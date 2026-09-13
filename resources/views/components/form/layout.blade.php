<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'GSCAB Portal' }}</title>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-sky-200 flex flex-col justify-center items-center py-10 px-4 sm:px-6 lg:px-8">
    
    {{-- Main Form Container --}}
    <main {{ $attributes->merge(['class' => 'w-full']) }}>
        {{ $slot }}
    </main>

</body>
</html>