<x-layouts.app title="Casher | Dashboard" header="Cashier Dashboard"
               class="grid grid-cols-2 md:grid-cols-4 gap-4 content-start p-4 md:p-6">
  
  <x-widgets.number
    title="Transactions Today"
    subtitle="Today"
  />

  <x-widgets.number
    title="Today's Collection"
  />

  <x-widgets.number
    title="OR Issued"
    subtitle="Today"
  />

  <x-widgets.number
    title="Outstanding Balance"
    footer="View Student Balance"
  />

  <x-widgets.multi-col-scroll 
      title="Recent Payments" 
      :header="true" 
      footer="View All Transaction" 
      href="/transactions" 
      :columns="$tableColumns"
      :rows="$paymentRows" 
      class="h-72 col-start-1 col-end-3 md:col-end-5"
  />
</x-layouts.app>ks