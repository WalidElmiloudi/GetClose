@extends('emails.layout')

@section('email-content')
<h2>New Order Received!</h2>
<p>Hello,</p>
<p>You have received a new order #{{ $order->id }} from {{ $order->client->name }}.</p>

<div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;">
    <h3 style="margin-top: 0;">Order Details</h3>
    <p><strong>Order Number:</strong> #{{ $order->id }}</p>
    <p><strong>Customer:</strong> {{ $order->client->name }}</p>
    <p><strong>Total Amount:</strong> ${{ number_format($order->total_price, 2) }}</p>
    <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
</div>

<h3>Items Ordered:</h3>
<ul style="list-style: none; padding: 0;">
    @foreach($order->items as $item)
    <li style="padding: 10px; border-bottom: 1px solid #ddd;">
        <strong>{{ $item->product->name }}</strong><br>
        Quantity: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}
    </li>
    @endforeach
</ul>

<a href="{{ url('/vendor/orders/' . $order->id) }}" class="button">View Order Details</a>

<p>Please process this order at your earliest convenience.</p>
@endsection