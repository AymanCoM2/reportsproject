<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDO;
ini_set('max_execution_time', 700); //180 =3 minutes
class DummyController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $selectedDB  = '';
            $received_query = $request->que;
            if ($request->db_name) {
                $selectedDB = $request->db_name;
            } else {
                $selectedDB = 'LB'; // ! Default DB Name;
            }
            // $serverName = "jou.is-by.us";
            $serverName = "jdry1.ifrserp.net";
            $databaseName = $selectedDB;
            $uid = "ayman";
            $port = "445";
            $pwd = "admin@1234";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                "TrustServerCertificate" => true,
                // "QueryTimeout" => 0.5,
                // PDO::ATTR_TIMEOUT => 60000000
                // "ConnectTimeout" => 2,
            ];
            $conn = new PDO("sqlsrv:server=$serverName,$port; Database = $databaseName;", $uid, $pwd, $options);
            $stmt = $conn->query($received_query);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row; // Append each row to the $data array
            }
            $firstElement = $data[0];
            $allKeys = [];
            $tdContent = "";
            foreach ($firstElement as $key => $value) {
                array_push($allKeys, $key);
                $tdContent .= "<td>$key</td>";
            }
            return response()->json(['data' => $data, 'first' => $firstElement, 'keys' => $allKeys, 'row' => $tdContent]);
        }
    }

    public function approveFirst()
    {
        return view('pages.approval');
    }
}
