@extends('theem.layouts.app')


@section('content')
    <div class="pagetitle">
        <h1 style="margin-top: -25px">Categories List</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">Categories</li>
                <li class="breadcrumb-item active">List</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <!-- Toast category -->
                <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100; margin-top: 60px;">
                    <div id="statusToastCategory" class="toast align-items-center text-bg-success border-0" role="alert"
                        aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
                        <div class="d-flex">
                            <div class="toast-body" id="toastMessageCategory">
                                Status updated successfully!
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



                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Categories</h5>
                    <a href="{{ route('dashboard.categories.create') }}" class="btn-fancy-create">
                        <span class="btn-fancy-bg"></span>
                        <i class="fa-solid fa-plus"></i> New Category
                    </a>
                </div>
                <div class="card">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Status</th>
                                        <th colspan="3" scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($categories as $category)
                                        <tr id="category-row-{{ $category->id }}">
                                            <th scope="row">{{ $category->id }}</th>
                                            <td>{{ $category->name }}</td>
                                            <td>
                                                @php
                                                    $imagePath = public_path('storage/' . $category->image);
                                                @endphp

                                                @if ($category->image && file_exists($imagePath))
                                                    <img src="{{ asset('storage/' . $category->image) }}"
                                                        alt="{{ $category->name }}"
                                                        style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                @else
                                                    <img src="{{ asset('assets/img/default-category.png') }}"
                                                        alt="Default Category"
                                                        style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                @endif

                                            </td>

                                            <td>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox"
                                                        class="form-check-input toggle-switch-status-category"
                                                        role="switch" data-id="{{ $category->id }}"
                                                        {{ $category->status ? 'checked' : '' }}>
                                                </div>
                                            </td>


                                            <td>
                                                <a href="{{ route('dashboard.categories.show', $category->id) }}"
                                                    class="btn custom-action-btn custom-show-btn">
                                                    <i class="fa-solid fa-eye me-1"></i> Show
                                                </a>
                                            </td>

                                            <td>
                                                <a href="{{ route('dashboard.categories.edit', $category->id) }}"
                                                    class="btn custom-action-btn custom-edit-btn">
                                                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                                </a>
                                            </td>

                                            <td>
                                                <button type="button"
                                                    class="btn custom-action-btn custom-delete-btn delete-btn"
                                                    data-model="categories" data-id="{{ $category->id }}"
                                                    data-row-id="category-row-{{ $category->id }}">
                                                    <i class="fa-solid fa-trash me-1"></i>
                                                    Delete
                                                </button>
                                            </td>


                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center m-5"> No Data Yet ! </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            {{ $categories->links('pagination::bootstrap-5') }}


                        </div>


                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection
