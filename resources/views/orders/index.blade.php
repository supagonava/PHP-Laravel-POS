@extends('layouts.admin')

@section('title', 'Orders List')
@section('content-header', 'Order List')
@section('content-actions')
<a href="{{ route('cart.index') }}" class="btn btn-primary">Open POS</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('orders.index') }}">
            <div class="row">
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-3 mb-1 mr-1">
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" />
                        </div>
                        <div class="col-md-3 mb-1 mr-1">
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" />
                        </div>
                        <div class="col-md-6 mb-1 mr-1">
                            <input type="text" name="cashier_name" class="form-control" value="{{ request('cashier_name') }}" placeholder="Cashier Name" />
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-primary" type="submit">Filter</button>
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">Reset</a>
                </div>
            </div>
        </form>
        <div class="table-responsive mt-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>พนักงานขาย</th>
                        <th>Customer Name</th>
                        <th>Total</th>
                        <th>Received Amount</th>
                        <th>Status</th>
                        <th>To Pay</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->cashier->getFullname() }}</td>
                        <td>{{ $order->getCustomerName() }}</td>
                        <td>{{ config('settings.currency_symbol') }} {{ $order->formattedTotal() }}</td>
                        <td>{{ config('settings.currency_symbol') }} {{ $order->formattedReceivedAmount() }}</td>
                        <td>
                            @if ($order->receivedAmount() == 0)
                            <span class="badge badge-danger">Not Paid</span>
                            @elseif($order->receivedAmount() < $order->total())
                                <span class="badge badge-warning">Partial</span>
                                @elseif($order->receivedAmount() == $order->total())
                                <span class="badge badge-success">Paid</span>
                                @elseif($order->receivedAmount() > $order->total())
                                <span class="badge badge-info">Change</span>
                                @endif
                        </td>
                        <td>{{ config('settings.currency_symbol') }}
                            {{ number_format($order->total() - $order->receivedAmount(), 2) }}
                        </td>
                        <td>{{ $order->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>{{ config('settings.currency_symbol') }} {{ number_format($total, 2) }}</th>
                        <th>{{ config('settings.currency_symbol') }} {{ number_format($receivedAmount, 2) }}</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        {{ $orders->appends(request()->query())->render() }}
    </div>
</div>
@endsection
