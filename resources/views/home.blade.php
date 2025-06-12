@extends('theem.layouts.app')

@section('content')
    <div class="pagetitle">
        <h1 style="margin-top: -25px">Admin Panel</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Overview</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

            {{-- Users Count --}}
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card info-card animated-card animated-delay-1">
                    <div class="card-body">
                        <h5 class="card-title">Users</h5>
                        <div class="d-flex align-items-center">
                            <div
                                class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $usersCount }}</h6>
                                <span class="text-muted small">Total users</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Chefs Count --}}
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card info-card animated-card animated-delay-2">
                    <div class="card-body">
                        <h5 class="card-title">Chefs</h5>
                        <div class="d-flex align-items-center">
                            <div
                                class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $chefsCount }}</h6>
                                <span class="text-muted small">Total chefs</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Orders Count --}}
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card info-card animated-card animated-delay-3">
                    <div class="card-body">
                        <h5 class="card-title">Orders</h5>
                        <div class="d-flex align-items-center">
                            <div
                                class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning text-white">
                                <i class="bi bi-bag-check"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $ordersCount }}</h6>
                                <span class="text-muted small">Total orders</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Foods Count --}}
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card info-card animated-card animated-delay-4">
                    <div class="card-body">
                        <h5 class="card-title">Foods</h5>
                        <div class="d-flex align-items-center">
                            <div
                                class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-danger text-white">
                                <i class="bi bi-egg-fried"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $foodsCount }}</h6>
                                <span class="text-muted small">Total foods</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Categories Count --}}
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card info-card animated-card animated-delay-5">
                    <div class="card-body">
                        <h5 class="card-title">Categories</h5>
                        <div class="d-flex align-items-center">
                            <div
                                class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info text-white">
                                <i class="bi bi-tags"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $categoriesCount }}</h6>
                                <span class="text-muted small">Total categories</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Amount --}}
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card info-card animated-card animated-delay-6">
                    <div class="card-body">
                        <h5 class="card-title">Total Revenue</h5>
                        <div class="d-flex align-items-center">
                            <div
                                class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-secondary text-white">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ number_format($totalMount, 2) }} EGP</h6>
                                <span class="text-muted small">Sum of approved amounts</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Withdraw Requests - Centered --}}
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card info-card animated-card animated-delay-7">
                    <div class="card-body">
                        <h5 class="card-title">Withdraw Requests</h5>
                        <div class="d-flex align-items-center">
                            <div
                                class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-dark text-white">
                                <i class="bi bi-wallet2"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $withdrawCount }}</h6>
                                <span class="text-muted small">Total withdraw requests</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
