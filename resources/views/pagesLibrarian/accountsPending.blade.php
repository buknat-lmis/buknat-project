@extends('layouts.app')

@section('content')
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div class="card-body p-3">
            <div class="row gx-4">

                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            Pending Users
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            In this page you see all students who created account and is waiting for your validation
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative end-0">
                        <ul class="nav nav-pills nav-fill p-1">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center btn"
                                    href="{{ route('accountPending') }}">
                                    <i class="ni ni-circle-08"></i>
                                    <span class="ms-2">Pending</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center "
                                    href="{{ route('accountLists') }}">
                                    <i class="ni ni-lock-circle-open"></i>
                                    <span class="ms-2">All Lists</span>
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
                                <div class="card mb-4">
                                    <div class="card-header pb-0">
                                        <h6>Books table</h6>

                                    </div>
                                    <div class="card-body px-0 pt-0 pb-2">
                                        <div class="table-responsive p-0">

                                           @if (session('successApprove'))
                                                    <div class="alert-success alert-dismissible fade show" role="alert">

                                                        <p class="text-center text-white">
                                                            {{ session('successApprove') }}
                                                        </p>
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">x</button>
                                                      </div>
                                            @elseif (session('successDelete'))
                                                    <div class="alert-success alert-dismissible fade show" role="alert">

                                                        <p class="text-center text-white">
                                                            {{ session('successDelete') }}
                                                        </p>
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">x</button>
                                                  </div>
                                                  @else
                                            @endif

                                            <table class="table align-items-center mb-0" id="mytable">

                                                <thead>
                                                    <tr>
                                                        <th
                                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                            ID Picture</th>
                                                        <th
                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                            ID Number</th>
                                                        <th
                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                            Name</th>
                                                        <th
                                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                            Grade</th>

                                                        <th
                                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                            Account Status</th>
                                                        <th>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @foreach ($pendingStudents as $student)
                                                        <tr>
                                                            <td class="align-middle text-center">

                                                                <a target="_blank"
                                                                    href="{{ Storage::url('IDPic/' . $student->id_pic) }}">
                                                                    <img src="{{ Storage::url('IDPic/' . $student->id_pic) }}"
                                                                        alt="Image" width="100px" height="100px">
                                                                </a>
                                                                <input type="text" id="id_pic" name="id_pic"
                                                                    value="{{ $student->id_pic }}" hidden>

                                                            </td>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 id="id_number" class="mb-0 text-sm">
                                                                            {{ $student->id_number }}l
                                                                        </h6>
                                                                        <!-- <p id="id"
                                                                            class="text-xs text-secondary mb-0">
                                                                            {{ $student->id }}
                                                                        </p> -->
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p id="name" class="text-xs font-weight-bold mb-0">
                                                                    {{ $student->name }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <p id="grade" class="text-xs font-weight-bold mb-0">
                                                                    {{ $student->grade_and_section }}
                                                                </p>
                                                            </td>

                                                            <td class="align-middle text-center text-sm">
                                                                <span
                                                                    class="badge badge-sm bg-gradient-danger">inactive</span>

                                                            </td>
                                                            <td class="">

                                                                <a type="button" class="btn btn-success btn-sm"
                                                                    title="Approve Student?" href="#approve"
                                                                    data-bs-toggle="modal" onclick="approveStudent(this)"
                                                                    data-ID="{{ $student->id }}"
                                                                    data-idNumber="{{ $student->id_number }}"
                                                                    data-named="{{ $student->name }}"
                                                                    data-grades="{{ $student->grade_and_section }}">
                                                                    <i class="fas fa-edit"></i> Approve</a>
                                                                <a type="button" class="btn btn-sm"
                                                                    title="Delete this Student?" href="#delete"
                                                                    data-bs-toggle="modal" onclick="deleteStudent(this)"
                                                                    data-ID2="{{ $student->id }}"
                                                                    data-idFile="{{ $student->id_pic }}">
                                                                    <i class="fas fa-trash"></i> Delete</a>

                                                            </td>
                                                        </tr>

                                                        </form>
                                                    @endforeach

                                                </tbody>
                                            </table>
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

    <!-- Modal -->
    <div class="modal fade" id="approve" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Validation</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center">
                        Do you want to confirm this account?
                    </p>

                    <form method="POST" action="{{ route('confirmAccount') }}">
                        @csrf
                        @method('PUT')
                        <input type="text" id="ids" name="id_account" hidden>

                        <label for="named">Name:</label>
                        <input type="text" id="named" name="name" class="form-control" required>
                        <label for="name">ID Number:</label>
                        <input type="number" name="id_number" class="form-control" id="idNumber" required>
                        <div class="row">
                            <div class="col">
                                <label for="grades" class="text-warning">Grade Level Selected</label>
                                <input type="text" class="form-control" name="" id="grades">
                            </div>
                            <div class="col">
                                <label for="grade">Kindly Re- Select</label>
                                <select id="grade" name="grade_and_section" class="form-control">
                                    <option value="Grade-7"> Grade 7</option>
                                    <option value="Grade-8"> Grade 8</option>
                                    <option value="Grade-9"> Grade 9</option>
                                    <option value="Grade-10"> Grade 10</option>
                                    <option value="Grade-11"> Grade 11</option>
                                    <option value="Grade-12"> Grade 12</option>
                                </select>
                                <input type="number" value="1" hidden>
                            </div>
                        </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success ">Confirm</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Student -->
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete this Account?</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center">
                        Do you want to Proceed in deleting this account?
                    </p>

                    <form method="POST" action="{{ route('deleteAccount') }}" >
                    @csrf
                    @method('DELETE')
                    <input type="text" id="ids2" name="id_account" hidden>
                    <input type="text" id="idFile" name="idFile" hidden>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning ">Proceed</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        function approveStudent(that) {
            ids = $(that).attr('data-ID');
            idNumber = $(that).attr('data-idNumber');
            named = $(that).attr('data-named');
            grades = $(that).attr('data-grades');

            $('#ids').val(ids)
            $('#idNumber').val(idNumber)
            $('#named').val(named)
            $('#grades').val(grades)
        }

        function deleteStudent(that) {
            ids2 = $(that).attr('data-ID2');
            idFile = $(that).attr('data-idFile');

            $('#ids2').val(ids2)
            $('#idFile').val(idFile)

        }
    </script>
@endsection
