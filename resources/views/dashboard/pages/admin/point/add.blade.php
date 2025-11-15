@extends('dashboard.layout.adminmain')

@section('content')

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <!-- Search Form -->
                    <form id="search-form">
                        <div class="row">
                            <div class="col-md-9 mb-5">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Enter card no / phone" id="search-query" aria-label="Search query">
                                    <button class="btn btn-dark waves-effect waves-light" type="submit" id="search-button">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Member Details -->
                    <div id="member-details">

                        <!-- Dynamic member details will be inserted here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- content -->

<!-- Custom Styles -->
<style>
    .knob-input {
        width: 64px;
        height: 40px;
        position: absolute;
        vertical-align: middle;
        margin-top: 40px;
        margin-left: -92px;
        border: 0;
        background: none;
        font: bold 24px Arial;
        text-align: center;
        color: #31cb72;
        padding: 0;
        appearance: none;
    }
</style>




<script>
    document.getElementById('search-form').addEventListener('submit', function(e) {
        e.preventDefault()
        const searchQuery = document.getElementById('search-query').value;

        if (searchQuery) {
            axios.get('/add-admin-point', {
                    params: {
                        search: searchQuery
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    console.log(response.data);
                    const member = response.data;
                    const memberDetailsDiv = document.getElementById('member-details');

                    memberDetailsDiv.innerHTML = `
                    <form id="update-form" >
                        <input type="hidden" id="user-id" value="{{ auth()->user()->id }}">
                        <input type="hidden" id="member-id" value="${member.id}">
                        <div class="row">
                            <!-- Member Input Fields -->
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="mb-2 col-md-4">
                                        <label for="billno" class="form-label">Bill No</label>
                                        <input type="text" class="form-control" id="billno">
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label for="billamount" class="form-label">Bill Amount</label>
                                        <input type="text" class="form-control" id="billamount">
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label for="point" class="form-label">Points</label>
                                        <input type="text" class="form-control" disabled id="point">
                                    </div>
                                    <!-- Other Member Details -->
                                    <div class="mb-2 col-md-4">
                                        <label for="formno" class="form-label">Form No</label>
                                        <input type="text" class="form-control" value="${member.form_no}" disabled id="formno">
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label for="fullname" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" value="${member.name}" disabled id="fullname">
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label for="inputState" class="form-label">Gender</label>
                                        <select id="inputState" disabled class="form-select">
                                            <option ${member.gender === 'M' ? 'selected' : ''}>Male</option>
                                            <option ${member.gender === 'F' ? 'selected' : ''}>Female</option>
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label for="inputEmail4" class="form-label">Email</label>
                                        <input type="email" class="form-control" disabled id="inputEmail4" value="${member.email}" placeholder="Email">
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label for="inputAddress" class="form-label">Address</label>
                                        <input type="text" class="form-control" disabled id="inputAddress" value="${member.address}" placeholder="1234 Main St">
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label for="example-date" class="form-label">Date of Birth (AD)</label>
                                        <input class="form-control" value="${member.dob}" type="date" name="date" disabled id="example-date">
                                    </div>
                                    <div class="mb-2 col-md-4">
                                        <label for="mobileno" class="form-label">Mobile NO </label>
                                        <input type="text" class="form-control" value="${member.phone}" disabled id="mobileno">
                                    </div>
                                </div>
                            </div>

                            <!-- Knob for Points -->
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <div style="display:inline;width:120px;height:120px;">
                                           <input class="knob-input" data-plugin="knob" data-width="120" data-height="120" data-linecap="round" 
                                            data-fgcolor="#31cb72" value="${member.total_points}" data-skin="tron" data-angleoffset="180" 
                                            data-readonly="true" data-thickness=".1" id="knob-point" data-min="0" data-max="1e+308" data-step="0.00001">

                                        </div>
                                    </div>
                                    <h4 class="card-title text-center">Gain Points</h4>
                                     <h4 class="card-title text-center" style="color: #38a169;">
                                    ${member.name}
                                    </h4>
                                     <h5 class="card-title text-center" style="color: #38a169;">
                                    ${member.card_no}
                                    </h5>
                                    <h6 class="card-title text-center" style="color: #38a169;">
                                    ${member.branch}
                                    </h6>
                                </div>
                            </div>

                            <!-- Submit and Cancel Buttons -->
                            <div class="col-md-12 text-center mt-4">
                                <button id="submit-point" class="btn btn-primary">Save Point</button>
                                <button type="button" class="btn btn-secondary">Cancel</button>
                            </div>
                        </div>
                    </form>
                `;
                    $(".knob-input").knob();

                })
                .catch(error => {
                    const memberDetailsDiv = document.getElementById('member-details');
                    memberDetailsDiv.innerHTML = `<p>Members Not Found!</p>`;
                });
        } else {
            alert('Please enter a card number, or phone to search.');
        }
    });

    // Calculate points based on bill amount
document.addEventListener('input', function(e) {
    if (e.target.id === 'billamount') {
        const billAmount = parseFloat(e.target.value) || 0;

        // ⭐ New rule: 10,000 pesos = 1 point
        const points = (billAmount / 10000).toFixed(5);

        document.getElementById('point').value = points;
    }
});


    // Prevent form submission on Enter key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.target.closest('#update-form')) {
            e.preventDefault();
        }
    });



    $(document).ready(function() {
        // Retrieve CSRF token
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Set CSRF token in headers
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        // Event delegation for dynamically generated elements
        $(document).on('submit', '#update-form', function(e) {
            e.preventDefault();
            // Debugging
            const billNo = $('#billno').val();
            const billAmount = $('#billamount').val();
            const points = $('#point').val();
            const memberId = $('#member-id').val();
            const userId = $('#user-id').val();

            // Debugging values
            console.log({
                bill_no: billNo,
                bill_amount: billAmount,
                points: points,
                member_id: memberId,
                user_id: userId,
            });

            // Send POST request using jQuery
            $.post("{{ route('admin-point-store') }}", {
                    bill_no: billNo,
                    bill_amount: billAmount,
                    points: points,
                    member_id: memberId,
                    user_id: userId
                })
                .done(function(response) {
                    console.log('Response:', response); // Log success response
                    alert('Point saved successfully!');
                    $('#search-form')[0].reset();
                    $('#update-form')[0].reset();


                    // Optionally clear dynamic content if you want
                    $('#member-details').html('');
                })

                .fail(function(xhr) {
                    if (xhr.status === 422) {
                        console.log('Validation errors:', xhr.responseJSON.errors); // Log validation errors
                        alert('Validation failed. Check your input.');
                    } else {
                        console.log('Error:', xhr); // Log other errors
                        alert('An error occurred. Please try again.');
                    }
                });
        });
    });
</script>

@endsection