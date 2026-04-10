@extends('admin.layout')

@section('title', 'Role Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Role Details</h5>
                    <div>
                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Role Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td>{{ $role->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $role->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Description:</strong></td>
                                    <td>{{ $role->description ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $role->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Updated:</strong></td>
                                    <td>{{ $role->updated_at->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Permissions Summary</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Total Permissions:</strong></td>
                                    <td>{{ $role->permissions->count() }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <h6>Assigned Permissions</h6>
                    @if($role->permissions->count() > 0)
                        <div class="row">
                            @foreach($role->permissions as $permission)
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $permission->name }}</h6>
                                            @if($permission->description)
                                                <p class="card-text text-muted small">{{ $permission->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No permissions assigned to this role.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
