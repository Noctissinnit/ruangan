<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Booking Meeting Room') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- icon Minimize --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


    <!-- icon -->
    <link rel="icon" href="{{ asset('images/logoykbs.png') }}" type="image/png">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMvKMEALPm3zYd5y7Ff13/UhgM+J7B7EXGk80fo" crossorigin="anonymous"> --}}
    <script src="/js/app.js"></script>
    <script src="/js/validate.js"></script>
    
    <script>
        $(document).ready(() => {
            @if ($errors->any())
                error("{{ $errors->first() }}");
            @endif
            @if (session()->has('error'))
                error("{{ session('error') }}");
            @endif

            @if (session()->has('success'))
                success("{{ session('success') }}");
            @endif
        });
    </script>
    <style>
        .navbar-brand {
    transition: color 0.3s ease, transform 0.3s ease; /* Animasi smooth */
    color: #000; /* Warna default */
    text-decoration: none;
    }

        .navbar-brand:hover {
        color: #007bff; /* Warna saat hover (contoh: biru Bootstrap) */
        transform: scale(1.1); /* Sedikit memperbesar */
        text-decoration: none; /* Hilangkan garis bawah */
    }

    .btn-outline-primary {
    transition: background-color 0.3s ease, color 0.3s ease, transform 0.3s ease; /* Animasi halus */
        }

        .btn-outline-primary:hover {
            background-color: #007bff; /* Warna biru solid */
            color: #fff; /* Ubah warna ikon dan teks menjadi putih */
            transform: scale(1.1); /* Sedikit memperbesar tombol */
        }

        .btn-outline-primary i {
            transition: transform 0.3s ease; /* Animasi ikon */
        }

        .btn-outline-primary:hover i {
            transform: rotate(15deg); /* Ikon sedikit berputar saat hover */
        }
        

    </style>

    @yield('head')
</head>
<body>
    @include('layouts.loading')
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <!-- Back to Home -->
                <a class="navbar-brand" href="
                @if(Route::is('home') || (isset($room) && $room->type === 'all'))
                    {{ route('home') }}
                @elseif(Route::is('home.mikael') || (isset($room) && $room->type === 'mikael'))
                    {{ route('home.mikael') }}
                @else
                    {{ route('home.yayasan') }}
                @endif
            ">
                @if(in_array(Route::currentRouteName(), ['home.yayasan', 'home.mikael', 'home.']))
                    Booking Room
                @else
                    <i class="bi bi-arrow-left"></i> <!-- Bootstrap Icon -->
                @endif
            </a>


                
                @auth
                    <a class="nav-link ms-1 mt-1" href="{{ route(auth()->user()->isAdmin() ? 'admin.dashboard' : 'user.dashboard') }}">Dashboard</a>
                    @admin
                        <a class="nav-link ms-3 mt-1" href="{{ route('rooms.index') }}">Rooms</a>
                    @endadmin
                @endauth
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto"></ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            {{-- Login Options --}}
                        @else
                        <li class="nav-item bg-primary rounded">
                                <a id="nav-link" class="nav-link text-light" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Halo, {{ Auth::user()->name }}
                                </a>

                            </li>
                            <li class="nav-item bg-danger rounded mx-1">
                                <a class="nav-link text-light" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>

</html>