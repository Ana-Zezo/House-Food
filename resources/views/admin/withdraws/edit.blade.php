@extends('theem.layouts.app')

@section('content')
    <div class="pagetitle">
        <h1 style="margin-top: -25px">Update Withdraw Status</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">Withdraws</li>
                <li class="breadcrumb-item active">Update Status</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-4">Edit Withdraw Status</h5>

                        <form class="row g-3" action="{{ route('dashboard.withdraws.update', $withdraw->id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')

                            <div class="col-12">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="pending" {{ $withdraw->status == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="approved" {{ $withdraw->status == 'approved' ? 'selected' : '' }}>
                                        Approved</option>
                                    <option value="rejected" {{ $withdraw->status == 'rejected' ? 'selected' : '' }}>
                                        Rejected</option>
                                </select>
                            </div>


                            <div class="text-center">
                                <button type="submit" class="btn custom-action-btn custom-edit-btn me-2">
                                    <i class="fa-solid fa-arrow-rotate-right me-1"></i> Update
                                </button>
                                <a href="{{ route('dashboard.withdraws.index') }}"
                                    class="btn custom-action-btn custom-cancel-btn">
                                    <i class="fa-solid fa-times me-1"></i> Cancel
                                </a>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
