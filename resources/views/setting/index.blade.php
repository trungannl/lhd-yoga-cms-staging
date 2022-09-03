@extends('layouts.app', ['page' => __('settings')])

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Settings</h1>
                </div><!-- /.col -->

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            <div class="col-md-12 row">
                <a href="{{ route('roles.index') }}" class="card setting_item">
                    <i class="nav-icon fas"><img src="/images/icon/role.svg" alt="icon_dashboard"></i>
                    <p>Role Manager</p>
                </a>
            </div>

        </div>
    </section>
@endsection
