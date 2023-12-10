<?php

use App\Models\QueryOfReport;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


function filterQueriesForSearch($queryId, $searchPhrase)
{
    $keyWords  = explode(' ', $searchPhrase);
    $selectedQuery  = QueryOfReport::where('id', $queryId)->first();
    $queryTitleString = $selectedQuery->query_title;
    $queryTagsArray  = $selectedQuery->querytags()->pluck('tag')->toArray();
    $tagsMatch = false;
    $titleMatch = false;
    foreach ($keyWords as $searchKeyWord) {
        $titleMatch = stripos($queryTitleString, $searchKeyWord) !== false;
        foreach ($queryTagsArray as $tag) {
            if (stripos($tag, $searchKeyWord) !== false) {
                $tagsMatch = true;
                break; // Break out of the loop if a match is found
            }
        }
    }
    $result = $titleMatch || $tagsMatch;
    if ($result) {
        return $queryId;
    } else {
        return null;
    }
}


Route::get('/dashboard/search', function (Request $request) {
    $searchKeyWord  = $request->qq;
    $searchResultIds = [];
    if ($searchKeyWord) {
        $userId  = request()->user()->id;
        $userRoleId  = request()->user()->role_id;
        $attachedQueryIds = DB::table('roles_queries')
            ->where('role_id', $userRoleId)
            ->pluck('query_id');

        foreach ($attachedQueryIds->toArray() as $queryKey) {
            $searchResultIds[] = filterQueriesForSearch($queryKey, $searchKeyWord);
        }

        $resultingQueryies = QueryOfReport::whereIn('id', array_filter($searchResultIds))->get();
        return view('pages.searching', compact('resultingQueryies'));
    } else {
        $resultingQueryies = [];
        return view('pages.searching', compact('resultingQueryies'));
    }
})
    ->name('search-get')
    ->middleware('verified');
