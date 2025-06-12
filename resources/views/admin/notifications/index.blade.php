@extends('theem.layouts.app')


@section('content')
    <div class="pagetitle">
        <h1 style="margin-top: -25px">Notifications List</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">Notifications</li>
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
                                        <th scope="col">Title</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($notifications as $notification)
                                        <tr @if (!$notification->is_read) class="fw-bold" @endif>
                                            <td>{{ $notification->id }}</td>
                                            <td>{{ $notification->title }}</td>
                                            <td>
                                                @if ($notification->is_read)
                                                    <span class="badge text-center text-success">
                                                        <i class="fa-solid fa-circle-check" title="Read"
                                                            style="font-size: 18px"></i>
                                                    </span>
                                                @else
                                                    <span class="badge text-warning text-center">
                                                        <i class="fa-solid fa-bell" title="Unread"
                                                            style="font-size: 18px"></i>
                                                    </span>
                                                @endif
                                            </td>


                                            <td>
                                                <a href="{{ route('admin.notifications.show', $notification->id) }}"
                                                    class="btn custom-action-btn custom-show-btn">
                                                    <i class="fa-solid fa-eye me-1"></i> Show
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center m-5"> No Data Yet ! </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            {{ $notifications->links('pagination::bootstrap-5') }}


                        </div>


                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
