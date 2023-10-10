@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <a href="{{ route('bookLists') }}">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Books</p>
                                        <h5 class="font-weight-bolder">
                                            {{ $noOfBooks }}
                                        </h5>
                            </a>

                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                            <a href="{{ route('bookLists') }}"> <i class="ni ni-books text-lg opacity-10"
                                    aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <a href="{{ route('accountLists') }}">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Number Of Students</p>
                                <h5 class="font-weight-bolder">
                                    {{ $noOfStudents }}
                                </h5>
                    </a>

                </div>
            </div>
            <div class="col-4 text-end">
                <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                    <a href="{{ route('accountLists') }}"> <i class="ni ni-circle-08 text-lg opacity-10"
                            aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <a href="{{ route('accountPending') }}">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Pending Students</p>
                                <h5 class="font-weight-bolder">
                                    {{ $noOfPending }}
                                </h5>
                    </a>

                </div>
            </div>
            <div class="col-4 text-end">
                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                    <a href="{{ route('accountPending') }}"> <i class="ni ni-paper-diploma text-lg opacity-10"
                            aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Unreturned Books</p>
                            <h5 class="font-weight-bolder">
                                {{ $unreturnedBooks }}
                            </h5>

                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                            <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-8 mb-lg-0 mb-4">
            <div class="card ">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-2">Due Today & Pass Due Books</h6>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center ">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Borrower's Name</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Date Borrowed</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Expected Return Date</th>
                                <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Borrowed Book/s</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $today = date('Y-m-d');
                                $dueCounter = 0; $newAccountCounter =0;
                            @endphp
                            @if ($transactions)
                                @foreach ($transactions as $transaction)
                                    @if ($transaction->expected_return_date !== null)
                                    @php
                                        $dueCounter++;
                                    @endphp
                                        <tr>

                                            <td>

                                                <div class="d-flex px-2 py-1 text-primary">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">
                                                            {{ $transaction->user->name }}
                                                        </h6>
                                                        <p class="text-xs text-secondary mb-0">
                                                            <strong>ID No.</strong>
                                                            {{ $transaction->user->id_number }}
                                                        </p>
                                                    </div>
                                                </div>

                                            </td>

                                            <td>
                                                <p class="text-xs font-weight-bold mb-2">
                                                    {{ $transaction->borrowed_at }}</p>
                                            </td>

                                            <td class="align-middle text-center text-sm">
                                                <p class="text-xs font-weight-bold mb-2">
                                                    {{ $transaction->expected_return_date }}</p>
                                            </td>

                                            <td class="text-start">
                                                <?php $count = 1; ?>
                                                @foreach ($transaction->bookTransactions as $bookTransaction)
                                                    @foreach ($books as $book)
                                                        @if (is_null($bookTransaction->returned_at))
                                                            @if ($bookTransaction->book_id == $book->id)
                                                                <p class="text-xs font-weight-bold mb-2">
                                                                    {{ $count }} .
                                                                    {{ $book->book_title }} </p>
                                                                <p class="text-xs text-secondary mb-0"></p>
                                                                <?php $count++; ?>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endforeach

                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('updateBorrow', ['transaction' => $transaction->id]) }}"
                                                    ddata-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Update Borrow">
                                                    <span class="fas fa-edit"></span>
                                                </a>

                                            </td>

                                        </tr>

                                    @endif
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    @if ($dueCounter == 0)
                    <div class="alert text-center alert-success text-white" role="alert">
                      Hooray! No Books Due Today.
                      </div>
                    @endif
                </div>
            </div>

        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header pb-0 p-3">
                    <h6 class="mb-0">Accounts Waiting for Validation</h6>
                </div>
                <div class="card-body p-3">
                    <ul class="list-group">

                        @if ($noOfPending == 0)
                            <div class="alert-secondary alert-dismissible fade show" role="alert">

                                <div class="alert text-center alert-success text-white" role="alert">
                                    Hooray!  No new Accounts are created, and None required Validation..
                                    </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close">x</button>
                            </div>
                        @endif
                        @foreach ($pendingStudents as $student)
                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                        <i class="ni ni-mobile-button text-white opacity-10"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark text-sm">{{ $student->name }}</h6>
                                        <span class="text-xs">{{ $student->grade_and_section }} &nbsp;<span
                                                class="font-weight-bold">&nbsp; {{ $student->id_number }}</span></span>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <a href="{{ route('accountPending') }}"> <button
                                            class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i
                                                class="ni ni-bold-right" aria-hidden="true"></i></button></a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    </div>

    @if (session('error'))
        <script>
            alert("Passwords Don't Match");
        </script>
    @endif
    @if (session('success'))
    <script>
        alert("Passwords Changed. Try Logging out");
    </script>
@endif
@endsection
