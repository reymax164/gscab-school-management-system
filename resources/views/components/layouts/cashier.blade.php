@props(['header', 'title'])

<x-layouts.base>

  
  <header class="flex justify-between items-center w-full py-4 px-8 pl-64 top-0 z-10">
    <h1 class="text-blue-900 dark:text-neutral-100 font-bold text-xl">
      {{ $header }}
    </h1>

    <div class="flex items-center gap-3">
      <div class="text-right">
        <strong class="block font-bold text-gray-900 dark:text-neutral-100">{{ auth()->user()->name ?? 'Name' }}</strong>
        <p class="text-sm text-gray-600 dark:text-neutral-300">Cashier</p>
      </div>
      <img src="" alt="Profile" class="w-10 h-10 rounded-full object-cover bg-neutral-200" />
    </div>
  </header>

  <x-nav.bar>
    {{-- <x-nav.link route="cashier.dashboard" label="Dashboard"/>
    <x-nav.link route="cashier.balances" label="Balances"/>
    <x-nav.link route="cashier.history" label="Transaction History"/>
    <x-nav.link route="cashier.reports" label="Reports"/> --}}
  </x-nav.bar>

  <main class="min-h-screen pl-54 p-64 grow">
    {{ $slot }}
  </main>

</x-layouts.base>