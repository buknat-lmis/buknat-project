<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Author;
use App\Models\BookSection;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\BookTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class StudentController extends Controller
{
    public function studentDashboard(){
        $user_id = Auth::user()->id;

        $transactions = Transaction::with(['user', 'bookTransactions'])
        ->where('user_id', $user_id)
        ->whereHas('bookTransactions', function($query) {
            $query->whereNull('returned_at');
        })
        ->orderBy('expected_return_date', 'desc')
        ->get();

        $books = Book::all();
        return view('pagesStudent.dashboard' ,compact('transactions', 'books'));
    }

    public function StudentbookLists(){
        $booksWSections = BookSection::with(['books' => function($books) {
            $books->with(['author:id,author_id,author'])->get();
        }])->get();
        //  dd( $booksWSections[0]);
        // return view('admin.index', ['bookSections' => $booksWSections]);
        return view('pagesStudent.bookLists',compact('booksWSections'));
    }

    public function studentBorrowed(){
        $user_id = Auth::user()->id;
        $transactions = Transaction::with(['user', 'bookTransactions'])
        ->where('user_id', $user_id)
        ->whereHas('bookTransactions')
        ->orderBy('borrowed_at', 'desc')
        ->get();
        $books = Book::all();

        return view('pagesStudent.borrowedLists', compact('transactions', 'books'));
    }

    public function bookInfo($id){

        $book = Book::where('id', '=', $id)->first();
        $sections = BookSection::all();
        return view ('pagesStudent.bookInfo', [
        'book' =>  $book ,
        'sections' => $sections,
       ]);
    }

    public function addResearch(){
        return view('pagesStudent.addBook');
    }

    public function uploadResearch(Request $request){
        $book = new Book();
        $author = new Author();
        $author_id = $request->input('author_id'); //for the ID of the Author to be validated

        $author_id = Auth::user()->id;
        if ($author_id) {
            //fetching if the author ID exists
            $result = count(DB::table('authors')
                ->where('author_id', '=', $author_id)
                ->get());

            if ($result == 0) {
                $author->author_id = $author_id;
                $author->author = $request->input('author');
                $author->save();
                $book->author_id = $author->id; // use the recently saved ID
            } else {
                $book->author_id = $author_id;
            }
        }
        $book->book_title = $request->input('book_title');
        $book->class_no = 001;
        $book->edition = 1;
        $book->section_id = 8;
        $book->publication_year =2023;
        $book->date_acquired = date('y-m-d');
        $book->no_of_copies = '001';
        $book->available_copies = '001'; // FOR COUNTING THE CURRENT AVAILABLE
        $book->on_hand_per_count = '001';
        $book->book_status = 'available';
        $book->book_condition = 'functional';
        $book->isbn = 001;
        $book->publisher = '001';
        $book->number_of_pages = '001';
        $book->book_cover = '001';
        $book->summary = '001';
        $book->added_by = Auth::user()->name; //THIS WOULD RECORD THE NAME OF THE LIBRARIAN WHO ADDED THE RECOR

        if ($request->hasFile('location')) {
            $file = $request->file('location');
            $fileName = $file->getClientOriginalName();
            $file->move(public_path('storage/UploadedBooks'), $fileName);
            $book->location = $fileName; //Recording to DB the image
        }
        $book->save();
        $file_ID = $book->id;

        // // Generate QR code with given data
        $file_ID = $book->getKey();
        // $qrCode = QrCode::size(250)->generate($file_ID);

        //Save QR code as image in a specific folder
        $path = 'storage/BookQRCodes/'; // path to folder where image will be saved
        $filename = $file_ID . '.svg'; // name of the image file
        // QrCode::format('svg')->size(250)->generate($file_ID, public_path($path . $filename));
        QrCode::format('svg')->size(400)->margin(10)->color(0, 0, 0)->backgroundColor(255, 255, 255)
            ->generate($file_ID, public_path($path . $filename));

        return redirect()->back()->with('success', 'Book created successfully!');
    }

}
