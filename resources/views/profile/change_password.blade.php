@extends('layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <a href="{{ route('profile.index') }}">
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
            {!! Form::open(['route' => 'profile.change']) !!}
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Change Password</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Current password</label>
                                        {!! Form::input('password', 'current_password', null,  $errors->has('current_password') ? ['class' => 'form-control is-invalid'] : ['class' => 'form-control']) !!}
                                        @error('current_password')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>New password</label>
                                        {!! Form::input('password', 'new_password', null,  $errors->has('new_password') ? ['class' => 'form-control is-invalid'] : ['class' => 'form-control']) !!}
                                        @error('new_password')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Confirm password</label>
                                        {!! Form::input('password', 'confirm_password', null,  $errors->has('confirm_password') ? ['class' => 'form-control is-invalid'] : ['class' => 'form-control']) !!}
                                        @error('confirm_password')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        @include('flash::message')
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Change</button>
                        </div>
                    </div>

                </div>
            </div>

            </div>
            {!! Form::close() !!}

        </div>
    </section>
@endsection
