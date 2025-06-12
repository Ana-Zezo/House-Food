@extends('theem.layouts.app')


@section('content')
    <div class="pagetitle">
        <h1 style="margin-top: -25px">Chefs List</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">Chefs</li>
                <li class="breadcrumb-item active">List</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">





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
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($chefs as $chef)
                                        <tr>
                                            <th scope="row">{{ $chef->id }}</th>
                                            <td>{{ $chef->name }}</td>
                                            <td>{{ $chef->email }}</td>
                                            <td>{{ $chef->phone }}</td>
                                            <td>
                                                <a href="{{ route('dashboard.chef.show', $chef->id) }}"
                                                    class="btn custom-show-btn">
                                                    <i class="fa-solid fa-eye me-1"></i> Show
                                                </a>
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
                            {{ $chefs->links('pagination::bootstrap-5') }}


                        </div>


                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
