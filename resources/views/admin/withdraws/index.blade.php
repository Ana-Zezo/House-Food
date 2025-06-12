@extends('theem.layouts.app')

@section('content')
    <div class="pagetitle">
        <h1 style="margin-top: -25px">WithDraws List</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">WithDraws</li>
                <li class="breadcrumb-item active">List</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                @if (session('success'))
                    <div id="sessionAlert" class="alert alert-success alert-dismissible fade show custom-alert"
                        role="alert" style="margin:1rem auto;">
                        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body mt-3">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th>#</th>
                                        <th>Chef Name</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th colspan="2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($withdraws as $withdraw)
                                        <tr>
                                            <td>{{ $withdraw->id }}</td>
                                            <td>{{ $withdraw->chef?->name ?? 'N/A' }}</td>
                                            <td>{{ number_format($withdraw->amount, 2) }} $</td>
                                            <td>
                                                @php
                                                    $statusColor = [
                                                        'pending' => 'warning',
                                                        'approved' => 'success',
                                                        'rejected' => 'danger',
                                                    ];
                                                @endphp
                                                <span
                                                    class="badge bg-{{ $statusColor[$withdraw->status] ?? 'secondary' }} status-badge">
                                                    {{ ucfirst($withdraw->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $withdraw->created_at?->format('Y-m-d') }}</td>

                                            <td>
                                                <a href="{{ route('dashboard.withdraws.show', $withdraw->id) }}"
                                                    class="btn custom-action-btn custom-show-btn">
                                                    <i class="fa-solid fa-eye me-1"></i> Show
                                                </a>
                                            </td>

                                            <td>
                                                <a href="{{ route('dashboard.withdraws.edit', $withdraw->id) }}"
                                                    class="btn custom-action-btn custom-edit-btn">
                                                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">No withdraw requests yet!</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            {{ $withdraws->links('pagination::bootstrap-5') }}


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
