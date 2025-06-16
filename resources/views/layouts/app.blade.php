<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.head')
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" /> --}}
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        @include('layouts.sidebar')
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    @include('layouts.logo-header')
                    <!-- End Logo Header -->
                </div>
                <!-- Navbar Header -->
                    @include('layouts.navbar')
                <!-- End Navbar -->
            </div>

            <div class="container">
                @yield('content')
            </div>

            <footer class="footer">
                @include('layouts.footer')
            </footer>
        </div>

        <!-- End Custom template -->
    </div>

    @include('layouts.script')
    
    {{-- ... any global scripts ... --}}
    @stack('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
</body>

</html>
