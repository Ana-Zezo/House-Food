@extends('theem.layouts.app')

@section('content')
    <div class="pagetitle">
        <h1 style="margin-top: -25px">Chefs Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">Chefs</li>
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
                                <img src="{{ $chef->image ? asset('uploads/chefs/' . $chef->image) : asset('assets/img/default-chef.png') }}"
                                    class="profile-image" alt="{{ $chef->name }}">
                            </div>
                        </div>


                        <div class="col-md-8">
                            <div class="card-body">
                                <h4 class="card-title mb-3">{{ $chef->name }}</h4>

                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <strong>Email:</strong> {{ $chef->email }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Phone:</strong> {{ $chef->phone }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Channel Name:</strong> {{ $chef->channel_name ?? 'N/A' }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Subscribers:</strong> {{ $chef->countSubscribe }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Total Orders:</strong> {{ $chef->totalOrder }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Wallet:</strong> ${{ number_format($chef->wallet, 2) }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Verified:</strong>
                                        @if ($chef->is_verify)
                                            <i class="fa-solid fa-circle-check text-success"></i>
                                        @else
                                            <i class="fa-solid fa-circle-xmark text-danger"></i>
                                        @endif
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Joined At:</strong> {{ $chef->created_at->format('F j, Y') }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($chef->bio)
                    <div class="card mt-4 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Bio</h5>
                            <p class="card-text">{{ $chef->bio }}</p>
                        </div>
                    </div>
                @endif

                <div class="text-center mt-4">
                    <a href="{{ route('dashboard.chefs.index') }}"
                        class="btn btn-secondary px-4 d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-arrow-left animated-icon"></i>
                        Back to Chefs
                    </a>
                </div>

            </div>
        </div>
    </section>
@endsection
