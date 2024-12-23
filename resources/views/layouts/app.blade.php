<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi KP')</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light" style="background: linear-gradient(45deg, #1d2671, #c33764);">
    <div class="container">
        <a class="navbar-brand text-white" href="#">Sistem KP</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ auth()->user()->isAdmin() ? route('dashboards.dashboardAdm') : route('dashboards.dashboardMhs') }}">Dashboard</a>
                    </li>
                    @if(auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('students.student') }}">Data Mahasiswa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('schedules.schedule') }}">Jadwal Pengajuan</a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#" onclick="confirmLogout(event)">
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('login') }}">Login</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<div class="container py-4">
    @yield('content')
</div>

<footer style="background-color: #1d2671; color: #fff;">
    <div class="container py-3">
        <div class="row">
            <div class="col-md-6">
                <p>&copy; 2024 Sistem KP</p>
            </div>
        </div>
    </div>
</footer>
</body>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>

<style>
    .navbar-nav .nav-link {
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .navbar-nav .nav-link:hover {
        color: #c33764;
        text-decoration: underline;
    }
    footer a {
        font-size: 1.2rem;
        transition: color 0.3s ease;
    }
    footer a:hover {
        color: #c33764;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmLogout(event) {
        event.preventDefault(); 

        Swal.fire({
            title: 'Apakah Anda yakin ingin logout?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>

</html>
