@extends('layouts.app', ['page' => __('dashboard')])

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Dashboard</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-6">
                    <div class="small-box bg-info dashboard">
                        <div class="inner">
                            <h3>{{ number_format(count($users)) }}</h3>
                            <p>User Registrations</p>
                        </div>
                        <div class="icon">
                            <i class="nav-icon fas"><img src="/images/icon/user.svg" alt="icon_user" width="70px"/></i>
                        </div>
                        <a href="{{ route('users.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

{{--                <div class="col-lg-6 col-6">--}}
{{--                    <div class="small-box bg-success dashboard">--}}
{{--                        <div class="inner">--}}
{{--                            <h3>3,578</h3>--}}
{{--                            <p>Skin Test</p>--}}
{{--                        </div>--}}
{{--                        <div class="icon">--}}
{{--                            <i class="nav-icon fas"><img src="/images/icon/skin_test.svg" alt="icon_user" width="70px"/></i>--}}
{{--                        </div>--}}
{{--                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
{{--                    </div>--}}
{{--                </div>--}}

            </div>
{{--            <div class="row">--}}
{{--                <div class="col-lg-4 col-3">--}}
{{--                    <div class="small-box bg-warning dashboard">--}}
{{--                        <div class="inner">--}}
{{--                            <h3>567</h3>--}}
{{--                            <p>Products</p>--}}
{{--                        </div>--}}
{{--                        <div class="icon">--}}
{{--                            <i class="nav-icon fas"><img src="/images/icon/product.svg" alt="icon_user" width="70px"/></i>--}}
{{--                        </div>--}}
{{--                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="col-lg-4 col-3">--}}
{{--                    <div class="small-box bg-danger dashboard">--}}
{{--                        <div class="inner">--}}
{{--                            <h3>2,568</h3>--}}
{{--                            <p>Ingredients</p>--}}
{{--                        </div>--}}
{{--                        <div class="icon">--}}
{{--                            <i class="nav-icon fas"><img src="/images/icon/ingredient.svg" alt="icon_user" width="70px"/></i>--}}
{{--                        </div>--}}
{{--                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="col-lg-4 col-3">--}}
{{--                    <div class="small-box bg-secondary dashboard">--}}
{{--                        <div class="inner">--}}
{{--                            <h3>45</h3>--}}
{{--                            <p>Tips</p>--}}
{{--                        </div>--}}
{{--                        <div class="icon">--}}
{{--                            <i class="nav-icon fas"><img src="/images/icon/tip.svg" alt="icon_user" width="70px"/></i>--}}
{{--                        </div>--}}
{{--                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>

    </section>

@endsection
