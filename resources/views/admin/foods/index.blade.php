@extends('theem.layouts.app')


@section('content')
    <div class="pagetitle">
        <h1 style="margin-top: -25px">Foods List</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">Foods</li>
                <li class="breadcrumb-item active">List</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <!-- Toast Food -->
                <div class="position-fixed top-0 end-0 p-3" style="margin-top: 55px">
                    <div id="statusToastFood" class="toast align-items-center text-bg-success border-0" role="alert"
                        aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
                        <div class="d-flex">
                            <div class="toast-body" id="toastMessageFood">
                                Food status updated!
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                        </div>
                    </div>
                </div>


                @if (session('success'))
                    <div id="sessionAlert" class="alert alert-success alert-dismissible fade show custom-alert"
                        role="alert" style="margin:1rem auto;">
                        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif


                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Chef Name</th>
                                        <th scope="col">Status</th>
                                        <th colspan="2" scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($foods as $food)
                                        <tr id="food-row-{{ $food->id }}">
                                            <th scope="row">{{ $food->id }}</th>
                                            <td>{{ $food->name }}</td>
                                            <td>
                                                @php

                                                    $imagePath = public_path('uploads/foods/' . $food->image);
                                                @endphp

                                                @if ($food->image && file_exists($imagePath))
                                                    <img src="{{ asset('uploads/foods/' . $food->image) }}"
                                                        alt="{{ $food->name }}"
                                                        style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                @else
                                                    <img src="{{ asset('assets/img/default-food.png') }}" alt="Default Food"
                                                        style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                @endif
                                            </td>
                                            <td>${{ number_format($food->price, 2) }}</td>
                                            <td>{{ $food->chef ? $food->chef->name : 'No Chef' }}</td>

                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input toggle-switch-status-food"
                                                        type="checkbox" role="switch" data-id="{{ $food->id }}"
                                                        {{ $food->status === 'active' ? 'checked' : '' }}>
                                                </div>
                                            </td>


                                            <td>
                                                <a href="{{ route('dashboard.food.show', $food->id) }}"
                                                    class="btn custom-action-btn custom-show-btn">
                                                    <i class="fa-solid fa-eye me-1"></i> Show
                                                </a>
                                            </td>

                                            <td>
                                                <button type="button"
                                                    class="btn custom-action-btn custom-delete-btn delete-btn"
                                                    data-model="food" data-id="{{ $food->id }}"
                                                    data-row-id="food-row-{{ $food->id }}">
                                                    <i class="fa-solid fa-trash me-1"></i>
                                                    Delete
                                                </button>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center m-5"> No Data Yet ! </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            {{ $foods->links('pagination::bootstrap-5') }}


                        </div>


                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
