@extends('dashboard.layout.adminmain')
@section('content')
<div class="container-fluid">
    <!-- start page title -->
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">Application for Privilege Card</h4>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">president</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('admin-members-store') }}" method="POST">
                        @csrf

                        <div class="row">

                            <div class="mb-2 col-md-4">
                                <label class="form-label">Form No</label>
                                <input type="text" name="form_no" class="form-control">
                            </div>

                            <div class="mb-2 col-md-4">
                                <label class="form-label">Card No</label>
                                <input type="text" name="card_no" class="form-control">
                            </div>

                            <div class="mb-2 col-md-4">
                                <label class="form-label">Date (AD)</label>
                                <input class="form-control" type="date" name="date">
                            </div>

                            <div class="mb-2 col-md-4">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control">
                            </div>

                            <div class="mb-2 col-md-4">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="">Choose</option>
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                </select>
                            </div>

                            <div class="mb-2 col-md-4">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Email">
                            </div>

                            <div class="mb-2 col-md-4">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="address" placeholder="1234 Main St">
                            </div>

                            <div class="mb-2 col-md-4">
                                <label class="form-label">Date of Birth (AD)</label>
                                <input class="form-control" type="date" name="dob">
                            </div>

                            <div class="mb-2 col-md-4">
                                <label class="form-label">Mobile No</label>
                                <input type="text" name="phone" class="form-control">
                            </div>

                            <!-- ⭐ NEW FIELD: ERP SYSTEM_PK -->
                            <div class="mb-2 col-md-6">
                                <label class="form-label">ERP Customer Name (system_pk / Name_Cust)</label>
                                <input type="text" name="system_pk" class="form-control"
                                       placeholder="Exact Name_Cust from ERP">
                            </div>

                            <div class="mb-2 col-md-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>

                            <div class="mb-2 col-md-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                            </div>

                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alertMessages = document.querySelectorAll('.alert');
        alertMessages.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });
    });
</script>
@endsection
