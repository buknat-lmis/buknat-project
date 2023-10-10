<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Carbon\Carbon;
use App\Models\Book;
use App\Models\User;
use SimpleXMLElement;
use App\Models\Author;
use App\Models\BookSection;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\BookTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LibrarianController extends Controller
{
    public function dashboard()
    {
        $booksCounter = count(Book::all());
        $noOfStudents = count(User::where('role', '=', 0)->get());
        $noOfPending = count(User::where('role', '=', -1)->get());
        $pendingStudents = User::where('role', '=', -1)->get();
        $books = Book::all();

        $unreturnedBooks = BookTransaction::whereNull('returned_at')->count();
        $transactions = Transaction::with(['user'])
            ->whereHas('bookTransactions', function ($query) {
                $query->whereNull('returned_at');
            })
            ->orderBy('expected_return_date', 'asc')
            ->get();

        $transact = [];
        if ($transactions) {
            foreach ($transactions as $transaction) {
                $trxns = BookTransaction::where('transaction_id', $transaction->id)->get();
                $bookNames = [];
                foreach ($trxns as $trxn) {
                    $book = Book::where('id', $trxn->book_id)->first();
                    if ($book) {
                        $bookNames[] = $book;
                    }
                }
                $transact[$transaction->id] = $bookNames;
            }
        }


        // Get all items that are currently borrowed
        // $borrowedBooks = BookTransaction::where('returned_date', false)->get();

        return view('pagesLibrarian.dashboard', [
            'noOfBooks' => $booksCounter,
            'noOfStudents' => $noOfStudents,
            'noOfPending' =>  $noOfPending,
            'pendingStudents' => $pendingStudents,
            'transactions' => $transactions,
            'booksTransacted' => $transact,
            'books' => $books,
            'unreturnedBooks' => $unreturnedBooks
        ]);
        // return response()->json($transact);
    }

    // START book options
    public function addForm() //adding A Book FORM
    {
        $sections = BookSection::all();
        return view('pagesLibrarian.bookAdd', [
            'sections' => $sections, //section is addded to the page
        ]);
    }

    public function registerBook(Request $request)
    {
        $book = new Book();
        $author = new Author();
        $author_id = $request->input('author_id'); //for the ID of the Author to be validated

        if ($request->input('author_id')) {
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
        $book->class_no = $request->input('class_no');
        $book->edition = $request->input('edition');
        $book->section_id = $request->input('section');
        $book->publication_year = $request->input('publication_year');
        $book->date_acquired = $request->input('date_acquired');
        $book->no_of_copies = $request->input('no_of_copies');
        $book->available_copies = $request->input('no_of_copies'); // FOR COUNTING THE CURRENT AVAILABLE
        $book->on_hand_per_count = $request->input('on_hand_per_count');
        $book->book_status = $request->input('book_status');
        $book->book_condition = $request->input('book_condition');
        $book->isbn = $request->input('isbn');
        $book->publisher = $request->input('publisher');
        $book->genre = $request->input('genre');
        $book->language = $request->input('language');
        $book->number_of_pages = $request->input('number_of_pages');
        $book->location = $request->input('location');
        $book->summary = $request->input('summary');
        $book->added_by = Auth::user()->name; //THIS WOULD RECORD THE NAME OF THE LIBRARIAN WHO ADDED THE RECOR

        if ($request->hasFile('book_cover')) {
            $file = $request->file('book_cover');
            $fileName = $file->getClientOriginalName();
            $file->move(public_path('storage/BookCovers'), $fileName);
            $book->book_cover = $fileName; //Recording to DB the image

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

    public function bookLists() //Lists all books
    {
        $booksWSections = BookSection::with(['books' => function ($books) {
            $books->with(['author:id,author_id,author'])->get();
        }])->get();
        //  dd( $booksWSections[0]);
        // return view('admin.index', ['bookSections' => $booksWSections]);
        return view('pagesLibrarian.bookLists', compact('booksWSections'));
    }

    public function bookInfo($book)
    { //indiv book info

        $book = Book::where('id', '=', $book)->first();
        $sections = BookSection::all();
        return view('pagesLibrarian.bookInfo', [
            'book' =>  $book,
            'sections' => $sections,
        ]);
    }

    public function updateBook(Request $request)
    { // Update book
        $Book = Book::where('id', $request->book_id);
        $Book->update([
            'book_title' => $request->book_title,
            'section_id' => $request->section,
            'class_no' => $request->class_no,
            'edition' => $request->edition,
            'publisher' => $request->publisher,
            'number_of_pages' => $request->number_of_pages,
            'isbn' => $request->isbn,
            'location' => $request->location,
            'publication_year' => $request->publication_year,
            'book_condition' => $request->book_condition,
            'summary' => $request->summary,
            'no_of_copies' => $request->no_of_copies,
        ]);

        $Author = Author::where('id', $request->authorID);
        $Author->update([
            'author' => $request->author_name,
            'author_id' => $request->author_id,
        ]);
        return redirect()->back()->with('success', 'Book edited Successfully.');
    }


    //START Account Options
    public function accountPending()
    {

        $pendingStudents = User::where('role', '=', -1)->get();
        return view('pagesLibrarian.accountsPending', [
            'pendingStudents' => $pendingStudents,
        ]);
    }

    public function accountLists()
    {
        $students = User::where('role', '=', 0)->get();

        return view('pagesLibrarian.accountLists', [
            'students' => $students
        ]);
    }

    public function confirmAccount(Request $request)
    {

        // Update the user's fields in the database
        $user = User::findOrFail($request->id_account);
        $user->update([
            'id_number' => $request->id_number,
            'name' => $request->name,
            'grade_and_section' => $request->grade_and_section,
            'role' => 0
        ]);



        // // Generate QR code with given data
        $file_ID =  $user->id_number;
        // $qrCode = QrCode::size(250)->generate($file_ID);

        // //Save QR code as image in a specific folder
        $path = 'storage/StudentQrCodes/'; // path to folder where image will be saved
        $filename = $user->id . '.svg'; // name of the image file
        // QrCode::format('svg')->size(250)->generate($file_ID, public_path($path . $filename));
        // QrCode::format('svg')->size(400)->margin(10)->color(0, 0, 0)->backgroundColor(255, 255, 255)
        //     ->generate($file_ID, public_path($path . $filename));


        QrCode::format('svg') // Use PNG format for the merged image
            ->size(400)
            ->margin(10)
            ->color(0, 0, 0)
            ->backgroundColor(255, 255, 255)
            ->merge(public_path('cat.svg'), 0.5, true) // Merge the cat image with QR code
            ->generate($file_ID, public_path($path . $filename));

        return redirect()->back()->with('successApprove', 'User Approved successfully.');
    }

    public function deleteAccount(Request $request)
    {
        $filename = $request->idFile;
        // Get the full path of the file
        $path = storage_path('app/public/IDPic/' . $filename);
        File::delete($path);  // Delete the file
        $user = User::findOrFail($request->id_account);
        $user->delete();

        return redirect()->back()->with('successDelete', 'User Deleted successfully.');
    }
    //END ACCOUNT OPTIONS

    //START Borrowing Options
    public function borrowingForm()
    {
        $users = User::where('role', '=', 0) //GET ONLY THE ROLE "0" or the students
            ->orderBy('name', 'ASC')
            ->get();
        $books = Book::where('is_available', '!=', 0) //get only the available && copies available && FUNCTIONAL
            ->where('available_copies', '>', 0)
            ->where('book_condition', '=', 'functional')
            ->orderBy('book_title', 'ASC')
            ->get();

        return view('pagesLibrarian.borrowBook', [
            'users' =>  $users,
            'books' => $books,
        ]);
    }

    public function registerBorrower(Request $request)
    {
        $transaction = new Transaction();
        $transaction->user_id = $request->input('user_id');
        $transaction->borrowed_at = date('Y-m-d');
        $transaction->remarks = $request->input('remarks');
        $transaction->expected_return_date = $request->input('expected_return_date');
        $transaction->save();
        $insertedId = $transaction->id;

        foreach ($request->input('books') as $book) {
            $bookTransactions = new BookTransaction();
            $bookTransactions->transaction_id = $insertedId;
            $bookTransactions->book_id = $book;

            Book::where('id', '=', $book)->decrement('available_copies', 1); //books copy will be subtracted by 1
            $bookInfo = Book::where('id', '=', $book)->first();
            $bookTransactions->borrowed_book_condition = $bookInfo->book_condition;
            $bookTransactions->returned_at = (null);
            $bookTransactions->return_book_condition = (null);
            $bookTransactions->remarks = (null);
            $bookTransactions->save();
        }
        return redirect()->back()->with('success', 'Borrowing Recorded Successfully!');
    }

    public function borrowerLists()
    {
        $transactions = Transaction::with(['user', 'bookTransactions'])
            ->whereHas('bookTransactions', function ($query) {
                $query->whereNull('returned_at');
            })
            ->orderBy('expected_return_date', 'asc')
            ->get();
        $books = Book::all();
        return view('pagesLibrarian.borrowLists', compact('transactions', 'books'));
    }

    public function updateBorrow($transaction)
    { //view the borrowed and its books
        $user = DB::table('transactions')
            ->select('users.*', 'transactions.*') //gettinG all the User Details
            ->join('users', 'users.id', '=', 'transactions.user_id')
            ->where('transactions.id', $transaction)
            ->first();

        $bookTransactions = DB::table('book_transactions')
            ->select('book_transactions.*', 'books.*', 'book_transactions.id')
            ->join('books', 'books.id', '=', 'book_transactions.book_id')
            ->where('transaction_id', $transaction)
            ->get();

        return view('pagesLibrarian.borrowUpdate', [
            'user' =>  $user,
            'bookTransactions' => $bookTransactions,
        ]);
    }

    public function returnBook(Request $request)
    {
        if (!empty($request->transactionIDs)) {
            for ($i = 0; $i < count($request->transactionIDs); $i++) {

                $Book = BookTransaction::where('id', $request->transactionIDs[$i]);
                $Book->update([
                    'returned_at' =>  $request->returned_dates[$i],
                    'return_book_condition' =>  $request->returned_book_conditions[$i],
                    'remarks' => $request->remarks[$i],
                ]);

                if ($request->returned_book_conditions[$i] == 'functional') {
                    $bookToUpdate = BookTransaction::where('id', $request->transactionIDs[$i])->first();
                    Book::where('id', $bookToUpdate->book_id)->increment('available_copies', '1'); //returned book will be added to copies
                }
            }
            return redirect()->route('borrowerLists')->with('success', 'Returning Recorded Successfully.');
        } else {
            return redirect()->back()->with('error', 'Check atleast one book to return');
        }
    }

    public function returnedBook()
    {
        $transactions = Transaction::with(['user', 'bookTransactions'])
            ->whereHas('bookTransactions', function ($query) {
                $query->whereNotNull('returned_at');
            })
            ->orderBy('borrowed_at', 'asc')
            ->get();

        $books = Book::all();
        return view('pagesLibrarian.borrowReturned', compact('transactions', 'books'));
    }

    public function generateReport()
    {
        return view('pagesLibrarian.generateReport');
    }

    public function studentTransactions()
    {
        $students = User::where('role', 0)
            ->with('transactions')
            ->get();
        return view('pagesLibrarian.transactions', [
            'students' => $students,
        ]);

        // return response()->json($students);

    }

    public function studentTransaction($id)
    {
        $studentWTransactions = Transaction::where('id', $id)
            ->with('bookTransactions', 'user')
            ->first();

        // if ($studentWTransactions) {

        foreach ($studentWTransactions->bookTransactions as $bookTransaction) {

            $book[] = Book::where('id', '=', $bookTransaction->book_id)->first();
        }
        return view('pagesLibrarian.transaction', [
            'transaction' => $studentWTransactions,
            'books' => $book,
        ]);

        // return response()->json( $studentWTransactions);
    }
    public function updateStudents()
    {
        return view('pagesLibrarian.updateStudentrecords');
    }

    public function updateStudentsRecord(Request $request)
    {
        $users = User::where('grade_and_section', $request->grade_levelA)->get();

        if ($users) {
            foreach ($users as $user) {
                $user->update([
                    'grade_and_section' => $request->grade_levelB, // Replace 'new_year_value' with the actual value you want to set
                ]);
            }
        }

        echo '<script>
      alert("The Records were SuccessFully updated");
      </script>';

        return  view('pagesLibrarian.updateStudentrecords');
    }


    public function printGeneratedReport(Request $request)
    {
        // return view('pagesLibrarian.generateReport');

        $requestedMonth = $request->input('dateToReport');
        $totalCounter = 0;
        $grade_levels = ['Grade-7', 'Grade-8', 'Grade-9', 'Grade-10', 'Grade-11', 'Grade-12'];
        //  Query the database to filter transactions for the specified month, join wsith the users table, and filter by grade level

        $reportData = '<h4 style="text-align: center;">Bukidnon National High School Library Information System</h4>'
            . '<h5 style="text-align: center;">Main Campus Malaybalay City Bukidnon</h5>';
        $reportData .= '<p style="text-align: center; font-style: italic;">Borrowed Books in the Period of ' . $requestedMonth . '</p>';
        $reportData .= '<table border="1" style="width: 100%; text-align: center;">
                        <thead>
                            <tr>
                                <th>Grade Level</th>
                                <th>Number of borrowed books</th>
                            </tr>
                        </thead>
                        <tbody>';

        foreach ($grade_levels as $gradeLevel) {
            $transactionCount = DB::table('transactions')
                ->join('users', 'transactions.user_id', '=', 'users.id')
                ->whereMonth('transactions.borrowed_at', $requestedMonth)
                ->where('users.grade_and_section', $gradeLevel)
                ->count();
            $reportData .= '<tr>';
            $reportData .= '<td>' . $gradeLevel . '</td>';
            $reportData .= '<td>' . $transactionCount . '</td>';
            $reportData .= '</tr>';

            $totalCounter =  $totalCounter + $transactionCount;
        }

        $reportData .= '<tr>';

        $reportData .= '</tr>';
        $reportData .= '</tbody>';
        $reportData .= '</table>';
        $reportData .= '<p style="text-align: right; padding-right: 50px;"> Total books Borrowed: ' . $totalCounter . '</p>';
        $reportData .= '<br><br><br><br><br>';
        $reportData .= '<p style="text-align: left;">PREPARED BY:</p><br>';
        $reportData .= '<footer>
        <p style="font-size: 16px; font-weight: bold;margin: 0px;"><u>' . Auth::user()->name . '</u></p>
        <p style="font-style: italic;margin-left: 10px; margin-top:0px;">Librarian</p>
      </footer>';
        $reportData .= '<br>';
        $reportData .= '<p style="text-align: right; padding-right: 50px;">Noted:</p>';
        $reportData .= '<p style="text-align: right;">______________________</p><br>';
        $reportData .= '<br><br>';
        $reportData .= '<p style="text-align: right; font-style: italic;">Generated on: ' . date('Y-m-d H:i:s') . '</p><br>';
        $mpdf = new Mpdf();
        // $mpdf->WriteHTML('<h1>Report</h1>');
        $mpdf->WriteHTML($reportData);
        $mpdf->Output('report.pdf', 'I');
    }
}
