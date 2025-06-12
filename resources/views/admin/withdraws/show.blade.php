@extends('theem.layouts.app')

@section('content')
    <div class="pagetitle">
        <h1 style="margin-top: -25px">Withdraw Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">Withdraws</li>
                <li class="breadcrumb-item active">Details</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card shadow-lg border-0">
                    <div class="row g-0">

                        <div class="col-md-4 profile-image-container">
                            <div class="profile-image-wrapper">
                                <img src="{{ $withdraw->chef->image ? asset('uploads/chefs/' . $withdraw->chef->image) : asset('assets/img/default-chef.png') }}"
                                    class="profile-image" alt="{{ $withdraw->chef->name ?? 'Chef' }}">
                            </div>
                        </div>


                        <div class="col-md-8">
                            <div class="card-body">
                                <h4 class="card-title mb-3">{{ $withdraw->chef->name ?? 'N/A' }}</h4>

                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <strong>Email:</strong> {{ $withdraw->chef->email ?? 'N/A' }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Phone:</strong> {{ $withdraw->chef->phone ?? 'N/A' }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Channel Name:</strong> {{ $withdraw->chef->channel_name ?? 'N/A' }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Subscribers:</strong> {{ $withdraw->chef->countSubscribe ?? 0 }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Wallet:</strong> ${{ number_format($withdraw->chef->wallet ?? 0, 2) }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Amount:</strong> {{ number_format($withdraw->amount, 2) }} $
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Status:</strong>
                                        <span
                                            class="badge bg-{{ $withdraw->status == 'approved' ? 'success' : ($withdraw->status == 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($withdraw->status) }}
                                        </span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Created At:</strong> {{ $withdraw->created_at->format('Y-m-d H:i A') }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('dashboard.withdraws.index') }}"
                        class="btn btn-secondary px-4 d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-arrow-left animated-icon"></i>
                        Back to Withdraws
                    </a>
                </div>

            </div>
        </div>
    </section>
@endsection
