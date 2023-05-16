<x-admin.app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Product Sales Report') }}
        </h2>
    </x-slot>

    <div x-data="{
        settingsTab: 'general',
        activeClasses: 'rounded block pl-3 pr-4 py-2 border-l-4 border-blue-400 text-base font-medium text-blue-700 bg-blue-50 focus:outline-none focus:text-blue-800 focus:bg-blue-100 focus:border-blue-700 transition duration-150 ease-in-out',
        inactiveClasses: 'rounded block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out'
    }">
        <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mt-0 md:mt-0 md:col-span-2">

                <form action="{{ route('admin.productsalesreport.generate') }}" method="POST">
                    @csrf
                    <div class="shadow sm:rounded-md sm:overflow-hidden">
                        <div class="px-4 py-3 text-right bg-gray-50 sm:px-6">
                            <a href="{{ route('admin.productsalesreport.index') }}"
                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-900 bg-gray-200 border border-transparent rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                                {{ __('Back') }}
                            </a>
                            <button type="submit"
                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white border border-transparent rounded-md shadow-sm bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('Save') }}
                            </button>
                        </div>

                        <div x-data="{ options: ['custom_date_range'], selected: 'custom_date_range' }">
                            <div class="px-4 py-5 space-y-6 bg-white sm:p-6">
                                <div class="col-span-6 sm:col-span-3">

                                    <div x-data="{ options: ['custom_date_range'], report_period: 'custom_date_range' }">
                                        <div class="space-y-6">
                                            <label for="report_period"
                                                class="block text-sm font-medium text-gray-700">{{ __('Report period') }}</label>
                                            <select x-model="report_period" id="report_period" name="report_period"
                                                autocomplete="report_period"
                                                class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                <option value="all_time"
                                                    {{ old('report_period') == 'all_time' ? 'selected' : '' }}>
                                                    {{ __('All time') }}</option>
                                                <option value="custom_date_range"
                                                    {{ old('report_period', 'custom_date_range') == 'custom_date_range' ? 'selected' : '' }}>
                                                    {{ __('Custom date range') }}</option>
                                                <option value="today"
                                                    {{ old('report_period') == 'today' ? 'selected' : '' }}>
                                                    {{ __('Today') }}</option>
                                                <option value="yesterday"
                                                    {{ old('report_period') == 'yesterday' ? 'selected' : '' }}>
                                                    {{ __('Yesterday') }}</option>
                                                <option value="previous_7_days"
                                                    {{ old('report_period') == 'previous_7_days' ? 'selected' : '' }}>
                                                    {{ __('Previous 7 days') }} {{ __('(excluding today)') }}</option>
                                                <option value="previous_30_days"
                                                    {{ old('report_period') == 'previous_30_days' ? 'selected' : '' }}>
                                                    {{ __('Previous 30 days (excluding today)') }}</option>
                                                <option value="current_calendar_month"
                                                    {{ old('report_period') == 'current_calendar_month' ? 'selected' : '' }}>
                                                    {{ __('Current calendar month') }}</option>
                                                <option value="previous_calendar_month"
                                                    {{ old('report_period') == 'previous_calendar_month' ? 'selected' : '' }}>
                                                    {{ __('Previous calendar month') }}</option>
                                                <option value="last_12_months"
                                                    {{ old('report_period') == 'last_12_months' ? 'selected' : '' }}>
                                                    {{ __('Last 12 months') }}</option>
                                                <option value="year_to_date"
                                                    {{ old('report_period') == 'year_to_date' ? 'selected' : '' }}>
                                                    {{ __('This year to date') }}</option>
                                                <option value="previous_year"
                                                    {{ old('report_period') == 'previous_year' ? 'selected' : '' }}>
                                                    {{ __('Previous year') }}</option>
                                            </select>
                                        </div>

                                        <div x-show="report_period === 'custom_date_range'" class="p-4" x-cloak
                                            x-transition>

                                            <div class="col-span-6 sm:col-span-3">
                                                <label for="start_date" class="block text-sm font-medium text-gray-700">
                                                    {{ __('Start date') }}
                                                </label>
                                                <div class="flex mt-1 rounded-md shadow-sm">
                                                    <input type="date" name="start_date" id="start_date"
                                                        class="@error('start_date') focus:ring-red-500 border-red-300 focus:border-red-500 @else focus:ring-blue-500 focus:border-blue-500 border-gray-300 @enderror flex-1 block w-full rounded-md sm:text-sm"
                                                        placeholder="" value="{{ old('start_date', date('Y-m-d')) }}">
                                                </div>
                                            </div>

                                            <div class="col-span-6 sm:col-span-3">
                                                <label for="end_at" class="block text-sm font-medium text-gray-700">
                                                    {{ __('End date') }}
                                                </label>
                                                <div class="flex mt-1 rounded-md shadow-sm">
                                                    <input type="date" name="end_at" id="end_at"
                                                        class="@error('end_at') focus:ring-red-500 border-red-300 focus:border-red-500 @else focus:ring-blue-500 focus:border-blue-500 border-gray-300 @enderror flex-1 block w-full rounded-md sm:text-sm"
                                                        placeholder="" value="{{ old('end_at', date('Y-m-d')) }}">
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div x-data="{ options: ['report_resource'], report_resource: 'all' }">

                                        <div class="mt-6 space-y-6">
                                            <label for="report_resource"
                                                class="block text-sm font-medium text-gray-700">{{ __('Report resource') }}</label>
                                            <select x-model="report_resource" id="report_resource"
                                                name="report_resource" autocomplete="report_resource"
                                                class="block w-full mt-0 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                <option value="all" {{ old('') == '' ? 'selected' : '' }}>
                                                    {{ __('Everyone') }}</option>
                                                <option value="staffassociation" {{ old('') == '' ? 'selected' : '' }}>
                                                    {{ __('Staffassociations') }}</option>
                                                <option value="user" {{ old('') == '' ? 'selected' : '' }}>
                                                    {{ __('Customer') }}</option>
                                            </select>
                                        </div>

                                        <div x-show="report_resource === 'staffassociation'" class="p-4" x-cloak
                                            x-transition>
                                            <div class="col-span-6 sm:col-span-3">
                                                <label for="staffassociation"
                                                    class="block text-sm font-medium text-gray-700">Choose a staffassociation
                                                </label>
                                                <select id="report_resource_staffassociation"
                                                    name="report_resource_staffassociation"
                                                    class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                    @foreach ($staffassociations as $staffassociation)
                                                        <option value="{{ $staffassociation->id }}">
                                                            {{ $staffassociation->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div x-show="report_resource === 'user'" class="p-4" x-cloak x-transition>
                                            <div class="col-span-6 sm:col-span-3">
                                                <label for="report_resource_user"
                                                    class="block text-sm font-medium text-gray-700">
                                                    {{ __('Customer id') }}
                                                </label>
                                                <div class="flex mt-1 rounded-md shadow-sm">
                                                    <input type="text" name="report_resource_user"
                                                        id="report_resource_user"
                                                        class="@error('report_resource_user') focus:ring-red-500 border-red-300 focus:border-red-500 @else focus:ring-blue-500 focus:border-blue-500 border-gray-300 @enderror flex-1 block w-full rounded-md sm:text-sm"
                                                        placeholder="" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-5">
                                        <label for="order_status"
                                            class="block text-sm font-medium text-gray-700">{{ __('Include with order status') }}</label>

                                        <div class="flex mt-1 items-star">
                                            <div class="flex items-center h-5">
                                                <input id="order_status-waiting-for-payment"
                                                    name="order_status[waiting_for_payment]" type="checkbox"
                                                    class="w-4 h-4 border-gray-300 rounded focus:ring-blue-500 text-blue-600"
                                                    {{ old('order_status[waiting_for_payment]') ? 'checked' : '' }}>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="order_status-waiting-for-payment"
                                                    class="font-medium text-gray-700">{{ __('Waiting for payment') }}</label>
                                            </div>
                                        </div>

                                        <div class="flex mt-1 items-star">
                                            <div class="flex items-center h-5">
                                                <input id="order_status-paid" name="order_status[paid]"
                                                    type="checkbox"
                                                    class="w-4 h-4 border-gray-300 rounded focus:ring-blue-500 text-blue-600"
                                                    {{ old('order_status[paid]') ? 'checked' : '' }}>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="order_status-paid"
                                                    class="font-medium text-gray-700">{{ __('Paid') }}</label>
                                            </div>
                                        </div>

                                        <div class="flex mt-1 items-star">
                                            <div class="flex items-center h-5">
                                                <input id="order_status-in-progress" name="order_status[in_progress]"
                                                    type="checkbox"
                                                    class="w-4 h-4 border-gray-300 rounded focus:ring-blue-500 text-blue-600"
                                                    {{ old('order_status[in_progress]') ? 'checked' : '' }}>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="order_status-in-progress"
                                                    class="font-medium text-gray-700">{{ __('In progress') }}</label>
                                            </div>
                                        </div>

                                        <div class="flex mt-1 items-star">
                                            <div class="flex items-center h-5">
                                                <input id="order_status-shipped" name="order_status[shipped]"
                                                    type="checkbox"
                                                    class="w-4 h-4 border-gray-300 rounded focus:ring-blue-500 text-blue-600"
                                                    {{ old('order_status[shipped]') ? 'checked' : '' }}>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="order_status-shipped"
                                                    class="font-medium text-gray-700">{{ __('Shipped') }}</label>
                                            </div>
                                        </div>

                                        <div class="flex mt-1 items-star">
                                            <div class="flex items-center h-5">
                                                <input id="order_status-completed" name="order_status[completed]"
                                                    type="checkbox"
                                                    class="w-4 h-4 border-gray-300 rounded focus:ring-blue-500 text-blue-600"
                                                    {{ old('order_status[completed]') ? 'checked' : 'checked' }}>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="order_status-completed"
                                                    class="font-medium text-gray-700">{{ __('Completed') }}</label>
                                            </div>
                                        </div>

                                        <div class="flex mt-1 items-star">
                                            <div class="flex items-center h-5">
                                                <input id="order_status-failed" name="order_status[failed]"
                                                    type="checkbox"
                                                    class="w-4 h-4 border-gray-300 rounded focus:ring-blue-500 text-blue-600"
                                                    {{ old('order_status[failed]') ? 'checked' : '' }}>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="order_status-failed"
                                                    class="font-medium text-gray-700">{{ __('Failed') }}</label>
                                            </div>
                                        </div>

                                        <div class="flex mt-1 items-star">
                                            <div class="flex items-center h-5">
                                                <input id="order_status-on_hold" name="order_status[on_hold]"
                                                    type="checkbox"
                                                    class="w-4 h-4 border-gray-300 rounded focus:ring-blue-500 text-blue-600"
                                                    {{ old('order_status[on_hold]') ? 'checked' : '' }}>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="order_status-on_hold"
                                                    class="font-medium text-gray-700">{{ __('On hold') }}</label>
                                            </div>
                                        </div>

                                        <div class="flex mt-1 items-star">
                                            <div class="flex items-center h-5">
                                                <input id="order_status-canceled" name="order_status[canceled]"
                                                    type="checkbox"
                                                    class="w-4 h-4 border-gray-300 rounded focus:ring-blue-500 text-blue-600"
                                                    {{ old('order_status[canceled]') ? 'checked' : '' }}>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="order_status-canceled"
                                                    class="font-medium text-gray-700">{{ __('Canceled') }}</label>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="mt-5">
                                        <label for="report_fields"
                                            class="block text-sm font-medium text-gray-700">{{ __('Report fields') }}</label>

                                        <div class="flex mt-1 items-star">
                                            <div class="flex items-center h-5">
                                                <input id="report_fields-product_id" name="report_fields[product_id]"
                                                    type="checkbox"
                                                    class="w-4 h-4 border-gray-300 rounded focus:ring-blue-500 text-blue-600"
                                                    checked>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="report_fields-product_id"
                                                    class="font-medium text-gray-700">{{ __('Product ID') }}</label>
                                            </div>
                                        </div>

                                        <div class="flex mt-1 items-star">
                                            <div class="flex items-center h-5">
                                                <input id="report_fields-product_sku"
                                                    name="report_fields[product_sku]" type="checkbox"
                                                    class="w-4 h-4 border-gray-300 rounded focus:ring-blue-500 text-blue-600"
                                                    checked>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="report_fields-product_sku"
                                                    class="font-medium text-gray-700">{{ __('Product SKU') }}</label>
                                            </div>
                                        </div>

                                        <div class="flex mt-1 items-star">
                                            <div class="flex items-center h-5">
                                                <input id="report_fields-product_name"
                                                    name="report_fields[product_name]" type="checkbox"
                                                    class="w-4 h-4 border-gray-300 rounded focus:ring-blue-500 text-blue-600"
                                                    checked>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="report_fields-product_name"
                                                    class="font-medium text-gray-700">{{ __('Product name') }}</label>
                                            </div>
                                        </div>

                                        <div class="flex mt-1 items-star">
                                            <div class="flex items-center h-5">
                                                <input id="report_fields-quantity_sold"
                                                    name="report_fields[quantity_sold]" type="checkbox"
                                                    class="w-4 h-4 border-gray-300 rounded focus:ring-blue-500 text-blue-600"
                                                    checked>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="report_fields-quantity_sold"
                                                    class="font-medium text-gray-700">{{ __('Quantity sold') }}</label>
                                            </div>
                                        </div>

                                        <div class="flex mt-1 items-star">
                                            <div class="flex items-center h-5">
                                                <input id="report_fields-gross_sales"
                                                    name="report_fields[gross_sales]" type="checkbox"
                                                    class="w-4 h-4 border-gray-300 rounded focus:ring-blue-500 text-blue-600"
                                                    checked>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="report_fields-gross_sales"
                                                    class="font-medium text-gray-700">{{ __('Gross sales') }}</label>
                                            </div>
                                        </div>

                                        <div class="mt-6 space-y-6">
                                            <label for="report_type"
                                                class="block text-sm font-medium text-gray-700">{{ __('Report type') }}</label>
                                            <select id="report_type" name="report_type" autocomplete="report_type"
                                                class="block w-full mt-0 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                <option value="view"
                                                    {{ old('report_type') == '' ? 'selected' : '' }}>
                                                    {{ __('View') }}</option>
                                                <option value="csv"
                                                    {{ old('report_type') == '' ? 'selected' : '' }}>
                                                    {{ __('CSV') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 text-right bg-gray-50 sm:px-6">
                            <a href="{{ route('admin.products.index') }}"
                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-900 bg-gray-200 border border-transparent rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                                {{ __('Back') }}
                            </a>
                            <button type="submit"
                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white border border-transparent rounded-md shadow-sm bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('Save') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin.app-layout>
