<?php

use App\Http\Controllers\DashController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\approvedUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DummyController;
use App\Http\Controllers\FileGenerationController;
use App\Http\Controllers\UserController;
use App\Models\QueryPrivot;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

Auth::routes(['verify' => true]);
Route::get('/', function () {
    return view('welcome');
})->name('welcome');
// adminRole
Route::get('/dash', [DashController::class, 'index'])->name('dash.index')->middleware(['verified', approvedUser::class]);

Route::group([], __DIR__ . '/CategoryRoutes.php');
Route::group([], __DIR__ . '/QueryRoutes.php');
Route::group([], __DIR__ . '/RoleRoutes.php');
Route::group([], __DIR__ . '/UserRoutes.php');
Route::group([], __DIR__ . '/SearchRoutes.php');

Route::post('/data/data', [DummyController::class, 'index'])
    ->name('vvv');

Route::get('/needapproval', [DummyController::class, 'approveFirst'])
    ->name('need-approval')
    ->middleware('verified'); // TODO not-approved-Yet-User

Route::post('/toggleApproval/{userId}', [UserController::class, 'toggleApproval'])
    ->name('toggleUserApproval')
    ->middleware(['verified', RoleMiddleware::class]);

Route::get('data/generate-pdf', [FileGenerationController::class, 'generatePdf'])
    ->name('pdf-generate');


Route::get('language/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
})->name('name-switcher');


// -------------------- This is the Pivots Code 

Route::get('/user-pivots/{query_id}', function (Request $request) {
    $theQueryId  = $request->query_id;
    return view('pivot-frame', compact('theQueryId'));
})->name('user-pivots');


Route::post('/get-pivots-count', function (Request $request) {
    $theQueryId  = $request->qId;
    $userId  = $request->usrId;
    $allPivots  = \App\Models\QueryPrivot::where('user_id', $userId)
        ->where('query_id', $theQueryId)
        ->count();
    return response()->json(['count' => $allPivots]);
})->name('get-pivot-count');


Route::post('/dash/pivot/update/{id}', function (Request $request) {
    $updatedPivot  = QueryPrivot::find($request->pk);
    $updatedPivot->pivot_name  = $request->value;
    $updatedPivot->save();
    return response()->json($request->value, 200);
})->name('rename-pivot');


Route::get('/manage-pivots/{qId}', function (Request $request) {
    $allRelatedPivots  = QueryPrivot::where('user_id', request()->user()->id)
        ->where('query_id', $request->qId)->get();
    return view('pages.manage-pivots.index', compact('allRelatedPivots'));
})->name('manage-pivots-get');


Route::post('/manage-pivots-post', function (Request $request) {
    $userId  = $request->user()->id; // This is the Id For user To save Pivot for him 
    $primaryPivot  = QueryPrivot::findOrFail($request->primary);
    $secondaryPivot  = QueryPrivot::findOrFail($request->secondary);
    $processedCode = concatStrings($primaryPivot->original, $secondaryPivot->original);
    // dd($processedCode);
    $theOriginal = justOneImport($primaryPivot->original . "\n" . $secondaryPivot->original);
    $qpvt = new QueryPrivot();
    $qpvt->query_pivot = $processedCode;
    $qpvt->user_id = $userId;
    $qpvt->query_id = $primaryPivot->query_id;
    $qpvt->original = $theOriginal;
    $randomString = Str::random(7);
    $qpvt->pivot_name  = "Merge" . "-" . $randomString;
    $qpvt->save();
    // return view('pages.manage-pivots.index', compact('allRelatedPivots'));
    return back();
})->name('manage-pivots-post');


function concatStrings($first, $second)
{
    $final = $first . "\n" . $second;
    $final = justOneImport($final);  // * Remove Repeating Imports 
    $lastLine = handleOneOrBulkPivots($final); // ? Get Last Line Before Function Call 
    $dfNamesArr = getDfNames($final);
    $lines = explode("\n", $final);
    $skip_imports = false;
    $indent_level = 1; # This is for 4 spaces 
    $outputF = "";
    $outputF .= "from mitosheet.streamlit.v1 import spreadsheet" . "\n";

    $df_index = 0; // Index for iterating through df_names

    foreach ($lines as $line) {
        $trimmed_line = trim($line);
        if (preg_match('/^(from|import)\s/i', $trimmed_line)) {
            $outputF .= $line . "\n";
            $skip_imports = true;
        } elseif ($skip_imports) {
            $outputF .= "def renderAlsoPivot(dataFrame):\n";
            $skip_imports = false;
        } else {
            // Check if the line contains the pattern ' = pivot_table.reset_index()'
            if (strpos($line, ' = pivot_table.reset_index()') !== false) {
                // Replace 'dataFrame_pivot' with the current element from df_names array
                $line = preg_replace('/^(\s*)([^=\s]+)\s*=\s*pivot_table.reset_index\(\)\s*$/i', '${1}' . $dfNamesArr[$df_index] . ' = pivot_table.reset_index()', $line);
                $df_index++; // Move to the next element in df_names
            }
            $outputF .= str_repeat("\t", $indent_level) . $line . "\n";
        }
    }

    $outputF .= "\t" . $lastLine . "\n";
    $outputF .= "renderAlsoPivot(dataFrame)\n"; // ! This is the Code For the Pivot END  ; 

    return $outputF;
}


function justOneImport($final)
{
    $lines = explode("\n", $final);
    $imports_count = 0;
    $output = '';
    foreach ($lines as $line) {
        $trimmed_line = trim($line);
        if (preg_match('/^(from|import)\s/i', $trimmed_line)) {
            if ($imports_count == 0) {
                $output .= $line . "\n";
            }
            $imports_count++;
        } else {
            $output .= $line . "\n";
        }
    }
    return $output;
}

function handleOneOrBulkPivots($codeString)
{
    $pivot_count = substr_count($codeString, '# Pivoted dataFrame into');
    $df_names = ['dataFrame', 'dataFrame_pivot']; // TODO : ???? for the First One 
    for ($i = 1; $i <= $pivot_count - 1; $i++) {
        $df_names[] = 'dataFrame_pivot_' . $i;
    }
    $output_string = sprintf("new_dfs, code = spreadsheet(%s, df_names=%s)\n", implode(',', $df_names), json_encode($df_names));
    return $output_string;
}

function getDfNames($codeString)
{
    $pivot_count = substr_count($codeString, '# Pivoted dataFrame into');
    $df_names = ['dataFrame_pivot']; // TODO : ???? for the First One 
    for ($i = 1; $i <= $pivot_count - 1; $i++) {
        $df_names[] = 'dataFrame_pivot_' . $i;
    }
    return $df_names;
}



Route::post('/delete-pivots', function (Request $request) {
    $toDeletePivot  = QueryPrivot::find($request->deletedPivodId);
    if ($toDeletePivot) {
        $toDeletePivot->delete();
        return back();
    } else {
        return back();
    }
})->name('delete-pivot');
