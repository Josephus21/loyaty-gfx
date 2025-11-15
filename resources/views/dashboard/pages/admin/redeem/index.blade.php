@extends('dashboard.layout.main')

@section('content')
<div class="container mt-4">
    <h2>Redeemed Rewards</h2>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Member</th>
                <th>Reward</th>
                <th>QR Code</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($redemptions as $r)
            <tr>
                <td>{{ $r->member->name }}</td>
                <td>{{ $r->reward->name }}</td>
                <td>{{ $r->code }}</td>

                <td>
                    @if($r->status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @else
                        <span class="badge bg-success">Redeemed</span>
                    @endif
                </td>

                <td>
                    @if($r->status == 'pending')
                    <form action="{{ route('admin-redeem-update', $r->id) }}" method="POST">
    @csrf
    <button class="btn  btn-success btn-sm">Mark as Redeemed</button>
</form>

                    @else
                    <button class="btn btn-secondary btn-sm" disabled>Done</button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
