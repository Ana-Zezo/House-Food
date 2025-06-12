@extends('theem.layouts.app')

@section('content')
    <div class="pagetitle">
        <h1 style="margin-top: -25px">Category Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">Category</li>
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
                            <img src="{{ $category->image && file_exists(public_path('storage/' . $category->image))
                                ? asset('storage/' . $category->image)
                                : asset('assets/img/default-category.png') }}"
                                class="img-fluid" style="max-height: 280px; object-fit: contain;"
                                alt="{{ $category->name }}">
                        </div>

                        <div class="col-md-8">
                            <div class="card-body">
                                <h4 class="card-title mb-3">{{ $category->name }}</h4>


                                <ul class="list-group list-group-flush">

                                    <li class="list-group-item">
                                        <strong>Status:</strong>
                                        <span class="badge bg-{{ $category->status ? 'success' : 'danger' }}">
                                            {{ $category->status ? 'Active' : 'Inactive' }}
                                        </span>

                                    </li>

                                    <li class="list-group-item">
                                        <strong>Created At:</strong> {{ $category->created_at->format('F j, Y') }}
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="text-center mt-4">
                    <a href="{{ route('dashboard.categories.index') }}"
                        class="btn btn-secondary px-4 d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-arrow-left animated-icon"></i>
                        Back to Categories
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
