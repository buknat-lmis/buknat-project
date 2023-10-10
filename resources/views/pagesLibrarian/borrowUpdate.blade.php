@extends('layouts.app')

@section('content')
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div class="card-body p-3">
            <div class="row gx-4">

                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            {{ $user->name }}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            {{ $user->id_number }} &nbsp; {{ $user->grade_and_section }}
                        </p>
                    </div>
                    <p class="mb-0 font-weight-bold text-sm"><strong> Note: </strong> &nbsp; {{ $user->remarks }}</p>
                </div>

                <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative end-0">
                        <ul class="nav nav-pills nav-fill p-1">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center"
                                    href="{{ route('borrowingForm') }}">
                                    <i class="ni ni-fat-add"></i>
                                    <span class="ms-2">Borrow</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center"
                                    href="{{ route('borrowerLists') }}">
                                    <i class="ni ni-books"></i>
                                    <span class="ms-2">Borrowed</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center"
                                    href="{{ route('returnedBook') }}">
                                    <i class="ni ni-books"></i>
                                    <span class="ms-2">Returned</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center btn">
                                    <i class="ni ni-books"></i>
                                    <span class="ms-2">Update</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('returnBook') }}">
        @csrf
        @method('PUT')

        @foreach ($bookTransactions as $bookTransaction)
            <div class="container-fluid py-2">
                <div class="row d-flex justify-content-center">
                    <div class="col-md-8 d-flex ">
                        <div class="card">
                            <div class="card-header pb-0">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0 text-uppercase">{{ $bookTransaction->book_title }}</p>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="form-check">
                                            <label class="form-check-label text-danger" for="check1">
                                                Check the box below for books to be returned
                                            </label>
                                            {{-- <input type="text" name="transaction_id" value="{{ $bookTransaction->id }}"> --}}
                                            <input class="form-check-input" name="transactionIDs[]" type="checkbox"
                                                value="{{ $bookTransaction->id }}" id="check1">
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Remarks /
                                                Note</label>
                                            <input class="form-control" type="text" name="remarks[]">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Returned Date</label>
                                            <input class="form-control" name="returned_dates[]" value="{{ date('Y-m-d') }}"
                                                type="date">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Returned Book
                                                Condition</label>
                                            <select name="returned_book_conditions[]" class="form-control">
                                                <option value="functional">functional</option>
                                                <option value="not-functional">not-functional</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endforeach
        <div class="row justify-content-center mt-2">
            <div class=" col-8">
                <button type="submit" class="justify-content-center btn btn-success form-control" name=""
                    id="">Submit</button>
            </div>
        </div>
    </form>

    @if (session('error'))
        <script>
            alert("Please select at least one book.");
        </script>
    @endif
@endsection
