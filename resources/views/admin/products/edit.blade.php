@extends('layouts.admin')

@section('title', 'Edit Product')

@section('header', 'Edit Product')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Edit Product</h1>
                        <p class="text-sm text-gray-600 mt-1">Update product information and images</p>
                    </div>
                    <a href="{{ route('admin.products.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Products
                    </a>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3 text-green-500"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle mr-3 text-red-500 mt-0.5"></i>
                        <div>
                            <p class="font-bold">Please fix the following errors:</p>
                            <ul class="list-disc list-inside mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Update Form -->
            <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Content - Left Column (2/3) -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Basic Information Card -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                                    Basic Information
                                </h3>
                            </div>
                            <div class="p-6 space-y-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Product Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name', $product->name) }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                        placeholder="Enter product name" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Description
                                    </label>
                                    <textarea name="description" rows="6"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                        placeholder="Enter product description">{{ old('description', $product->description) }}</textarea>
                                    <p class="mt-1 text-xs text-gray-500">Detailed description of the product</p>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing & Status Card -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    <i class="fas fa-tag mr-2 text-blue-500"></i>
                                    Pricing & Status
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Price <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                            <input type="number" name="price" value="{{ old('price', $product->price) }}"
                                                step="0.01"
                                                class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                placeholder="0.00" required>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Status <span class="text-red-500">*</span>
                                        </label>
                                        <select name="status"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>🟢 Active</option>
                                            <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>🔴 Inactive</option>
                                            <option value="out_of_stock" {{ old('status', $product->status) == 'out_of_stock' ? 'selected' : '' }}>🟡 Out of Stock</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar - Right Column (1/3) -->
                    <div class="space-y-6">
                        <!-- Categories Card -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    <i class="fas fa-folder mr-2 text-blue-500"></i>
                                    Categories
                                </h3>
                            </div>
                            <div class="p-6 space-y-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Brand <span class="text-red-500">*</span>
                                    </label>
                                    <select name="brand_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required>
                                        <option value="">Select Brand</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Product Type <span class="text-red-500">*</span>
                                    </label>
                                    <select name="product_type_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required>
                                        <option value="">Select Product Type</option>
                                        @foreach ($productTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('product_type_id', $product->product_type_id) == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Images Card -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    <i class="fas fa-image mr-2 text-blue-500"></i>
                                    Product Images
                                </h3>
                            </div>
                            <div class="p-6">
                                <!-- Add New Images Input Area -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Add New Images
                                    </label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-500 transition-colors">
                                        <div class="space-y-1 text-center">
                                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400"></i>
                                            <div class="flex text-sm text-gray-600">
                                                <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                                    <span>Choose files</span>
                                                    <input type="file" name="images[]" class="sr-only" multiple accept="image/*" onchange="updateFileList(this)">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                        </div>
                                    </div>
                                    <div id="fileList" class="mt-2 text-sm text-gray-600"></div>
                                </div>

                                <!-- Current Configured Gallery Grid -->
                                @if ($product->images->count() > 0)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-3">
                                            Current Images ({{ $product->images->count() }})
                                        </label>
                                        <div class="grid grid-cols-2 gap-3">
                                            @foreach ($product->images as $image)
                                                <div class="relative group" id="image-container-{{ $image->id }}">
                                                    <img src="{{ Storage::url($image->image_path) }}"
                                                        class="w-full h-28 object-cover rounded-lg shadow-sm">
                                                    
                                                    <!-- Safe Standard Action Button (No Form Nesting) -->
                                                    <button type="button"
                                                        class="delete-image-btn absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600 focus:outline-none shadow-md"
                                                        data-image-id="{{ $image->id }}"
                                                        data-url="{{ route('admin.products.delete-image', $image->id) }}">
                                                        <i class="fas fa-times text-xs"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Form Actions -->
                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('admin.products.index') }}"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200 shadow-sm">
                        <i class="fas fa-save mr-2"></i>Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateFileList(input) {
            const fileList = document.getElementById('fileList');
            if (input.files.length > 0) {
                let html = '<div class="mt-2 p-2 bg-blue-50 rounded-lg"><p class="font-medium text-blue-700">Selected files:</p><ul class="list-disc list-inside text-sm text-blue-600">';
                for (let i = 0; i < input.files.length; i++) {
                    html += `<li>${input.files[i].name}</li>`;
                }
                html += '</ul></div>';
                fileList.innerHTML = html;
            } else {
                fileList.innerHTML = '';
            }
        }

        // Handle Image Deletions Safely out of Main form context
        document.querySelectorAll('.delete-image-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation(); // Block bubbling context up to product submission

                const imageId = this.getAttribute('data-image-id');
                const actionUrl = this.getAttribute('data-url');

                if (confirm('Are you sure you want to delete this specific image?')) {
                    console.log(`Executing isolated request route for Image ID: ${imageId}`);

                    // Generate temporary virtual form at safe document scope root level
                    const hiddenForm = document.createElement('form');
                    hiddenForm.method = 'POST';
                    hiddenForm.action = actionUrl;

                    // Laravel Context CSRF Data Configuration Token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    // REST Method Spoof override directive parameter
                    const methodOverride = document.createElement('input');
                    methodOverride.type = 'hidden';
                    methodOverride.name = '_method';
                    methodOverride.value = 'DELETE';

                    hiddenForm.appendChild(csrfToken);
                    hiddenForm.appendChild(methodOverride);

                    document.body.appendChild(hiddenForm);
                    hiddenForm.submit();
                }
            });
        });
    </script>
@endsection