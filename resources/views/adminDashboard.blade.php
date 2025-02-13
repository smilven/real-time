@extends('layouts.app')

@section('content')

<style>
/* Apply to cards within the specific row */
.row .col-lg-4 {
    display: flex;
    flex-direction: column;
    height: 100%;
}

/* Ensure the card body takes up available space and the cards have uniform height */
.row .col-lg-4 .card {
    height: 100%;
    display: flex;
    flex-direction: column;
}

/* Make sure the card content stretches to fill the card */
.row .col-lg-4 .card-body {
    flex: 1;
}

/* Optionally, set a fixed height for uniform card size */
.row .col-lg-4 .card {
    min-height: 380px; /* Adjust as needed */
}
.chart-canvas{
    height: 200px !important;
}

    </style>

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container py-4 pb-0">


    <div class="row">
        <div class="ms-3">
            <h3 class="mb-0 h4 font-weight-bolder">Dashboard</h3>
            <p class="mb-4">
                Check the sales, value and bounce rate.
            </p>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-2 ps-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-capitalize">Week's Money</p>
                            <h4 class="mb-0">RM{{$currentWeekTotal}}</h4> <!-- 初始值 -->
                        </div>
                        <div class="icon icon-md icon-shape bg-gradient-dark text-center border-radius-lg">
                            <i class="material-symbols-rounded opacity-10">weekend</i>
                        </div>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-2 ps-3">
                    <p class="mb-0 text-sm">
                        <span class="font-weight-bolder 
                                {{ $percentageChange >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $percentageChange >= 0 ? '+' : '' }}{{ number_format($percentageChange, 2) }}%
                        </span> than last week
                    </p>
                </div>

            </div>



        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-2 ps-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-capitalize">Total Users</p>
                            <h4 class="mb-0">{{$totalUser}}</h4>
                        </div>
                        <div class="icon icon-md icon-shape bg-gradient-dark text-center border-radius-lg">
                            <i class="material-symbols-rounded opacity-10">

                                person</i>
                        </div>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-2 ps-3">
                    <p class="mb-0 text-sm">
                        <span class="font-weight-bolder 
                                {{ $userGrowthPercentage >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $userGrowthPercentage >= 0 ? '+' : '' }}{{ number_format($userGrowthPercentage, 2) }}%
                        </span> than last month
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-2 ps-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-capitalize">Total Items</p>
                            <h4 class="mb-0">{{$totalItem}}</h4>
                        </div>
                        <div class="icon icon-md icon-shape bg-gradient-dark text-center border-radius-lg">
                            <i class="material-symbols-rounded opacity-10">leaderboard</i>
                        </div>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-2 ps-3">
                    <p class="mb-0 text-sm"> <span class="font-weight-bolder 
                            {{ $itemGrowthPercentage >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $itemGrowthPercentage >= 0 ? '+' : '' }}{{ number_format($itemGrowthPercentage, 2) }}%
                        </span>than last month
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-header p-2 ps-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-capitalize">Total Sales</p>
                            <h4 class="mb-0">RM{{$totalMoney}}</h4>
                        </div>
                        <div class="icon icon-md icon-shape bg-gradient-dark text-center border-radius-lg">
                            <i class="material-symbols-rounded opacity-10">weekend</i>
                        </div>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-2 ps-3">
                    <p class="mb-0 text-sm">
                        <span class="font-weight-bolder 
                                {{ $percentageMonthChange >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $percentageMonthChange >= 0 ? '+' : '' }}{{ number_format($percentageMonthChange, 2) }}%
                        </span> than last week
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-6 mt-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-0">Daily Sales</h6>
                    <p class="text-sm">Daily Performance</p>
                    <div class="pe-2">
                        <div class="chart">
                            <canvas id="chart-bars" class="chart-canvas"></canvas>
                        </div>
                    </div>
                    <hr class="dark horizontal">
                    <div class="d-flex">
                        <i class="material-symbols-rounded text-sm my-auto me-1">schedule</i>
                        <p class="mb-0 text-sm daily-last-updated">Updated Loading...</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mt-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-0">Weekly Sales</h6>
                    <p class="text-sm sales-percentage">(Loading...) increase in weekly sales.</p>
                    <div class="pe-2">
                        <div class="chart">
                            <canvas id="chart-line" class="chart-canvas" ></canvas>
                        </div>
                    </div>
                    <hr class="dark horizontal">
                    <div class="d-flex">
                        <i class="material-symbols-rounded text-sm my-auto me-1">schedule</i>
                        <p class="mb-0 text-sm weekly-last-updated">Updated Loading...</p>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="col-lg-4 mt-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-0">Monthly Sales</h6>
                    <p class="text-sm monthly-sales-percentage">(Loading...) increase in monthly sales.</p>
                    <div class="pe-2">
                        <div class="chart">
                            <canvas id="chart-line-tasks" class="chart-canvas"></canvas>
                        </div>
                    </div>
                    <hr class="dark horizontal">
                    <div class="d-flex">
                        <i class="material-symbols-rounded text-sm my-auto me-1">schedule</i>
                        <p class="mb-0 text-sm monthly-last-updated">Updated Loading...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
            <div class="card" style="padding:5px;">
            <!-- Title and Download Button -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Order Items</h2>
                <a href="{{ route('order-items.export') }}" class="btn btn-success">Export to Excel</a>
            </div>
    
            <!-- Order Items Table -->
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Order ID</th>
                        <th>Product ID</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderItems as $item)
                        <tr>
                            <td>{{ $item->id}}</td>
                            <td>{{ $item->user->name ?? 'N/A' }}</td> <!-- 显示用户名 -->                            <td>{{ $item->order_id }}</td>
                            <td>{{ $item->product_id }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->price }}</td>
                            <td>{{ $item->quantity * $item->price }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    
            <!-- Pagination Links -->
            <div class="d-flex justify-content-center">
                {{ $orderItems->links() }}
            </div>
        </div>
    </div>
    </div>
    
  

</div>

<!--   Core JS Files   -->
<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
<script src="../assets/js/plugins/chartjs.min.js"></script>
<script>
   document.addEventListener("DOMContentLoaded", function() {
    fetch('/adminDashboard/dailySales') // Adjusted endpoint for daily sales
        .then(response => response.json())
        .then(data => {
            var ctx = document.getElementById("chart-bars").getContext("2d");

            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: data.days, // Dynamically set the day labels (Sun, Mon, etc.)
                    datasets: [{
                        label: "Daily Sales",
                        tension: 0.4,
                        borderWidth: 0,
                        borderRadius: 4,
                        borderSkipped: false,
                        backgroundColor: "#43A047",
                        data: data.sales, // Daily sales data from the database
                        barThickness: 'flex'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    scales: {
                        y: {
                            grid: {
                                drawBorder: false,
                                display: true,
                                drawOnChartArea: true,
                                drawTicks: false,
                                borderDash: [5, 5],
                                color: '#e5e5e5'
                            },
                            ticks: {
                                suggestedMin: 0,
                                suggestedMax: 500,
                                beginAtZero: true,
                                padding: 10,
                                font: {
                                    size: 14,
                                    lineHeight: 2
                                },
                                color: "#737373"
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: false,
                                drawTicks: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                display: true,
                                color: '#737373',
                                padding: 10,
                                font: {
                                    size: 14,
                                    lineHeight: 2
                                }
                            }
                        }
                    }
                }
            });

            // Display last updated time
            const timeElement = document.querySelector('.daily-last-updated');
            timeElement.textContent = `Updated ${data.minutes_ago} min ago`;
        });
});


    document.addEventListener("DOMContentLoaded", function() {
        fetch('/adminDashboard/weeklySales') // Adjusted endpoint to fetch weekly sales
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('chart-line').getContext('2d');
                const chart = new Chart(ctx, {
                    type: 'line'
                    , data: {
                        labels: data.weeks, // Dynamically set week labels
                        datasets: [{
                            label: 'Total Sales'
                            , data: data.sales, // Weekly sales data from the database
                            borderColor: '#42a5f5'
                            , backgroundColor: 'rgba(66, 165, 245, 0.2)'
                            , fill: true
                        }]
                    }
                    , options: {
                        responsive: true
                        , plugins: {
                            legend: {
                                position: 'top'
                            , }
                        , }
                    , }
                });

                // Display percentage change in sales for each week
                const percentageElement = document.querySelector('.sales-percentage');
                let percentageText = '';
                data.percentage_changes.forEach((change, index) => {
                    percentageText += `WEEK ${index + 1}: ${change}% `;
                });
                percentageElement.textContent = `(${percentageText}) increase in weekly sales.`;

                // Display last updated time
                const timeElement = document.querySelector('.weekly-last-updated');
                timeElement.textContent = `Updated ${data.minutes_ago} min ago`;
            });
    });



    document.addEventListener("DOMContentLoaded", function() {
    fetch('/adminDashboard/monthlySales') // Adjusted endpoint for monthly sales
        .then(response => response.json())
        .then(data => {
            var ctx3 = document.getElementById("chart-line-tasks").getContext("2d");

            new Chart(ctx3, {
                type: "line",
                data: {
                    labels: data.months, // Dynamically set the month labels
                    datasets: [{
                        label: "Monthly Sales",
                        tension: 0,
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: "#43A047",
                        pointBorderColor: "transparent",
                        borderColor: "#43A047",
                        backgroundColor: "transparent",
                        fill: true,
                        data: data.sales, // Monthly sales data from the database
                        maxBarThickness: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    scales: {
                        y: {
                            grid: {
                                drawBorder: false,
                                display: true,
                                drawOnChartArea: true,
                                drawTicks: false,
                                borderDash: [4, 4],
                                color: '#e5e5e5'
                            },
                            ticks: {
                                display: true,
                                padding: 10,
                                color: '#737373',
                                font: {
                                    size: 14,
                                    lineHeight: 2
                                }
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: false,
                                drawTicks: false,
                                borderDash: [4, 4]
                            },
                            ticks: {
                                display: true,
                                color: '#737373',
                                padding: 10,
                                font: {
                                    size: 14,
                                    lineHeight: 2
                                }
                            }
                        }
                    }
                }
            });

            // Display last updated time
            const timeElement = document.querySelector('.monthly-last-updated');
            timeElement.textContent = `Updated ${data.minutes_ago} min ago`;
        });
});


</script>
<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }

</script>
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>


@endsection
