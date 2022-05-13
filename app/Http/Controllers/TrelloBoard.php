<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CardList;
use App\Models\ColList;
use Illuminate\Support\Facades\DB;
class TrelloBoard extends Controller
{
    public function __construct()
    {

    }
    public function index()
    {
        $cols = ColList::with(['cardlists'])->get();
        return view('welcome',compact('cols'));
    }
    public function getColLists()
    {
        $cols = ColList::with(['cardlists'])->get();
        return response()->json(["data"=>$cols], 200);
    }
    public function storeCol(Request $request)
    {

        $this->validate($request, [
            'title' => 'required|string|unique:col_lists'

        ]);

        $column =  new ColList();

        $column->title = $request->title;
        $column->status = 1;
        $column->orderCol = intval(ColList::count())+1;
        $column->save();
        return response()->json(["data"=>$column], 201);
    }

    public function storeCard(Request $request)
    {

        $this->validate($request, [
            'title' => 'required|string|unique:card_lists,list_id',
            'description' => 'required|string',
            'list_id' => 'required|exists:col_lists,id'

        ]);

        $card =  new cardList();

        $card->title = $request->title;
        $card->description = $request->description;
        $card->status = 1;
        $card->list_id = $request->list_id;
        $card->orderRow =intval(DB::table('card_lists')->where("list_id",$request->list_id)->count())+1;
        $card->save();
        return response()->json(["data"=>$card], 201);
    }
    public function deleteCol(Request $request)
    {
        CardList::where("list_id",$request->id)->delete();
        ColList::where("id",$request->id)->delete();
        return response()->json(["message"=>"Deleted Successfully!"], 200);
    }
    public function deleteCard(Request $request)
    {
        CardList::where("id",$request->id)->delete();
        return response()->json(["message"=>"Deleted Successfully!"], 200);
    }
    public function dbBackup()
    {
        $DbName             = env('DB_DATABASE');
        $get_all_table_query = "SHOW TABLES ";
        $result = DB::select(DB::raw($get_all_table_query));

        $prep = "Tables_in_$DbName";
        foreach ($result as $res){
            $tables[] =  $res->$prep;
        }



        $connect = DB::connection()->getPdo();

        $get_all_table_query = "SHOW TABLES";
        $statement = $connect->prepare($get_all_table_query);
        $statement->execute();
        $result = $statement->fetchAll();


        $output = '';
        foreach($tables as $table)
        {
            $show_table_query = "SHOW CREATE TABLE " . $table . "";
            $statement = $connect->prepare($show_table_query);
            $statement->execute();
            $show_table_result = $statement->fetchAll();

            foreach($show_table_result as $show_table_row)
            {
                $output .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
            }
            $select_query = "SELECT * FROM " . $table . "";
            $statement = $connect->prepare($select_query);
            $statement->execute();
            $total_row = $statement->rowCount();

            for($count=0; $count<$total_row; $count++)
            {
                $single_result = $statement->fetch(\PDO::FETCH_ASSOC);
                $table_column_array = array_keys($single_result);
                $table_value_array = array_values($single_result);
                $output .= "\nINSERT INTO $table (";
                $output .= "" . implode(", ", $table_column_array) . ") VALUES (";
                $output .= "'" . implode("','", $table_value_array) . "');\n";
            }
        }
        $file_name = 'database_backup_on_' . date('y-m-d') . '.sql';
        $file_handle = fopen($file_name, 'w+');
        fwrite($file_handle, $output);
        fclose($file_handle);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file_name));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_name));
        ob_clean();
        flush();
        readfile($file_name);
        unlink($file_name);
    }
}
