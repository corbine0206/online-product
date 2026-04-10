@extends('admin.layout')

@section('title', 'Permission Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Permission Details</h5>
                    <div>
                        <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('permissions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Permission Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td>{{ $permission->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $permission->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Description:</strong></td>
                                    <td>{{ $permission->description ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $permission->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Updated:</strong></td>
                                    <td>{{ $permission->updated_at->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Usage Summary</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Assigned to Roles:</strong></td>
                                    <td>{{ $permission->roles->count() }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <h6>Assigned to Roles</h6>
                    @if($permission->roles->count() > 0)
                        <div class="row">
                            @foreach($permission->roles as $role)
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $role->name }}</h6>
                                            @if($role->description)
                                                <p class="card-text text-muted small">{{ $role->description }}</p>
                                            @endif>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">This permission is not assigned to any roles.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
