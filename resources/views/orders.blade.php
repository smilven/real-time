@extends('layouts.app')

@section('content')
<style>
    .status-placed {
    background-color: lightblue;
}
.status-shipped {
    background-color: lightyellow;
}
.status-delivered {
    background-color: lightgreen;
}

    </style>
<div class="container">
    <h1>My Orders</h1>

    @if ($orders->isEmpty())
        <p>You have no orders yet.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        <td id="status-{{ $order->id }}">{{ $order->status }}</td>
                        <td>${{ $order->items->sum(function($item) {
                            return $item->quantity * $item->price;
                        }) }}</td>
                        <td>
                            <button class="btn btn-info btn-sm" data-bs-toggle="collapse" data-bs-target="#order-{{ $order->id }}">
                                View Details
                            </button>
                        </td>
                    </tr>
                    <tr class="collapse" id="order-{{ $order->id }}">
                        <td colspan="5">
                            <h5>Order Process</h5>
                            <ul class="list-group mb-3">
                                <li class="list-group-item {{ $order->status === 'Placed' ? 'active' : '' }}">Placed</li>
                                <li class="list-group-item {{ $order->status === 'Shipped' ? 'active' : '' }}">Shipped</li>
                                <li class="list-group-item {{ $order->status === 'Delivered' ? 'active' : '' }}">Delivered</li>
                            </ul>

                            <h5>Order Items</h5>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td>{{ $item->product->productName }}</td>
                                            <td>${{ $item->price }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>${{ $item->price * $item->quantity }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
<script type="module">
    window.Echo.channel("orders")
     // Listen for the update-status event
     .listen(".update-status", (e) => {
         if (!@json(auth()->user()->is_admin)) {
             console.log(e);

             // Get the order ID and status from the event
             const orderId = e.order.id;
             const newStatus = e.order.status;

             // Find the status element in the main table
             const statusElement = document.getElementById("status-" + orderId);

             // Remove all possible status classes
             statusElement.classList.remove("status-placed", "status-shipped", "status-delivered");

             // Add the class corresponding to the new status
             if (newStatus === "Placed") {
                 statusElement.classList.add("status-placed");
             } else if (newStatus === "Shipped") {
                 statusElement.classList.add("status-shipped");
             } else if (newStatus === "Delivered") {
                 statusElement.classList.add("status-delivered");
             }

             // Optionally, update the text content as well
             statusElement.textContent = newStatus;

             // Find the order process steps in the collapsed details
             const processSteps = document.querySelectorAll(`#order-${orderId} .list-group-item`);

             // Remove active class from all steps
             processSteps.forEach(step => step.classList.remove("active"));

             // Add active class to the relevant status
             processSteps.forEach(step => {
                 if (step.textContent === newStatus) {
                     step.classList.add("active");
                 }
             });

             // Optionally, update the background color in the collapsed section
             const orderProcess = document.querySelector(`#order-${orderId} .list-group`);
             orderProcess.querySelectorAll(".list-group-item").forEach(item => {
                 item.classList.remove("status-placed", "status-shipped", "status-delivered");
                 if (newStatus === "Placed" && item.textContent === "Placed") {
                     item.classList.add("status-placed");
                 } else if (newStatus === "Shipped" && item.textContent === "Shipped") {
                     item.classList.add("status-shipped");
                 } else if (newStatus === "Delivered" && item.textContent === "Delivered") {
                     item.classList.add("status-delivered");
                 }
             });
         }
     });
</script>

 

 @endsection
