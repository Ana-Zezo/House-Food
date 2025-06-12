@extends('theem.layouts.app')

@section('title', 'Notification Details')

@section('content')

    <div class="pagetitle">
        <h1 style="margin-top: -25px">Notification Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">Notifications</li>
                <li class="breadcrumb-item active">Details</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">

        @if (session('success'))
            <div id="sessionAlert" class="alert alert-success alert-dismissible fade show custom-alert" role="alert"
                style="margin:1rem auto;">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $notification->title }}</h5>
                <p>{{ $notification->description }}</p>
                <p>
                    <strong>Status: </strong>
                    @if ($notification->is_read)
                        <span class="badge bg-success">Read</span>
                    @else
                        <span class="badge bg-warning">Unread</span>
                    @endif
                </p>
                <p><strong>Created at: </strong> {{ $notification->created_at->format('d M Y, h:i A') }}</p>



                <div class="animated-buttons mt-4" style="max-width: 170px;">
                    @if (!$notification->is_read)
                        <form method="POST" action="{{ route('admin.notifications.read', $notification->id) }}">
                            @csrf
                            <button type="submit" class="modern-btn btn-gradient-success w-100">
                                <i class="bi bi-check-circle btn-icon"></i>
                                Mark as Read
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.notifications.index') }}" class="modern-btn btn-gradient-secondary w-100">
                        <i class="bi bi-arrow-left btn-icon"></i>
                        Back to List
                    </a>
                </div>

            </div>
        </div>
    </section>
@endsection
