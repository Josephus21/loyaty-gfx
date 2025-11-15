@extends('dashboard.layout.main')

@section('content')
<div class="col-md-6 offset-md-3 mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h4>Member Registration</h4>
        </div>

        <div class="card-body">

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('member.register') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Name</label>
                    <input name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input name="email" type="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Branch</label>
                    <input name="branch" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input name="password" type="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Confirm Password</label>
                    <input name="password_confirmation" type="password" class="form-control" required>
                </div>

                <!-- ⭐ NEW FIELD: ERP system_pk (Name_Cust) -->
                <div class="mb-3">
                    <label>ERP Customer Name (system_pk / Name_Cust)</label>
                    <input name="system_pk" class="form-control" 
                           placeholder="Enter exact Name_Cust from ERP">
                </div>

                <button class="btn btn-primary w-100">Register</button>
            </form>
        </div>
    </div>
</div>
@endsection
