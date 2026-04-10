@extends('admin.layout')

@section('title', 'Edit Product')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Edit Product</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Product Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sku" class="form-label">SKU</label>
                                    <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                           id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
                                    @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price ($)</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01" required>
                                    @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Stock</label>
                                    <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                           id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>
                                    @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <input type="text" class="form-control @error('category') is-invalid @enderror" 
                                           id="category" name="category" value="{{ old('category', $product->category) }}">
                                    @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Existing Images -->
                        @if($product->images->isNotEmpty())
                        <div class="mb-4">
                            <label class="form-label">Current Images</label>
                            <div class="row">
                                @foreach($product->images->sortBy('sort_order')->sortBy('id') as $image)
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <img src="{{ $image->image_url }}" class="card-img-top" style="height: 150px; object-fit: cover;" alt="{{ $image->image_name }}">
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">{{ strlen($image->image_name) > 15 ? substr($image->image_name, 0, 15) . '...' : $image->image_name }}</small>
                                                <div class="btn-group btn-group-sm">
                                                    @if(!$image->is_primary)
                                                    <button type="button" class="btn btn-outline-primary btn-sm" title="Set as primary" 
                                                            onclick="setPrimaryImage({{ $image->id }})">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                    @else
                                                    <span class="badge bg-primary" title="Primary image">
                                                        <i class="fas fa-star"></i>
                                                    </span>
                                                    @endif
                                                    <button type="button" class="btn btn-outline-danger btn-sm" title="Delete"
                                                            onclick="deleteImage({{ $image->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label for="images" class="form-label">Add More Images</label>
                            <input type="file" class="form-control @error('images') is-invalid @enderror" 
                                   id="images" name="images[]" multiple accept="image/*">
                            <div class="form-text">Upload up to 5 images (JPEG, PNG, JPG, GIF). Maximum 2MB per image.</div>
                            @error('images')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image_url" class="form-label">Or Image URL</label>
                            <input type="url" class="form-control @error('image_url') is-invalid @enderror" 
                                   id="image_url" name="image_url" value="{{ old('image_url', $product->image_url) }}" placeholder="https://example.com/image.jpg">
                            <div class="form-text">Alternative: Provide image URL instead of uploading files.</div>
                            @error('image_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" 
                                       name="is_active" value="1" @if(old('is_active', $product->is_active)) checked @endif>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save"></i> Update Product
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden forms for image management -->
<form id="setPrimaryForm" method="POST" style="display: none;">
    @csrf
    @method('PUT')
</form>

<form id="deleteImageForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function setPrimaryImage(imageId) {
    if (confirm('Are you sure you want to set this as the primary image?')) {
        const form = document.getElementById('setPrimaryForm');
        form.action = '{{ route('admin.product-images.set-primary', ':imageId') }}'.replace(':imageId', imageId);
        form.submit();
    }
}

function deleteImage(imageId) {
    if (confirm('Are you sure you want to delete this image?')) {
        const form = document.getElementById('deleteImageForm');
        form.action = '{{ route('admin.product-images.delete', ':imageId') }}'.replace(':imageId', imageId);
        form.submit();
    }
}
</script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
