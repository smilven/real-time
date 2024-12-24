@extends('layouts.app')

@section('content')
<div class="container py-4 pb-0">
    <h1>My Cart</h1>
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
            @foreach ($cartItems as $item)
                <tr>
                    <td>{{ $item->product->productName }}</td>
                    <td>{{ $item->product->productPrice }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->product->productPrice * $item->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        <h4>Total: 
            ${{ $cartItems->sum(function ($item) {
                return $item->product->productPrice * $item->quantity;
            }) }}
        </h4>
    </div>

    <div class="mt-4">
        <form action="{{ route('place.order') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">Place Order</button>
        </form>
    </div>
</div>
@endsection
