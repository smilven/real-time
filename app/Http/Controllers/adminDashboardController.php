<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\orderItem;
use App\Models\product;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exports\OrderItemsExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminDashboardController extends Controller
{
    // 显示管理面板视图
    public function indexAdminDashboard()
    {

        $orderItems = OrderItem::paginate(10);

        // 计算本周总金额
        $currentWeekTotal = OrderItem::selectRaw('SUM(quantity * price) as total')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->value('total') ?? 0;

        // 计算上周总金额
        $lastWeekTotal = OrderItem::selectRaw('SUM(quantity * price) as total')
            ->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
            ->value('total') ?? 0;

        // 计算百分比变化
        $percentageChange = $lastWeekTotal > 0
            ? (($currentWeekTotal - $lastWeekTotal) / $lastWeekTotal) * 100
            : 0; // 如果上周总金额为 0，直接设为 0%

        $totalUser = User::count();


        $lastMonthUserCount = User::whereBetween('created_at', [
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->subMonth()->endOfMonth()
        ])->count();

        // 计算百分比增长
        $userGrowthPercentage = (($totalUser - $lastMonthUserCount) / $lastMonthUserCount) * 100;

        $totalItem = product::count();


        $itemGrowthPercentage = product::whereBetween('created_at', [
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->subMonth()->endOfMonth()
        ])->count();

        // 计算百分比增长
        $itemGrowthPercentage = (($totalItem - $itemGrowthPercentage) / $itemGrowthPercentage) * 100;

        $totalMoney = OrderItem::selectRaw('SUM(quantity * price) as total')->value('total') ?? 0;

        $lastMonthTotal = OrderItem::selectRaw('SUM(quantity * price) as total')
        ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
        ->value('total') ?? 0;

        $percentageMonthChange = $lastMonthTotal > 0
            ? (($totalMoney - $lastMonthTotal) / $lastMonthTotal) * 100
            : 0;
        return view('adminDashboard', compact('currentWeekTotal', 'percentageChange', 'totalUser', 'userGrowthPercentage', 'totalItem', 'itemGrowthPercentage', 'totalMoney','percentageMonthChange','orderItems'));
    }
    public function getDailySalesData()
    {
        // Get daily sales for the current week
        $sales = DB::table('order_items')
            ->select(DB::raw('SUM(quantity * price) as total_sales'), DB::raw('DAYOFWEEK(created_at) as day'))
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]) // Current week
            ->groupBy(DB::raw('DAYOFWEEK(created_at)'))
            ->orderBy(DB::raw('DAYOFWEEK(created_at)'), 'asc')
            ->get();
    
        // Prepare sales data for each day of the week (Sun, Mon, etc.)
        $dailySales = [];
        foreach ($sales as $sale) {
            $dailySales[] = $sale->total_sales;
        }
    
        // Get the last update time
        $lastUpdate = DB::table('order_items')
            ->orderBy('created_at', 'desc')
            ->first();
    
        // Calculate the time difference and round to the nearest minute
        $minutesAgo = round(now()->diffInMinutes($lastUpdate->created_at));
    
        // Day labels for the current week (adjust according to the current week start)
        $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    
        return response()->json([
            'days' => $days,
            'sales' => $dailySales,
            'minutes_ago' => -($minutesAgo),
        ]);
    }
    
    public function getWeeklySalesData()
    {
        $sales = DB::table('order_items')
            ->select(DB::raw('SUM(quantity * price) as total_sales'), DB::raw('WEEK(created_at) as week'))
            ->whereBetween('created_at', [now()->subWeeks(4), now()]) // Last 4 weeks (adjust as needed)
            ->groupBy(DB::raw('WEEK(created_at)'))
            ->orderBy(DB::raw('WEEK(created_at)'), 'asc')
            ->get();
    
        // Calculate percentage change for each week
        $weeklySales = [];
        $previousSales = null;
        $percentageChanges = [];
    
        foreach ($sales as $weekSale) {
            $weeklySales[] = $weekSale->total_sales;
            if ($previousSales) {
                $percentageChange = (($weekSale->total_sales - $previousSales) / $previousSales) * 100;
                $percentageChanges[] = round($percentageChange, 2);
            } else {
                $percentageChanges[] = 0; // No percentage change for the first week
            }
            $previousSales = $weekSale->total_sales;
        }
    
        // Get the last update time
        $lastUpdate = DB::table('order_items')
            ->orderBy('created_at', 'desc')
            ->first();
    
        // Calculate the time difference and round to the nearest minute
        $minutesAgo = round(now()->diffInMinutes($lastUpdate->created_at));
    
        // Prepare data for the frontend
        $weeks = ['WEEK 1', 'WEEK 2', 'WEEK 3', 'WEEK 4']; // Adjust as needed based on the number of weeks fetched
    
        return response()->json([
            'weeks' => $weeks,
            'sales' => $weeklySales,
            'percentage_changes' => $percentageChanges,
            'minutes_ago' => -($minutesAgo), // Return the rounded minutes
        ]);
    }
    public function getMonthlySalesData()
{
    // Get sales data for the last 12 months
    $sales = DB::table('order_items')
        ->select(DB::raw('SUM(quantity * price) as total_sales'), DB::raw('MONTH(created_at) as month'))
        ->whereBetween('created_at', [now()->subYear(), now()]) // Last 12 months
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->orderBy(DB::raw('MONTH(created_at)'), 'asc')
        ->get();

    // Prepare sales data for each month
    $monthlySales = [];
    foreach ($sales as $sale) {
        $monthlySales[] = $sale->total_sales;
    }

    // Get the last update time
    $lastUpdate = DB::table('order_items')
        ->orderBy('created_at', 'desc')
        ->first();

    // Calculate the time difference and round to the nearest minute
    $minutesAgo = round(now()->diffInMinutes($lastUpdate->created_at));

    // Month labels (adjust according to the current year/month data)
    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    return response()->json([
        'months' => $months,
        'sales' => $monthlySales,
        'minutes_ago' => -($minutesAgo),
    ]);
}

public function exportOrderItemsToExcel()
{
    return Excel::download(new OrderItemsExport, 'Sales.xlsx');
}


}


    