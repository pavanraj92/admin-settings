@extends('admin::admin.layouts.master')

@section('title', 'Settings Management')

@section('page-title', isset($setting) ?  'Edit Setting' : 'Create Setting')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-curren
    t="page"><a href="{{ route('admin.settings.index') }}">Setting Manager</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{isset($setting) ?  'Edit Setting' : 'Create Setting'}}</li>
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
                                    <input
                                        type="text"
                                        name="title"
                                        class="form-control alphabets-only"
                                        value="{{ $setting?->title ?? old('title') }}"
                                        required
                                        @if(isset($setting)) disabled @endif
                                    >
                                    @if(isset($setting))
                                        <input type="hidden" name="title" value="{{ $setting->title }}">
                                    @endif
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
                            <button type="submit" class="btn btn-primary" id="saveBtn">{{isset($setting) ? 'Update' : 'Save'}}</button>
                            <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End setting Content -->
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Select2 for any select elements with the class 'select2'
            $('.select2').select2();

            $.validator.addMethod(
                "alphabetsOnly",
                function (value, element) {
                    return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
                },
                "Please enter letters only"
            );

            //jquery validation for the form
            $('#settingForm').validate({
                ignore: [],
                rules: {
                    title: {
                        required: true,
                        minlength: 3,
                        alphabetsOnly: true
                    },
                    config_value: {
                        required: true,
                    }
                },
                messages: {
                    title: {
                        required: "Please enter a title",
                        minlength: "Title must be at least 3 characters long"
                    },
                    config_value: {
                        required: "Please enter config value",
                    }
                },
                submitHandler: function(form) {
                    const $btn = $('#saveBtn');
                    if ($btn.text().trim().toLowerCase() === 'update') {
                        $btn.prop('disabled', true).text('Updating...');
                    } else {
                        $btn.prop('disabled', true).text('Saving...');
                    }
                    form.submit();
                },
                errorElement: 'div',
                errorClass: 'text-danger custom-error',
                errorPlacement: function(error, element) {
                    $('.validation-error').hide(); // hide blade errors
                    error.insertAfter(element);
                }
            });
        });
    </script>
@endpush
