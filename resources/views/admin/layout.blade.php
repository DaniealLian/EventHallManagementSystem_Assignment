<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Event Hall Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
        }
        .admin-header {
            background: white;
            border-bottom: 1px solid #dee2e6;
        }
        .stat-card {
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .sidebar-brand {
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
            padding: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand d-block text-center">
                        <i class="fas fa-shield-alt"></i> Admin Panel
                    </a>

                    <nav class="nav flex-column p-3">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                           href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>

                        <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}"
                           href="{{ route('admin.users') }}">
                            <i class="fas fa-users me-2"></i> Users
                        </a>

                        <a class="nav-link {{ request()->routeIs('admin.manager.*') ? 'active' : '' }}"
                           href="{{ route('admin.manager.applications') }}">
                            <i class="fas fa-user-tie me-2"></i> Manager Applications
                            @if(\App\Models\User::where('manager_status', 'pending')->count() > 0)
                                <span class="badge bg-warning ms-2">
                                    {{ \App\Models\User::where('manager_status', 'pending')->count() }}
                                </span>
                            @endif
                        </a>

                        <hr class="text-white">

                        <form action="{{ route('admin.logout') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-0">
                <!-- Header -->
                <div class="admin-header py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">@yield('title', 'Admin Dashboard')</h5>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> {{ auth('admin')->user()->name }}
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.profile') }}">
                                    <i class="fas fa-user-cog"></i> Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('admin.logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item" type="submit">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
