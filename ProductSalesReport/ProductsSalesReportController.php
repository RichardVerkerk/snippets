<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StaffAssociation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductSalesReportController extends Controller
{
    public function index()
    {
        $staffassociations = StaffAssociation::all();

        return view('admin.productsalesreport.form', compact('staffassociations'));
    }

    public function generate(Request $request)
    {
        $orders = Order::with(['items', 'items.variation', 'items.product', 'user', 'user.staffassociation'])
            ->whereHas('user', function ($query) use ($request) {
                $query->when($request->report_resource == 'user', function ($q) use ($request) {
                    $q->where('id', $request->report_resource_user);
                });
            })
            ->whereHas('user.staffassociation', function ($query) use ($request) {
                $query->when($request->report_resource == 'staffassociation', function ($q) use ($request) {
                    $q->where('staff_associations.id', $request->report_resource_staffassociation);
                });
            })
            ->where(function ($query) use ($request) {
                $query->when($request->report_period == 'today', function ($q) {
                    $q->whereDate('created_at', Carbon::now());
                });

                $query->when($request->report_period == 'yesterday', function ($q) {
                    $q->whereDate('created_at', Carbon::yesterday());
                });

                $query->when($request->report_period == 'previous_7_days', function ($q) {
                    $q->whereDate('created_at', '>=', Carbon::now()->subDays(7))->whereDate('created_at', '<=', Carbon::yesterday());
                });

                $query->when($request->report_period == 'previous_30_days', function ($q) {
                    $q->whereDate('created_at', '>=', Carbon::now()->subDays(30))->whereDate('created_at', '<=', Carbon::yesterday());
                });

                $query->when($request->report_period == 'current_calendar_month', function ($q) {
                    $q->whereDate('created_at', '>=', Carbon::now()->startOfMonth())->whereDate('created_at', '<=', Carbon::today());
                });

                $query->when($request->report_period == 'previous_calendar_month', function ($q) {
                    $q->whereDate('created_at', '>=', Carbon::now()->startOfMonth()->subMonth(1))->whereDate('created_at', '<=', Carbon::today()->startOfMonth()->subDay());
                });

                $query->when($request->report_period == 'last_12_months', function ($q) {
                    $q->whereDate('created_at', '>=', Carbon::now()->startOfMonth()->subMonths(12))->whereDate('created_at', '<=', Carbon::today());
                });

                $query->when($request->report_period == 'year_to_date', function ($q) {
                    $q->whereDate('created_at', '>=', Carbon::today()->startOfYear())->whereDate('created_at', '<=', Carbon::today());
                });

                $query->when($request->report_period == 'previous_year', function ($q) {
                    $q->whereDate('created_at', '>=', Carbon::today()->startOfYear()->subYear())->whereDate('created_at', '<=', Carbon::today()->endOfYear()->subYear());
                });

                $query->when($request->report_period == 'custom_date_range', function ($q) use ($request) {
                    $startDate = Carbon::createFromDate($request->start_date);
                    $endDate = Carbon::createFromDate($request->end_date);

                    $q->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate);
                });
            })
            ->where(function ($query) use ($request) {
                if (isset($request->order_status)) {
                    foreach ($request->order_status as $statusKey => $statusValue) {
                        $query->orWhere('status', $statusKey);
                    }
                }
            })->get();

        $list = [];
        $columns = [];

        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                if (array_key_exists('product_id', $request->report_fields)) {
                    $list[$item->variation_id]['product_id'] = $item->product_id;
                    $list[$item->variation_id]['variation_id'] = $item->variation_id;

                    if (!in_array('Product #', $columns) && !in_array('Variation #', $columns)) {
                        $columns[] = 'Product #';
                        $columns[] = 'Variation #';
                    }
                }
                if (array_key_exists('product_sku', $request->report_fields)) {
                    $list[$item->variation_id]['product_sku'] = $item->variation->sku;
                    if (!in_array('SKU', $columns)) {
                        $columns[] = 'SKU';
                    }
                }
                if (array_key_exists('product_name', $request->report_fields)) {
                    $list[$item->variation_id]['product_title'] = $item->title;
                    $list[$item->variation_id]['variation_title'] = $item->variation_title;

                    if (!in_array('Product title', $columns) && !in_array('Variation title', $columns)) {
                        $columns[] = 'Product title';
                        $columns[] = 'Variation title';
                    }
                }

                if (array_key_exists('quantity_sold', $request->report_fields)) {
                    $list[$item->variation_id]['quantity_sold'] = isset($list[$item->variation_id]['quantity_sold']) ? $item->amount + $list[$item->variation_id]['quantity_sold'] : $item->amount;

                    if (!in_array('Quantity sold', $columns)) {
                        $columns[] = 'Quantity sold';
                    }
                }

                if (array_key_exists('gross_sales', $request->report_fields)) {
                    $list[$item->variation_id]['gross_sales'] = isset($list[$item->variation_id]['gross_sales']) ? ($item->price * $item->amount) + $list[$item->variation_id]['gross_sales'] : ($item->price * $item->amount);

                    if (!in_array('Gross sales', $columns)) {
                        $columns[] = 'Gross sales';
                    }
                }
            }
        }

        $filename = 'Sales report - ';

        switch ($request->report_period) {
            case 'today':
                $filename .= Carbon::now()->format('d-m-Y');
                break;
            case 'yesterday':
                $filename .= Carbon::yesterday()->format('d-m-Y');
                break;
            case 'previous_7_days':
                $filename .= Carbon::now()->subDays(7)->format('d-m-Y') . ' - ' . Carbon::yesterday()->format('d-m-Y');
                break;
            case 'previous_30_days':
                $filename .= Carbon::now()->subDays(30)->format('d-m-Y') . ' - ' . Carbon::yesterday()->format('d-m-Y');
                break;
            case 'current_calendar_month':
                $filename .= Carbon::now()->startOfMonth()->format('d-m-Y') . ' - ' . Carbon::today()->format('d-m-Y');
                break;
            case 'previous_calendar_month':
                $filename .= Carbon::now()->startOfMonth()->subMonth()->format('d-m-Y') . ' - ' . today()->startOfMonth()->subDay()->format('d-m-Y');
                break;
            case 'this_year_till_date':
                $filename .= Carbon::today()->startOfYear()->format('d-m-Y') . ' - ' . Carbon::today()->format('d-m-Y');
                break;
            case 'last_year':
                $filename .= today()->startOfYear()->subYear()->format('d-m-Y') . ' - ' . today()->endOfYear()->subYear()->format('d-m-Y');
                break;
        }

        if ($request->report_type === 'csv') {

            if ($request->report_period) {
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=" . $filename,
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0",
                );
            }

            $callback = function () use ($columns, $list) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($list as $item) {

                    fputcsv($file, $item);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } elseif ($request->report_type === 'view') {
            return view('admin.productsalesreport.viewreport', compact('list', 'columns', 'filename'));
        } else {
            return abort(404);
        }

    }

}
