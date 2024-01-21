<?php

namespace App\Http\Controllers;

use App\Models\QueryOfReport;
use App\Models\QueryTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use STS\JWT;
use STS\JWT\JWTFacade;
// use STS\JWT\JWTFacade;

class QueryOfReportController extends Controller
{
    public function index()
    {

        // TODO Pagination
        $allQueries = QueryOfReport::all();
        return view('pages.manage-queries.index', compact('allQueries'));
    }

    public function create()
    {
        return view('pages.manage-queries.create');
    } // DONE 

    public function store(Request $request)
    {
        // TODO data Validation ,, Internally not using request Class 
        $arrayOfTags =  $request->tags;
        $newQuery = new QueryOfReport;
        $newQuery->query_title = $request->f_query_title;
        $newQuery->report_category_id = $request->f_report_category_id;
        $newQuery->sql_query_string = $request->f_sql_query_string;
        $newQuery->db_name = $request->db_name;
        $newQuery->save();
        if ($arrayOfTags != null  || $arrayOfTags != []) {
            foreach ($arrayOfTags as $index => $tag) {
                $tagLabel  = new QueryTag();
                $tagLabel->tag = $tag;
                $tagLabel->query_id  = $newQuery->id;
                $tagLabel->save();
            }
        }
        DB::insert('insert into roles_queries (role_id, query_id) values (?, ?)', [1, $newQuery->id]);
        // ^ after adding the New Query , Use DB Facade to store it For the Admin Role 
        return view('pages.manage-queries.index');
    } // DONE 

    public function view($id)
    {
        $singleQuery = QueryOfReport::find($id);
        return view('pages.manage-queries.view', compact('singleQuery'));
    }
    public function edit($id)
    {
        $singleQuery = QueryOfReport::find($id);
        return view('pages.manage-queries.edit', compact('singleQuery'));
    }
    public function update(Request $request, $id)
    {
        $arrayOfTags =  $request->tags;
        $updatedQuery = QueryOfReport::find($id);
        $updatedQuery->query_title = $request->f_query_title;
        $updatedQuery->report_category_id = $request->f_report_category_id;
        $updatedQuery->sql_query_string = $request->f_sql_query_string;
        $updatedQuery->save();
        foreach ($updatedQuery->querytags as  $tagObject) {
            $tagObject->delete();
        }
        foreach ($arrayOfTags as $index => $tag) {
            $tagLabel  = new QueryTag();
            $tagLabel->tag = $tag;
            $tagLabel->query_id  = $updatedQuery->id;
            $tagLabel->save();
        }
        toastr()->info('Updated and Ok !!');
        return redirect(asset(route('queries.manage.index')));
    }
    public function delete(Request $request, $id)
    {
        $deletedQuery = QueryOfReport::find($id);
        $deletedQuery->delete();
        Toastr()->info('Query is Deleted Successfully');
        return redirect(asset(route('queries.manage.index')));
    }
}
