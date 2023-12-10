<?php

use App\Models\QueryPrivot;
use App\Models\TempUd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});


Route::post('/save-pivot', function (Request $request) {
  $jsonData = $request->json()->all();
  $fileContent = $jsonData['fileContent'];
  $lines = explode("\n", $fileContent);
  $skip_imports = false;
  $indent_level = 1; # This is for 4 spaces 
  $output = '';
  foreach ($lines as $line) {
    $trimmed_line = trim($line);
    if (preg_match('/^(from|import)\s/i', $trimmed_line)) {
      $output .= $line . "\n";
      $skip_imports = true;
    } elseif ($skip_imports) {
      $output .= "def renderAlsoPivot(dataFrame):\n";
      $skip_imports = false;
    } else {
      $output .= str_repeat("\t", $indent_level) . $line . "\n";
    }
  }
  $output .= "renderAlsoPivot(dataFrame)\n"; // ! This is the Code For the Pivot END  ; 
  $queryId  = $jsonData['queryId'];
  $userId  = $jsonData['userId'];
  $originalCode  = $jsonData['originalCode'];
  if ($originalCode != null) {
    $qpvt = new QueryPrivot();
    $qpvt->query_pivot = $output;
    $qpvt->user_id = $userId;
    $qpvt->query_id = $queryId;
    $qpvt->original = $originalCode;
    $qpvt->save();
  }
  return response()->json(['msg' => "Ok"]);
});


Route::post('/get-uuid-data', function (Request $request) {
  $jsonData = $request->json()->all();
  $uuid = $jsonData['uuid'];
  $tempOBject = TempUd::where('id', $uuid)->first();
  return response()->json($tempOBject);
});


Route::post('/uuid-is-used', function (Request $request) {
  $jsonData = $request->json()->all();
  $uuid = $jsonData['uuid'];
  $tempOBject = TempUd::where('id', $uuid)->first();
  $tempOBject->isUsed = true;
  $tempOBject->save();
  return response()->json(['stats' => 'Done']);
});
