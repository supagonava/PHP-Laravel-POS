@extends('layouts.admin')

@section('title', 'ออเดอร์ทั้งหมด')
@section('content-header', 'ออเดอร์ทั้งหมด')
@section('content-actions')
    <a href="{{ route('cart.index') }}" class="btn btn-primary">Open POS</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('orders.index') }}">
                <div class="row">
                    <div class="col-md-3 col-6 mb-1 mr-1">
                        <input type="date" name="start_date" class="form-control"
                            value="{{ request('start_date', date('Y-m-d')) }}" />
                    </div>
                    <div class="col-md-3 col-6 mb-1 mr-1">
                        <input type="date" name="end_date" class="form-control"
                            value="{{ request('end_date', date('Y-m-d')) }}" />
                    </div>
                    <div class="col-md-3 col-6 mb-1 mr-1">
                        <input type="text" name="cashier_name" class="form-control" value="{{ request('cashier_name') }}"
                            placeholder="Cashier Name" />
                    </div>
                </div>
                <div class="w-100 d-flex justify-content-end">
                    <button class="btn btn-outline-primary mr-2" type="submit">ค้นหา</button>
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">Reset</a>
                </div>
            </form>
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $ordersCount }}</h3>
                            <p>ยอดออเดอร์</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        {{-- <a href="{{ route('orders.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ config('settings.currency_symbol') }} {{ number_format($income, 2) }}</h3>
                            <p>ยอดขาย</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        {{-- <a href="{{ route('orders.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
                    </div>
                </div>
            </div>
            <div class="table-responsive mt-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>พนักงานขาย</th>
                            <th>ลูกค้า</th>
                            <th>ยอดขาย</th>
                            <th>สถานะ</th>
                            <th>รับเงิน</th>
                            <th>ทอนเงิน</th>
                            <th>เมื่อเวลา</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $order->cashier->getFullname() }}</td>
                                <td>{{ $order->getCustomerName() }}</td>
                                <td>

                                    <!-- Button trigger modal -->
                                    <a href="#" data-toggle="modal" data-target="#order-items-{{ $order->id }}">
                                        {{ config('settings.currency_symbol') }} {{ $order->formattedTotal() }}
                                    </a>

                                    <!-- Modal -->
                                    <div class="modal fade" id="order-items-{{ $order->id }}" tabindex="-1"
                                        role="dialog" data-backdrop="static" aria-labelledby="modelTitleId"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">รายการที่ขาย</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>สินค้า</th>
                                                                    <th>ราคา</th>
                                                                    <th>จำนวน</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($order->items as $item)
                                                                    <tr>
                                                                        <td>{{ $loop->iteration }}</td>
                                                                        <td>{{ $item->product->name }}</td>
                                                                        <td>{{ $item->product->price }}</td>
                                                                        <td>{{ $item->quantity }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
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
