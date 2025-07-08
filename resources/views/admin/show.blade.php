@extends('admin::admin.layouts.master')

@section('title', 'Settings Management')

@section('page-title', 'Setting Details')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.settings.index') }}">Manage Settings</a></li>
    <li class="breadcrumb-item active" aria-current="page">Setting Details</li>
@endsection

@section('content')
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Setting Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">                    
                    <div class="table-responsive">
                         <div class="card-body">      
                            <table class="table table-responsive-lg table-no-border">
                                <tbody>
                                    <tr>
                                        <th scope="row">Title</th>
                                        <td scope="col">{{ $setting->title ?? 'N/A' }}</td>
                                    </tr>         
                                    <tr>
                                        <th scope="row">Slug</th>
                                        <td scope="col">{{ $setting->slug ?? 'N/A' }}</td>
                                    </tr>         
                                    <tr>
                                        <th scope="row">Config Value</th>
                                        <td scope="col">{{ $setting->config_value ?? 'N/A' }}</td>
                                    </tr>                                
                                    <tr>
                                        <th scope="row">Created At</th>
                                        <td scope="col">{{ $setting->created_at
                                            ? $setting->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s')
                                            : '—' }}</td>
                                    </tr>                                
                                </tbody>
                            </table>   
                                             
                            <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">Back</a> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Setting Content -->
    </div>
    <!-- End Container fluid  -->
@endsection
