@extends('admin::admin.layouts.master')

@section('title', 'Settings Management')

@section('page-title', 'Create Setting')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.settings.index') }}">Manage Settings</a></li>
    <li class="breadcrumb-item active" aria-current="page">Create Setting</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Start Setting Content -->
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <form action="{{ isset($setting) ? route('admin.settings.update', $setting->id) : route('admin.settings.store') }}"
                        method="POST" id="settingForm">
                        @if (isset($setting))
                            @method('PUT')
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-md-12">                                
                                <div class="form-group">
                                    <label>Title<span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control"
                                        value="{{ $setting?->title ?? old('title') }}" required>
                                    @error('title')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Config Value<span class="text-danger">*</span></label>
                            <textarea name="config_value" id="config_value" class="form-control config_value-editor">{{ $setting?->config_value ?? old('config_value') }}</textarea>
                            @error('config_value')
                                <div class="text-danger validation-error">{{ $message }}</div>
                            @enderror
                        </div>
                       
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End setting Content -->
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.css">
    <!-- Select2 CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Custom CSS for the setting -->
    <link rel="stylesheet" href="{{ asset('backend/custom.css') }}">           
@endpush

@push('scripts')
    <!-- Then the jQuery Validation plugin -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <!-- Include the CKEditor script -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
    <!-- Select2 CSS & JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for any select elements with the class 'select2'
            $('.select2').select2();

            //jquery validation for the form
            $('#settingForm').validate({
                ignore: [],
                rules: {
                    title: {
                        required: true,
                        minlength: 3
                    },
                    config_value: {
                        required: true,
                        minlength: 3
                    }
                },
                messages: {
                    title: {
                        required: "Please enter a title",
                        minlength: "Title must be at least 3 characters long"
                    },
                    config_value: {
                        required: "Please enter config value",
                        minlength: "Config value must be at least 3 characters long"
                    }
                },
                errorElement: 'div',
                errorClass: 'text-danger custom-error',
            });
        });
    </script>
@endpush
