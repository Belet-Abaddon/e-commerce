@extends('layouts.admin')

@section('title', 'Promotions - HomeNest Furniture')
@section('header', 'Promotions')

@section('content')
    <div class="space-y-6">
        <!-- Create Button -->
        <div class="flex justify-end">
            <a href="{{ route('admin.promotions.create') }}"
                class="px-4 py-2 bg-admin-blue text-white rounded-lg hover:bg-admin-light-blue">
                <i class="fas fa-plus mr-2"></i> Add Promotion
            </a>
        </div>

        <!-- Search -->
        <div class="bg-white rounded-xl shadow-md p-4">
            <form method="GET" action="{{ route('admin.promotions.index') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search Promotion</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by promotion name..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-admin-light-blue">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="submit" class="px-4 py-2 bg-admin-blue text-white rounded-lg hover:bg-admin-light-blue">
                        <i class="fas fa-search mr-2"></i> Search
                    </button>
                    <a href="{{ route('admin.promotions.index') }}"
                        class="px-4 py-5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                        <i class="fas fa-undo mr-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Promotions Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Promotion Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">End Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Products</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($promotions as $promotion)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $promotion->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $promotion->promotion_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($promotion->start_date)->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($promotion->end_date)->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <a href="{{ route('admin.promotions.products', $promotion->id) }}"
                                        class="text-admin-blue hover:text-admin-light-blue">
                                        {{ $promotion->products->count() }} products
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                    <a href="{{ route('admin.promotions.products', $promotion->id) }}"
                                        class="text-blue-600 hover:text-blue-700">
                                        <i class="fas fa-boxes"></i>
                                    </a>
                                    <a href="{{ route('admin.promotions.edit', $promotion->id) }}"
                                        class="text-green-600 hover:text-green-700">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="deletePromotion({{ $promotion->id }})"
                                        class="text-red-600 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                                </td>
                        @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        No promotions found
                                    </td>
                                </tr>
                            @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t">
                {{ $promotions->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function deletePromotion(id) {
            if (confirm('Are you sure you want to delete this promotion? All products in this promotion will also be removed.')) {
                const form = document.getElementById('deleteForm');
                form.action = `/admin/promotions/${id}`;
                form.submit();
            }
        }
    </script>
@endsection