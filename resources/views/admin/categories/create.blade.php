@extends('theem.layouts.app')

@section('content')
    <div class="pagetitle">
        <h1 style="margin-top: -25px">Create Category</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">Categories</li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 animate__animated animate__fadeInUp">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-4">Create a New Category</h5>

                        <form class="row g-3" action="{{ route('dashboard.categories.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Category Name -->
                            <div class="col-12">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Category Image -->
                            <div class="col-12">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" name="image" id="image"
                                    class="form-control @error('image') is-invalid @enderror">
                                @error('image')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Submit & Cancel Buttons -->
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-success custom-btn animate-pulse px-4">
                                    <i class="fa-solid fa-plus me-1"></i> Create
                                </button>
                                <a href="{{ route('dashboard.categories.index') }}"
                                    class="btn btn-outline-secondary custom-btn-outline animate-pulse px-4 ms-2">
                                    <i class="fa-solid fa-arrow-left me-1"></i> Cancel
                                </a>
                            </div>


                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
