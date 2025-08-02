{{-- resources/views/admin/pages/archives/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', trans('all.Archive Management'))

@push('breadcrumbs')
    @php
    $breadcrumbs = [
        ['name' => trans('all.Archive Management')]
    ];
    @endphp
@endpush

@section('content')
    <livewire:admin.archive-manager />
@endsection

@push('styles')
<style>
    .file-upload-area {
        transition: all 0.3s ease;
    }

    .file-upload-area.dragover {
        border-color: #6366f1;
        background-color: rgba(99, 102, 241, 0.05);
    }

    .archive-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    /* Loading animation */
    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }

    /* Notification styles (if you want to implement custom notifications) */
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 9999;
        transform: translateX(100%);
        transition: transform 0.3s ease;
    }

    .notification.show {
        transform: translateX(0);
    }

    .notification.success {
        background-color: #10b981;
    }

    .notification.error {
        background-color: #ef4444;
    }

    /* RTL Support */
    @if(is_rtl())
        .notification {
            right: auto;
            left: 20px;
            transform: translateX(-100%);
        }

        .notification.show {
            transform: translateX(0);
        }
    @endif
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Custom notification system
    window.showNotification = function(message, type = 'success') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notification => notification.remove());

        // Create new notification
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Show notification
        setTimeout(() => notification.classList.add('show'), 100);

        // Hide notification after 5 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    };

    // File upload progress (if you want to implement it)
    window.addEventListener('livewire:upload-start', () => {
        console.log('Upload started');
    });

    window.addEventListener('livewire:upload-finish', () => {
        console.log('Upload finished');
    });

    window.addEventListener('livewire:upload-error', () => {
        showNotification('{{ trans("all.An error occurred") }}', 'error');
    });

    window.addEventListener('livewire:upload-progress', (event) => {
        console.log('Upload progress: ' + event.detail.progress + '%');
    });
});
</script>
@endpush
