<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    @php
        use App\Models\Setting;
        $siteName = Setting::get('site_name', 'K-Glow');
        $primaryColor = Setting::get('primary_color', '#f36c21');
        $whiteLogo = Setting::get('white_logo', 'admin-assets/white-logo.png');
    @endphp
    
    <title>@yield('title') - {{ $siteName }}</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Colors -->
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'brand-orange': '{{ $primaryColor }}',
                }
            }
        }
    }
    </script>
    <link rel="icon" href="fav.PNG" type="image/x-icon" />

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
    :root {
        --brand-orange: #f36c21;
    }

    .hero-dot {
        width: 8px;
        height: 8px;
        border-radius: 9999px;
    }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-50">

    <div class="flex w-full max-w-5xl bg-white shadow-xl rounded-2xl overflow-hidden">
        <!-- Left Side Image / Branding -->
        <!-- Left Banner -->
        <div
            class="hidden md:flex flex-col items-center justify-center p-10  from-white/20  backdrop-blur-lg text-white bg-brand-orange">
            <h2 class="text-4xl font-extrabold tracking-wide">Welcome Back 👋</h2>
            <p class="mt-4 text-lg text-white/80 text-center">
                Discover the beauty of Korea, delivered to you. <span class="text-white font-semibold"></span>.
            </p>
            <img src="{{ asset($whiteLogo) }}" class="w-56 mt-8 animate-bounce" alt="{{ $siteName }}">
        </div>


        <!-- Right Side Content -->
        <div class="w-full md:w-1/2 p-8">
            @yield('content')
        </div>
    </div>

</body>

</html>