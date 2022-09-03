
@extends('layouts.app', ['page' => __('staffs')])

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <a href="{{ route('staffs.index') }}">
                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        Back
                    </a>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">

            {!! Form::open(['route' => 'staffs.store']) !!}
            <div class="row">
                @include("staffs.fields")
            </div>

            @include('flash::message')
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>

            {!! Form::close() !!}

        </div>

    </section>

@endsection


