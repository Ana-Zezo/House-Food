@extends('theem.layouts.app')


@section('content')
    <div class="pagetitle">
        <h1 style="margin-top: -25px">Update Categories</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">Categories</li>
                <li class="breadcrumb-item active">Update</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-4">Edit Category</h5>



                        <form class="row g-3" action="{{ route('dashboard.categories.update', $category->id) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="col-12">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" id="name"
                                    value="{{ old('name', $category->name) }}" class="form-control" required>
                            </div>

                            <div class="col-12">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" name="image" id="image" class="form-control">

                                @if ($category->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="Current Image"
                                            width="100" height="70" style="object-fit:cover;">
                                    </div>
                                @endif
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn custom-action-btn custom-edit-btn me-2">
                                    <i class="fa-solid fa-arrow-rotate-right me-1"></i> Update
                                </button>
                                <a href="{{ route('dashboard.categories.index') }}"
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
