@extends('layouts.app')

@section('content')
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div class="card-body p-3">
            <div class="row gx-4">

                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">

                        </p>
                    </div>
                    <p class="mb-0 font-weight-bold text-sm"><strong> Generate Report </strong>
                    </p>
                </div>

            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('printGeneratedReport') }}">
        @csrf


        <div class="container-fluid py-2">
            <div class="row mt-3">

                <div class="col-3">
                    <p class="text-center font-weight-bold">
                        Please Select the Month to Report:
                    </p>
                </div>
                <div class="col-5 text-center font-weight-bold">
                    <input class="form-control " type="month" id="check1" name="dateToReport">
                </div>
            </div>


        </div>

        <div class="row justify-content-center mt-2">
            <div class=" col-11">
                <a class="btn btn-sm form-control mt-4 btn-success" href="#changePass" data-bs-toggle="modal">Generate
                    Report</a>
            </div>
        </div>

        <!-- Change Pass  -->
        <div class="modal fade" id="changePass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Print Report?</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-center">
                            Do you want to Proceed in Printing?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Proceed</button>

                    </div>
                </div>
            </div>
    </form>
    </div>


    </form>
@endsection
