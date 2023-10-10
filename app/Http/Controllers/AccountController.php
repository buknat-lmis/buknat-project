<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AccountController extends Controller
{

    public function index()
    {
        return view('pagesAuth.login');
    }

    public function loginForm()
    {
        return view('pagesAuth.login');
    }

    public function loginUser(Request $request)
    {
        $credentials = $request->only('id_number', 'password');

        if (Auth::attempt($credentials)) {

            if (auth()->user()->role == 1) {
                return redirect()->intended('Librarian/dashboard'); // Authentication for Librarian

            } else if (auth()->user()->role == 0)  {
                return redirect()->intended('Student/dashboard'); // Authentication for SuperAdmin
                // echo "kjsahjksah";
            } else if (auth()->user()->role == 2)  {
                return redirect()->intended('Teacher/dashboard'); // Authentication for SuperAdmin
                // echo "kjsahjksah";
            } else {
            return redirect()->route('loginForm')->withErrors(['id_number' => 'Your Account has not yet confirmed by the Librarian.']);
            }
        } else {

            return back()->withErrors([
                'id_number' => 'The provided credentials do not match our records.',
            ]);
        }
    }


    public function inquireBooks(){
        $bookWithSectionAuthor =  Book::with('author', 'section')->get();
       return view('pagesAuth.inquirebook', ['books' => $bookWithSectionAuthor ,]);
    // return response()->json($bookWithSectionAuthor);
}


    public function signupForm()
    {
        return view('pagesAuth.signup');
    }


    public function registerUser(Request $request){
        $count = User::where('id_number', '=', $request->input('id_number'))->count();
        if ($count > 0) {
        return redirect()->route('signupForm')->withErrors(['id_number' => 'ID number can only have one account.']);
        } else {

        $user = new User();
        $user->name = $request->input('name');
        $user->id_number = $request->input('id_number');
        $user->grade_and_section = $request->input('grade_and_section');
        $user->office_or_department = '';
        $user->password = Hash::make('password');
        $user->role = -1;

        if ($request->hasFile('id_pic')) {
            $file = $request->file('id_pic');
            $fileName = $file->getClientOriginalName();
            $file->move(public_path('storage/IDPic'), $fileName);
            $user->id_pic =  $fileName; //storing in DB
        }
        $user->save();

         return redirect()->back()->with('success', 'Account created successfully. The Librarian will have to confirm it first, before you can use it');
         }
    }

    public function profile(Request $request){
        $user = User::where('id', $request->userID)->first();
        return view('layouts.profile');
    }

    public function updateProfile(Request $request){

        function LibrarianError(){
        return redirect()->route('librarianDashboard')->with('error', 'Pass');
        }
        function StudentError(){
            return redirect()->route('studentDashboard')->with('error', 'Pass');
        }
        function StudentError(){
            return redirect()->route('teacherDashboard')->with('error', 'Pass');
        }

        $user = Auth::user();

        if (!Hash::check($request->oldPass, $user->password)) {
            if (Auth::user()->role == 0){
                return redirect()->route('studentDashboard')->with('error', 'Pass');

            }elseif (Auth::user()->role == 1){
                return redirect()->route('librarianDashboard')->with('error', 'Pass');

            }
            elseif (Auth::user()->role == 2){
                return redirect()->route('teacherDashboard')->with('error', 'Pass');

            }

        }else{//password Matches and procceed with changing IT

            if($request->pass1 == $request->pass2){
                $user->update([
                    'password' => Hash::make($request->pass1)
                ]);
                if (Auth::user()->role == 0){
                    return redirect()->route('studentDashboard')->with('success', 'Pass');

                }elseif (Auth::user()->role == 1){
                    return redirect()->route('librarianDashboard')->with('success', 'Pass');
                }elseif (Auth::user()->role == 2){
                    return redirect()->route('teacherDashboard')->with('success', 'Pass');
                }

            }else{
                if (Auth::user()->role == 0){
                    return redirect()->route('studentDashboard')->with('error', 'Pass');

                }elseif (Auth::user()->role == 1){
                    return redirect()->route('librarianDashboard')->with('error', 'Pass');

                }elseif (Auth::user()->role == 2){
                    return redirect()->route('teacherDashboard')->with('success', 'Pass');
                }

            }
        }


    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
