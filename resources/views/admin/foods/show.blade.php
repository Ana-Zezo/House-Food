@extends('theem.layouts.app')

@section('content')
    <div class="pagetitle">
        <h1 style="margin-top: -25px">Food Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">Food</li>
                <li class="breadcrumb-item active">Details</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="row g-0">

                        <div class="col-md-4 d-flex justify-content-center align-items-center"
                            style="background-color: #f8f9fa; ">
                            <img src="{{ $food->image && file_exists(public_path('uploads/foods/' . $food->image))
                                ? asset('uploads/foods/' . $food->image)
                                : asset('assets/img/default-food.png') }}"
                                class="img-fluid" style="max-height: 280px; object-fit: contain;" alt="{{ $food->name }}">
                        </div>

                        <div class="col-md-8">
                            <div class="card-body">
                                <h4 class="card-title mb-3">{{ $food->name }}</h4>


                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <strong>Price:</strong> ${{ number_format($food->price, 2) }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Offer Price:</strong>
                                        {{ $food->offer_price ? '$' . number_format($food->offer_price, 2) : '-' }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Status:</strong>
                                        <span class="badge bg-{{ $food->status == 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($food->status) }}
                                        </span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Food Type:</strong> {{ ucfirst($food->food_type) }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Preparation Time:</strong> {{ $food->preparation_time }} minutes
                                    </li>

                                    <li class="list-group-item">
                                        <strong>Rating:</strong>
                                        <div class="star-rating-display" title="{{ $food->rating }}/5">
                                            @php
                                                $fullStars = floor($food->rating);
                                                $halfStar = $food->rating - $fullStars >= 0.5;
                                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                            @endphp

                                            @for ($i = 0; $i < $fullStars; $i++)
                                                <i class="fa fa-star"></i>
                                            @endfor

                                            @if ($halfStar)
                                                <i class="fa fa-star-half-alt"></i>
                                            @endif

                                            @for ($i = 0; $i < $emptyStars; $i++)
                                                <i class="fa fa-star far"></i>
                                            @endfor
                                        </div>
                                    </li>


                                    <li class="list-group-item">
                                        <strong>Category:</strong> {{ $food->category->name ?? 'No category' }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Chef:</strong> {{ $food->chef->name ?? 'No chef assigned' }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($food->description)
                    <div class="card mt-4 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Description</h5>
                            <p class="card-text">{{ $food->description }}</p>
                        </div>
                    </div>
                @endif

                <div class="text-center mt-4">
                    <a href="{{ route('dashboard.foods.index') }}"
                        class="btn btn-secondary px-4 d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-arrow-left animated-icon"></i>
                        Back to Foods
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
