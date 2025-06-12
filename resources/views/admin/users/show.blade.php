@extends('theem.layouts.app')


@section('content')
    <div class="pagetitle">
        <h1 style="margin-top: -25px">User Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">Users</li>
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
                                <img src="{{ $user->image ? asset('uploads/users/' . $user->image) : asset('assets/img/default-user.png') }}"
                                    class="profile-image" alt="{{ $user->name }}">
                            </div>
                        </div>


                        <div class="col-md-8">
                            <div class="card-body">
                                <h4 class="card-title mb-3">{{ $user->name }}</h4>

                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <strong>Email:</strong> {{ $user->email }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Phone:</strong> {{ $user->phone }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Status:</strong>
                                        <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Wallet:</strong> ${{ number_format($user->wallet, 2) }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Verified:</strong>
                                        @if ($user->is_verify)
                                            <i class="fa-solid fa-circle-check text-success"></i>
                                        @else
                                            <i class="fa-solid fa-circle-xmark text-danger"></i>
                                        @endif
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Joined At:</strong> {{ $user->created_at->format('F j, Y') }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('dashboard.users.index') }}"
                        class="btn btn-secondary px-4 d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-arrow-left animated-icon"></i>
                        Back to Users
                    </a>
                </div>


            </div>
        </div>
    </section>
@endsection
