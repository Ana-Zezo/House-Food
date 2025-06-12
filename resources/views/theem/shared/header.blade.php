<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">

            <a href="{{ route('home') }}" class="logo d-flex align-items-center gap-2 text-decoration-none custom-logo">
                <img src="{{ asset('assets/img/Logo2.png') }}" alt="Logo" class="rounded-circle shadow-sm"
                    style="width: 50px; height: 50px; object-fit: cover;">
                <span class="d-none d-lg-block logo-text animate-logo shine-text">
                    <span class="text-food">FOOD</span><span class="text-ia">IA</span>
                </span>
            </a>


            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->

        <div class="search-bar">
            <form class="search-form d-flex align-items-center" method="POST" action="#">
                @csrf
                <input type="text" name="query" placeholder="Search" title="Enter search keyword">
                <button type="submit" title="Search"><i class="bi bi-search"></i></button>
            </form>
        </div><!-- End Search Bar -->

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item d-block d-lg-none">
                    <a class="nav-link nav-icon search-bar-toggle " href="#">
                        <i class="bi bi-search"></i>
                    </a>
                </li><!-- End Search Icon-->

                <!-- Notifications -->
                <li class="nav-item dropdown">
                    <a class="nav-link nav-icon position-relative" href="#" data-bs-toggle="dropdown"
                        aria-expanded="false">

                        <i class="bi bi-bell fs-5"></i>

                        @if (isset($unreadCount) && $unreadCount > 0)
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $unreadCount }}
                                <span class="visually-hidden">unread notifications</span>
                            </span>
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow p-0 shadow" style="width: 320px;">
                        <li
                            class="dropdown-header bg-primary text-white py-2 px-3 d-flex justify-content-between align-items-center">
                            <span>
                                You have {{ $unreadCount ?? 0 }} new
                                notification{{ ($unreadCount ?? 0) != 1 ? 's' : '' }}
                            </span>
                            <a href="{{ route('admin.notifications.index') }}"
                                class="badge bg-light text-primary text-decoration-none">View All</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider m-0">
                        </li>

                        @if (isset($headerNotifications) && $headerNotifications->count() > 0)
                            @foreach ($headerNotifications as $notification)
                                @php
                                    $icon = !$notification->is_read
                                        ? 'bi-exclamation-circle text-warning'
                                        : 'bi-info-circle text-primary';
                                    $bg = !$notification->is_read ? 'bg-light' : '';
                                @endphp
                                <li>
                                    <a href="{{ route('admin.notifications.show', $notification->id) }}"
                                        class="dropdown-item d-flex align-items-start {{ $bg }} py-2 px-3 text-decoration-none">
                                        <i class="bi {{ $icon }} fs-4 me-3 flex-shrink-0"></i>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold" title="{{ $notification->title }}"
                                                style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 50%;">
                                                {{ $notification->title }}
                                            </h6>
                                            <p class="mb-1 text-muted small" title="{{ $notification->description }}"
                                                style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 50%;">
                                                {{ \Illuminate\Support\Str::limit($notification->description, 60) }}
                                            </p>
                                            <small
                                                class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider m-0">
                                </li>
                            @endforeach
                        @else
                            <li class="text-center text-muted py-3">No notifications found</li>
                        @endif
                    </ul>
                </li>

                <!-- Profile Dropdown -->
                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                        data-bs-toggle="dropdown">
                        @if ($admin)
                            <img src="{{ $admin->image ? asset('uploads/admins/' . $admin->image) : asset('assets/img/default-admin.png') }}"
                                alt="Profile" class="rounded-circle">
                            <span
                                class="d-none d-md-block dropdown-toggle ps-2">{{ explode(' ', $admin->name)[0] ?? 'User' }}</span>
                        @else
                            <img src="{{ asset('assets/img/default-admin.png') }}" alt="Profile"
                                class="rounded-circle">
                            <span class="d-none d-md-block dropdown-toggle ps-2">Guest</span>
                        @endif
                    </a><!-- End Profile Image Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        @if ($admin)
                            <li class="dropdown-header">
                                <h6>{{ $admin->name }}</h6>
                                <span>{{ $admin->job ?? '--' }}</span>
                            </li>
                        @else
                            <li class="dropdown-header">
                                <h6>Guest</h6>
                                <span>--</span>
                            </li>
                        @endif
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center"
                                href="{{ route('dashboard.profile.index') }}">
                                <i class="bi bi-person"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <i class="bi bi-gear"></i>
                                <span>Account Settings</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <i class="bi bi-question-circle"></i>
                                <span>Need Help?</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        @if ($admin)
                            <li>
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center btn btn-link">
                                        <i class="bi bi-box-arrow-right"></i>
                                        <span>Sign Out</span>
                                    </button>
                                </form>
                            </li>
                        @endif

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->

            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->
