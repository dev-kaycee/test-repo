@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create New Asset</h2>
        <a href="{{ route('tenant.assets.index') }}" class="btn btn-secondary mb-3">Back to Assets</a>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tenant.assets.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label for="serial_number">Serial Number</label>
                <input type="text" class="form-control" id="serial_number" name="serial_number"
                       value="{{ old('serial_number') }}" required>
            </div>
            <div class="form-group">
                <label for="model">Model</label>
                <input type="text" class="form-control" id="model" name="model" value="{{ old('model') }}" required>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="In use" {{ old('status') == 'In use' ? 'selected' : '' }}>In use</option>
                            <option value="Not in use" {{ old('status') == 'Not in use' ? 'selected' : '' }}>Not in
                                use
                            </option>
                            <option value="In service" {{ old('status') == 'In service' ? 'selected' : '' }}>In
                                service
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="asset_type_id">Asset Type</label>
                        <select class="form-control" id="asset_type_id" name="asset_type_id" required>
                            <option value="">Select Asset Type</option>
                            @foreach($assetTypes as $assetType)
                                <option value="{{ $assetType->id }}" {{ old('asset_type_id') == $assetType->id ? 'selected' : '' }}>
                                    {{ $assetType->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="cost">Cost</label>
                <input type="number" step="0.01" class="form-control" id="cost" name="cost" value="{{ old('cost') }}"
                       required>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}"
                       required>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="purchase_date">Purchase Date</label>
                        <input type="date" class="form-control" id="purchase_date" name="purchase_date"
                               value="{{ old('purchase_date') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="warranty_date">Warranty Date</label>
                        <input type="date" class="form-control" id="warranty_date" name="warranty_date"
                               value="{{ old('warranty_date') }}" required>
                    </div>
                </div>
            </div>


            <button type="submit" class="btn btn-primary">Create Asset</button>
        </form>
    </div>
@endsection