@extends('admin.layouts.app')

@section('title', 'إدارة المشرفين')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-900">إدارة المشرفين</h1>
            <a href="{{ route('admin.admins.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                <svg class="-ml-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                إضافة مشرف جديد
            </a>
        </div>
        
        <!-- Alerts -->
        @include('admin.components.alerts')

        <!-- Livewire Filter Component -->
        @livewire('admin.admin-filter')
                
    </div>
</div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('livewire:initialized', function () {
            Livewire.on('filterUpdated', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
    </script>
@endsection