@extends('layouts.app')

@section('content')
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="{{ url('assets/assets/img/team-1.jpg') }}" alt="profile_image"
                            class="w-100 border-radius-lg shadow-sm">
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                          Update Student Records
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                           This section has the option to update the Grade level of students base on the record.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative end-0">
                        <ul class="nav nav-pills nav-fill p-1">

                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center btn"
                                    @disabled(true)">
                                    <i class="ni ni-lock-circle-open"></i>
                                    <span class="ms-2">Student Records</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </div>


                <div class="container-fluid py-4 mt-2">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="card mt-2"></div>

                            <div class="col-12">
                                <div class="card">

                                    <div class="card-body">
                                        <p class="text-uppercase text-sm">Update Students</p>
                                        <form method="POST" action="{{ route('updateStudentsRecord') }}">
                                        @csrf

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <label for="gender-select">Grade Level:</label>
                                                        <select class="form-select form-control" id="gender-select"
                                                            name="grade_levelA">
                                                            <option value="Grade-7" selected>Grade-7</option>
                                                            <option value="Grade-8" selected>Grade-8</option>
                                                            <option value="Grade-9" selected>Grade-9</option>
                                                            <option value="Grade-10" selected>Grade-10</option>
                                                            <option value="Grade-11" selected>Grade-11</option>
                                                            <option value="Grade-12" selected>Grade-12</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="gender-select">Grade Level to be Promoted:</label>
                                                    <select class="form-select form-control" id="gender-select"
                                                        name="grade_levelB">
                                                        <option value="Grade-7" selected>Grade-7</option>
                                                        <option value="Grade-8" selected>Grade-8</option>
                                                        <option value="Grade-9" selected>Grade-9</option>
                                                        <option value="Grade-10" selected>Grade-10</option>
                                                        <option value="Grade-11" selected>Grade-11</option>
                                                        <option value="Grade-12" selected>Grade-12</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <hr class="horizontal dark">
                                            <input type="submit" class="btn btn-primary form-control">

                                            </form>
                                        </div>


                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
