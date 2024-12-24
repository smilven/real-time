@extends('layouts.app')

@section('content')
<div class="container py-4 pb-0">
    <h1>Manage Orders</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Status</th>
                <th>Update Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td id="status-{{ $order->id }}">{{ $order->status }}</td>
                    <td>
                        <select class="form-select update-status" data-order-id="{{ $order->id }}">
                            <option value="Placed" {{ $order->status == 'Placed' ? 'selected' : '' }}>Placed</option>
                            <option value="Shipped" {{ $order->status == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="Delivered" {{ $order->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                        </select>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script type="module">
    document.querySelectorAll('.update-status').forEach(select => {
        select.addEventListener('change', function () {
            const orderId = this.dataset.orderId;
            const status = this.value;

            fetch(`/admin/orders/${orderId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Status updated!');
                    // Update the status on the page without refreshing
                    document.getElementById(`status-${orderId}`).textContent = status;
                } else {
                    alert('Failed to update status.');
                }
            });
        });
    });

    window.Echo.channel("orders")
    // Listen for order status update event
    .listen(".update-status", (e) => {
        // Update the status for all users, not just admins
        const orderId = e.order.id;
        const status = e.order.status;
        const statusElement = document.getElementById(`status-${orderId}`);

        if (statusElement) {
            statusElement.textContent = status;
        }
    });
</script>
@endsection
