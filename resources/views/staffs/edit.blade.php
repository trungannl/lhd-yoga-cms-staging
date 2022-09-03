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
            @include('flash::message')
            {!! Form::model($staff, ['route' => ['staffs.update', $staff->id], 'method' => 'put']) !!}
            <div class="row mb-2 p-3">
                <div class="col-md-6">
                    <div class="row">
                        <div class="float-left">
                            <img class="profile-user-img img-fluid img-circle" src=" @if ($staff->hasMedia('avatar')) {{ $staff->getFirstMediaUrl('avatar') }}@else /images/avatar_default.png @endif" alt="staff avatar">
                        </div>
                        <div class="float-left pl-3">
                            <h1>{{ $staff->name }}</h1>
                        </div>
                        <div class="float-left p-2">
                            @if ($staff->active)
                                <span class='badge badge-success'>Active</span>
                            @else
                                <span class='badge badge-danger'>Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <a class="btn btn-app btn_custom" href="{{ route('staffs.show', $staff->id) }}">
                        <i class="fas fa-times" style="color:red;"></i>
                    </a>
                    <button class="btn btn-app btn_custom" type="submit">
                        <i class="fas fa-check" style="color:green;"></i>
                    </button>
                </div>
            </div>
            <div class="row">
                @include("staffs.fields")

            </div>
            {!! Form::close() !!}

        </div>
    </section>
@endsection
