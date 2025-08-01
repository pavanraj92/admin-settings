@extends('admin::admin.layouts.master')

@section('title', 'Logo/Fav Management')
@section('page-title', 'Logo/Fav Details')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Logo/Fav Manager</li>
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
                        <form method="post" enctype="multipart/form-data" action="{{ route('admin.settings.storeLogos') }}" id="logo-setting-form">
                            @csrf    
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>S.NO</th>
                                        <th>Slug</th>
                                        <th>Current Image</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($settings as $k => $setting)
                                        <tr>
                                            <td>{{ $loop->iteration }}.</td>
                                            <td>
                                                <input type="text" name="setting[{{ $k }}][slug]" class="form-control" value="{{ old("setting.$k.slug", $setting->slug) }}" readonly>
                                                <input type="hidden" name="setting[{{ $k }}][id]" value="{{ $setting->id }}">
                                            </td>
                                            <td>
                                                @if (!empty($setting->config_value))
                                                    <div class="mb-2">
                                                        <img src="{{ asset('storage/'.$setting->config_value) }}" width="150" height="150" style="object-fit:contain;"  class="preview-img"  id="preview-{{ $k }}" />
                                                    </div>
                                                @endif
                                                <input type="file" name="setting[{{ $k }}][config_value]" accept="image/*" onchange="previewImage(event, {{ $k }})"/>
                                                @error("setting.$k.config_value")
                                                    <span class="text-danger d-block">{{ $message }}</span>
                                                @enderror
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button class="btn btn-primary btn-flat" type="submit"><i class="fa fa-save"></i> Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Setting Content -->
</div>
<!-- End Container fluid  -->
@endsection
@push('scripts')
<script>
    function previewImage(event, index) {
        const input = event.target;
        const preview = document.getElementById(`preview-${index}`);
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                if (preview) {
                    preview.src = e.target.result;
                }
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
