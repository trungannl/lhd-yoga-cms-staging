@extends('layouts.app', ['page' => __('staffs')])

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Staffs</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <div class="float-sm-right">
                        <a href="{{ route('staffs.create') }}" class="btn btn-block btn-outline-danger">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                            ADD STAFF
                        </a>
                    </div>

                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            @include('flash::message')
            @include('staffs.table')
        </div>

    </section>

@endsection
