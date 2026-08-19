@props(['title' => ''])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSCAB | {{ $title }}</title>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @vite('resources/css/app.css')
</head>

<body class="bg-neutral-200 min-h-screen flex justify-center items-center font-sans antialiased dark:text-neutral-100 dark:bg-slate-900">
    
    <main class="w-full flex justify-center items-center px-4">
        {{ $slot }}
    </main>

</body>
</html>