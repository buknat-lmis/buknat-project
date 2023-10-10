@extends('layouts.app')

@section('content')
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div class="card-body p-3">
            <div class="row gx-4">

                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            {{-- {{ $user->name }} --}}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            {{-- {{ $user->id_number }} &nbsp; {{ $user->grade_and_section }} --}}
                        </p>
                    </div>
                    <p class="mb-0 font-weight-bold text-sm"><strong> Profile </strong>
                         &nbsp; {{ Auth::user()->name }}
                    </p>
                </div>



            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('updateProfile') }}">
        @csrf
        @method('PUT')

            <div class="container-fluid py-2">
                <div class="row d-flex justify-content-center">
                    <div class="col-md-8 d-flex ">
                        <div class="card">
                            <div class="card-header pb-0">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0 text-uppercase">

                                    </p>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label class="form-control-label" for="check1">
                                              ID Number
                                            </label>
                                            <input class="form-control" type="text" id="check1" value="{{ Auth::user()->id_number }}" disabled>
                                        </div>

                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Grade Level</label>
                                            <input class="form-control" type="text" value="{{ Auth::user()->grade_and_section }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label text-danger">OLD PASSWORD</label>
                                            <input class="form-control" type="password" name="oldPass" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">New Password</label>
                                            <input class="form-control" name="pass1" id="pass1" required
                                                type="password">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Confirm New Password</label>
                                            <input class="form-control" name="pass2" id="pass2" required
                                                type="password">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mt-2">
                <div class=" col-8">
                    <a class="btn btn-sm form-control mt-4 btn-success" href="#changePass" data-bs-toggle="modal">Update</a>
                </div>
            </div>

             <!-- Change Pass  -->
             <div class="modal fade" id="changePass" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
             <div class="modal-dialog modal-md" role="document">
                 <div class="modal-content">
                     <div class="modal-header">
                         <h5 class="modal-title" id="exampleModalLabel">Change the Pass?</h5>
                         <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                             aria-label="Close"></button>
                     </div>
                     <div class="modal-body">
                         <p class="text-center">
                             Do you want to proceed in Changing your passsword?
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

