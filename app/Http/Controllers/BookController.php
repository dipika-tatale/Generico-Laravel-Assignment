<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Author;

class BookController extends Controller
{
    public function index()
    {
    	$columns = array(
                     1 => 'title',  
                     2 => 'price', 
                     3 => 'created_at' 
                    );  

	    $start = $_POST['start'];
	    $limit = $_POST["length"];    
	    $q = $_POST["search"]["value"];
	    
	    $fields = array("order"     => (isset($_POST['order'][0])) ? $columns[$_POST['order'][0]['column']] : '',
	                    "dir"       =>  (isset($_POST['order'][0])) ? $_POST['order'][0]['dir'] : '',
	                    "q"         =>  $q
	                );

	    $query  = Book::where('status', '!=', 2);

	    $q = $fields["q"];
	    if($q != "")
        {
            $query->where('title', 'like', '%' . $q . '%');
        }

	    $sort_by = $fields["order"];
	    if($sort_by != "")
	    	$query->orderBy($sort_by, $fields["dir"]);
        else
            $query->orderBy('created_at', 'desc');

	    $books  =  $query->offset($start)->limit($limit)->get();
	    //dd($books);
	    
	    if($q != "")
        {
            $total_rows = Book::where('status', '!=', 2)->where('title', 'like', '%' . $q . '%')->count();
        } else {
		    $total_rows = Book::where('status', '!=', 2)->count();
		}
	  
	    $data = array();
	    foreach($books as $row) 
	    {
	        $rowdata = array();  

	        $rowdata[] = $row["id"];
	        $rowdata[] = $row["title"];
	        $rowdata[] = 'INR.'.$row["price"];
	        $rowdata[] = date("d M, Y h:i A", strtotime($row["created_at"]));
	        $rowdata[] = ((int) $row["status"] == 1) ? 'Active' : '<span style="color:red;">InActive</span>';
	        $rowdata[] = '<a href="/books/'.$row['id'].'/edit" class="btn btn-primary btn-sm edit_book">
	                        edit
	                    </a>
	                    <a href="javascript:void(0)" class="btn btn-warning btn-sm delete_book" row_id="'.$row['id'].'">
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
        $author_result = Author::select('id', 'name')
                    ->where('status', 1)->get();

    	return view('add_book', ['authors' => $author_result]);
    }

    public function store(Request $request)
    {
    	//dd($request->all());

    	$validator = Validator::make($request->all(), [
                'title' => 'required',
                'price' => 'required|numeric'
        ], [
		    'title.required' => 'Title field is required.',
		    'price.required' => 'Price field is required.',
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

        $book = Book::create([
            'title' => $request->title,
            'price' => $request->price,
            'description' => $request->description,
            'status' => 1
        ]);

        $selected_authors = explode(',', $request->selected_authors);
        $book->authors()->syncWithoutDetaching($selected_authors);

        die(json_encode(array('status' => 'success', 'html' => '')));
    }

    public function update(Request $request)
    {
    	// dd($request->all());

    	$validator = Validator::make($request->all(), [
                'title' => 'required',
                'price' => 'required|numeric'
        ], [
		    'title.required' => 'Title field is required.',
		    'price.required' => 'Price field is required.',
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

        $book = Book::where('id', $request->book_id)->firstOrFail();
        $book->update([
        	'title' => $request->title,
        	'price' => $request->price,
        	'description' => $request->description,
            'status' => $request->status,
        ]);

        $selected_authors = explode(',', $request->selected_authors);
        $book->authors()->syncWithoutDetaching($selected_authors);

        die(json_encode(array('status' => 'success', 'html' => '')));
    }

    public function delete(Request $request)
    {
        $book = Book::where('id', $request->row_id)->firstOrFail();
        $book->update(['status' => 2]);
        
        die(json_encode(array('status' => 'success')));
    }

    public function show($book_id)
    {
        $author_result = Author::select('id', 'name')
                    ->where('status', 1)->get();

        $book_result = Book::with('authors')->select('id', 'title', 'price', 'description', 'status')
                    ->where('id', $book_id)->firstOrFail();
        
        return view('edit_book', ['book' => $book_result, 'authors' => $author_result]);
    }
}
