@props(['title' => 'Portal'])

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title }}</title>
  @vite('resources/css/app.css')
</head>
<body class="bg-neutral-100 dark:bg-slate-900 dark:text-neutral-100 flex flex-col min-h-screen">
  
  {{ $slot }}

  <x-footer class="pl-54 mt-auto"></x-footer>
</body>
</html>