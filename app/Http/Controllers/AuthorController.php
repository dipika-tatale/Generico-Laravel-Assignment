<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Author;

class AuthorController extends Controller
{
    public function index()
    {
    	$columns = array(
                     1 => 'name',
                     2 => 'created_at'
                    );  

	    $start = $_POST['start'];
	    $limit = $_POST["length"];    
	    $q = $_POST["search"]["value"];
	    
	    $fields = array("order"     => (isset($_POST['order'][0])) ? $columns[$_POST['order'][0]['column']] : '',
	                    "dir"       =>  (isset($_POST['order'][0])) ? $_POST['order'][0]['dir'] : '',
	                    "q"         =>  $q
	                );

	    $query  = Author::where('status', '!=', 2);

	    $q = $fields["q"];
	    if($q != "")
        {
            $query->where('name', 'like', '%' . $q . '%');
        }

	    $sort_by = $fields["order"];
	    if($sort_by != "")
	    	$query->orderBy($sort_by, $fields["dir"]);
        else
            $query->orderBy('created_at', 'desc');

	    $authors  =  $query->offset($start)->limit($limit)->get();
	    //dd($authors);
	    
	    if($q != "")
        {
            $total_rows = Author::where('status', '!=', 2)->where('name', 'like', '%' . $q . '%')->count();
        } else {
		    $total_rows = Author::where('status', '!=', 2)->count();
		}
	  
	    $data = array();
	    foreach($authors as $row) 
	    {
	        $rowdata = array();  

	        $rowdata[] = $row["id"];
	        $rowdata[] = $row["name"];
	        $rowdata[] = date("d M, Y h:i A", strtotime($row["created_at"]));
	        $rowdata[] = ((int) $row["status"] == 1) ? 'Active' : '<span style="color:red;">InActive</span>';
	        $rowdata[] = '<a href="/authors/'.$row['id'].'/edit" class="btn btn-primary btn-sm edit_author">
	                        edit
	                    </a>
	                    <a href="javascript:void(0)" class="btn btn-warning btn-sm delete_author" row_id="'.$row['id'].'">
	                        delete
	                    </a>';
	        
	        $data[] = $rowdata;
	    }   
	    
	    $json_data = array( "draw"            => intval( $_POST['draw'] ),   
	                        "recordsTotal"    => intval( $total_rows ),  
	                        "recordsFiltered" => intval( $total_rows),
	                        "data"            => $data  
	                      );
	    
	    die(json_encode($json_data));
    }

    public function create()
    {
    	return view('add_author');
    }

    public function store(Request $request)
    {
    	//dd($request->all());

    	$validator = Validator::make($request->all(), [
                'author_name' => 'required',
        ], [
		    'author_name.required' => 'Name field is required.',
		]);
        
        if ($validator->fails()) {
        	$html = '';

        	$errors = $validator->errors();
        	if ($errors->all())
        	{
              	$html .= '<div class="alert alert-danger">
                              <ul>';
                  foreach ($errors->all() as $error) {
                    $html .= '<li>'.$error.'</li>';
                  }
                $html .= '</ul>
                              </div>';
            }

            die(json_encode(array('status' => 'error', 'html' => $html)));
        }

        $author = new Author;
        $author->name = $request->author_name;
        $author->status = 1;
        $author->save();

        die(json_encode(array('status' => 'success', 'html' => '')));
    }

    public function update(Request $request)
    {
    	//dd($request->all());

    	$validator = Validator::make($request->all(), [
                'author_name' => 'required',
        ], [
		    'author_name.required' => 'Name field is required.',
		]);
        
        if ($validator->fails()) {
        	$html = '';

        	$errors = $validator->errors();
        	if ($errors->all())
        	{
              	$html .= '<div class="alert alert-danger">
                              <ul>';
                  foreach ($errors->all() as $error) {
                    $html .= '<li>'.$error.'</li>';
                  }
                $html .= '</ul>
                              </div>';
            }

            die(json_encode(array('status' => 'error', 'html' => $html)));
        }

        $author = Author::where('id', $request->author_id)->firstOrFail();
        $author->update([
        	'name' => $request->author_name,
            'status' => $request->status,
        ]);

        die(json_encode(array('status' => 'success', 'html' => '')));
    }

    public function delete(Request $request)
    {
        $author = Author::where('id', $request->row_id)->firstOrFail();
        $author->update(['status' => 2]);
        
        die(json_encode(array('status' => 'success')));
    }

    public function show($author_id)
    {
        $author_result = Author::select('id', 'name', 'status')
                    ->where('id', $author_id)->firstOrFail();
        
        return view('edit_author', ['author' => $author_result]);
    }
}
