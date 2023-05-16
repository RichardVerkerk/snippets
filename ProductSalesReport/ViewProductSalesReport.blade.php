<x-admin.app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('View sales report') }} - {{ $filename }}
        </h2>
    </x-slot>

    <div class="pt-4 pb-5">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mt-0 md:mt-0 md:col-span-2">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-3 text-right bg-gray-50 sm:px-6">
                        <a href="{{ url()->previous() }}"
                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-900 bg-gray-200 border border-transparent rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                            {{ __('Back') }}
                        </a>
                    </div>
                    <div class="px-4 py-5 space-y-6 bg-white sm:p-6">
                        <div>

                            <table class="w-full mb-6 table-auto">
                                <thead>
                                    <tr>
                                        @foreach ($columns as $column)
                                            <th class="px-4 py-2">{{ $column }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($list as $item)
                                        <tr class="cursor-pointer hover:bg-gray-100">
                                            @if (isset($item['product_id']))
                                                <td class="px-4 py-2 border">{{ $item['product_id'] }}</td>
                                            @endif
                                            @if (isset($item['variation_id']))
                                                <td class="px-4 py-2 border">{{ $item['variation_id'] }}</td>
                                            @endif
                                            @if (isset($item['product_sku']))
                                                <td class="px-4 py-2 border">{{ $item['product_sku'] }}</td>
                                            @endif
                                            @if (isset($item['product_title']))
                                                <td class="px-4 py-2 border">{{ $item['product_title'] }}</td>
                                            @endif
                                            @if (isset($item['variation_title']))
                                                <td class="px-4 py-2 border">{{ $item['variation_title'] }}</td>
                                            @endif
                                            @if (isset($item['quantity_sold']))
                                                <td class="px-4 py-2 border">{{ $item['quantity_sold'] }}</td>
                                            @endif
                                            @if (isset($item['gross_sales']))
                                                <td class="px-4 py-2 border">
                                                    &euro;{{ number_format($item['gross_sales'], 2, ',', '.') }}</td>
                                            @endif
                                        </tr>
                                    @empty
                                        {{ __('There are no sales to report.') }}
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</x-admin.app-layout>
