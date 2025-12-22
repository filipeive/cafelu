@extends('layouts.app')

@section('content')
    <div class="w-full">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="mdi mdi-folder-multiple text-orange-500"></i>
                    {{ __('messages.categories_title') }}
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">{{ __('messages.manage_categories_desc') }}</p>
            </div>
            <button type="button" @click="openCreateModal()"
                class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-orange-500/20 flex items-center gap-2">
                <i class="mdi mdi-plus-circle"></i>
                {{ __('messages.new_category') }}
            </button>
        </div>

        <!-- Categories Table -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden"
            x-data="{ 
                        showCreateModal: false,
                        showEditModal: false,
                        editCategory: { id: '', name: '' },
                        openCreateModal() { this.showCreateModal = true; },
                        openEditModal(id, name) { 
                            this.editCategory = { id, name };
                            this.showEditModal = true;
                        }
                     }">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('messages.name') }}
                            </th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('messages.products_count') }}
                            </th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('messages.created_at') }}
                            </th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">
                                {{ __('messages.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($categories as $category)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-900/20 flex items-center justify-center">
                                            <i class="mdi mdi-folder text-orange-500 text-xl"></i>
                                        </div>
                                        <span
                                            class="text-sm font-bold text-gray-900 dark:text-white">{{ $category->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                        {{ $category->products->count() }} {{ __('messages.products') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($category->created_at)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button type="button" @click="openEditModal('{{ $category->id }}', '{{ $category->name }}')"
                                        class="p-2 bg-gray-50 dark:bg-gray-700 text-gray-400 hover:text-orange-500 rounded-lg transition-colors"
                                        title="{{ __('messages.edit') }}">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>

                                    <button type="button" onclick="confirmDelete({{ $category->id }})"
                                        class="p-2 bg-gray-50 dark:bg-gray-700 text-gray-400 hover:text-red-500 rounded-lg transition-colors"
                                        title="{{ __('messages.delete') }}">
                                        <i class="mdi mdi-delete"></i>
                                    </button>

                                    <form id="deleteForm{{ $category->id }}"
                                        action="{{ route('categories.destroy', $category) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="mdi mdi-folder-open-outline text-6xl text-gray-200 dark:text-gray-700"></i>
                                        <p class="text-gray-500 dark:text-gray-400 mt-4">
                                            {{ __('messages.no_categories_found') }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Create Modal -->
            <div x-show="showCreateModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showCreateModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 transition-opacity bg-gray-900/50 backdrop-blur-sm"
                        @click="showCreateModal = false"></div>

                    <div x-show="showCreateModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="inline-block w-full max-w-md overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 rounded-3xl shadow-xl sm:my-8">

                        <form action="{{ route('categories.store') }}" method="POST">
                            @csrf
                            <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-700">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ __('messages.new_category') }}
                                </h3>
                            </div>
                            <div class="p-8">
                                <div class="space-y-4">
                                    <div>
                                        <label
                                            class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.category_name') }}</label>
                                        <input type="text" name="name" required
                                            class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                    </div>
                                </div>
                            </div>
                            <div
                                class="px-8 py-6 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3">
                                <button type="button" @click="showCreateModal = false"
                                    class="px-6 py-2 text-sm font-bold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                                    {{ __('messages.cancel') }}
                                </button>
                                <button type="submit"
                                    class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-orange-500/20">
                                    {{ __('messages.create_category') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div x-show="showEditModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showEditModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 transition-opacity bg-gray-900/50 backdrop-blur-sm"
                        @click="showEditModal = false"></div>

                    <div x-show="showEditModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="inline-block w-full max-w-md overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 rounded-3xl shadow-xl sm:my-8">

                        <form :action="'/categories/' + editCategory.id" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-700">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ __('messages.edit_category') }}
                                </h3>
                            </div>
                            <div class="p-8">
                                <div class="space-y-4">
                                    <div>
                                        <label
                                            class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.category_name') }}</label>
                                        <input type="text" name="name" x-model="editCategory.name" required
                                            class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                    </div>
                                </div>
                            </div>
                            <div
                                class="px-8 py-6 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3">
                                <button type="button" @click="showEditModal = false"
                                    class="px-6 py-2 text-sm font-bold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                                    {{ __('messages.cancel') }}
                                </button>
                                <button type="submit"
                                    class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-orange-500/20">
                                    {{ __('messages.save_changes') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function confirmDelete(categoryId) {
                Swal.fire({
                    title: '{{ __('messages.are_you_sure') }}',
                    text: '{{ __('messages.action_irreversible') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: '{{ __('messages.yes_delete') }}',
                    cancelButtonText: '{{ __('messages.cancel') }}',
                    background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                    color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#1F2937'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteForm' + categoryId).submit();
                    }
                });
            }
        </script>
    @endpush
@endsection