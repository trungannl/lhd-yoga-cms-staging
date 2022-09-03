@extends('layouts.app', ['page' => __('studios')])

@push('css_lib')
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endpush

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <a href="{{ route('studios.index') }}">
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
            <div class="row mb-2 p-3">
                <div class="col-md-6">
                    <div class="row">
                        <div class="float-left pl-3">
                            <h1>{{ $studio->name }}</h1>
                        </div>
                        <div class="float-left p-2">
                            <span class="badge {{ $studio->getDisplayStatus()['color'] }}">{{ $studio->getDisplayStatus()['text'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <div class="float-sm-right">
                        @if ($studio->status !== $studio::CLOSE)
                            {!! Form::open(['route' => ['studios.cancel', $studio->id], 'method' => 'post']) !!}
                                {!! Form::button('<i class="fas fa fa-ban"></i>', [
                                    'type' => 'submit',
                                    'class' => 'btn btn-app btn_custom',
                                    'onclick' => "return confirm('Are you sure?')"
                                ]) !!}
                            {!! Form::close() !!}
                        @endif
                    </div>
                    <div class="float-sm-right">
                        @if ($studio->status !== $studio::OPEN)
                            {!! Form::open(['route' => ['studios.active', $studio->id], 'method' => 'post']) !!}
                                {!! Form::button('<i class="fas fa fa-unlock"></i>', [
                                    'type' => 'submit',
                                    'class' => 'btn btn-app btn_custom',
                                    'onclick' => "return confirm('Are you sure?')"
                                ]) !!}
                            {!! Form::close() !!}
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card_backgroud">
                        <div class="card-header border-0">
                            <h3 class="card-title">Studio Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Display Name</label>
                                        <div class="form-control border-0">
                                            {{ $studio->name }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <div class="form-control border-0">
                                            {{ $studio->address }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Image</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <img class="rounded img-thumbnail" src=" @if ($studio->hasMedia('image')) {{ $studio->getFirstMediaUrl('image') }}@else /images/avatar_default.png @endif" alt="package image">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <div class="form-control border-0">
                                            {{ $studio->description }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts_lib')
    <script type="text/javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"></script>
@endpush