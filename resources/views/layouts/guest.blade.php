<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Auth' }} - K-Glow</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-50">

    <div class="flex w-full max-w-5xl bg-white shadow-xl rounded-2xl overflow-hidden">
        <!-- Left Side Image / Branding -->
        <div class="hidden md:flex md:w-1/2 bg-brand-orange items-center justify-center p-10">
            <div class="text-center text-white space-y-4">
                <h1 class="text-4xl font-bold">K-Glow</h1>
                <p class="text-lg opacity-90">Discover the beauty of Korea, delivered to you.</p>
                <img src="{{ asset('images/branding.png') }}" alt="K-Glow" class="mx-auto w-48">
            </div>
        </div>

        <!-- Right Side Content -->
        <div class="w-full md:w-1/2 p-8">
            {{ $slot }}
        </div>
    </div>

</body>

</html>