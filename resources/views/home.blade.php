@extends('layouts.admin')

@section('content-header', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <form class="row mb-2">
            <div class="col-5 g-1">
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date', date('Y-m-d')) }}" />
            </div>
            <div class="col-5 g-1">
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date', date('Y-m-d')) }}" />
            </div>
            <div class="col-2 g-1">
                <button class="btn btn-outline-primary" type="submit">Submit</button>
            </div>
        </form>
        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $orders_count }}</h3>
                        <p>Orders Count</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    {{-- <a href="{{ route('orders.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a> --}}
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ config('settings.currency_symbol') }} {{ number_format($income, 2) }}</h3>
                        <p>Income</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    {{-- <a href="{{ route('orders.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
