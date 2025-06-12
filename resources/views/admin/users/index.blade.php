@extends('theem.layouts.app')

@section('content')
    <div class="pagetitle">
        <h1 style="margin-top: -25px">Users List</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active">List</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <!-- Toast User -->
                <div class="position-fixed top-0 end-0 p-3" style="margin-top: 55px">
                    <div id="statusToastUser" class="toast align-items-center text-bg-success border-0" role="alert"
                        aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
                        <div class="d-flex">
                            <div class="toast-body" id="toastMessageUser">
                                Status updated successfully!
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                        </div>
                    </div>
                </div>




                <div class="card">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr>
                                            <th scope="row">{{ $user->id }}</th>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone }}</td>

                                            <td>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox"
                                                        class="form-check-input toggle-switch-status-user"
                                                        data-id="{{ $user->id }}"
                                                        {{ $user->status === 'active' ? 'checked' : '' }}>
                                                </div>
                                            </td>

                                            <td>
                                                <a href="{{ route('dashboard.user.show', $user->id) }}"
                                                    class="btn custom-show-btn">
                                                    <i class="fa-solid fa-eye me-1"></i> Show
                                                </a>
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
                            {{ $users->links('pagination::bootstrap-5') }}

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
