@extends('admin.layouts.app')

@section('title', trans('all.Edit Administrator')  . $admin->name)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900"{{ trans('all.Edit Administrator')  }}:> {{ $admin->name }}</h1>
            <a href="{{ route('admin.admins.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                <svg class="-ml-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
               {{trans('all.Administrators Management') }}
            </a>
        </div>

        <div class="mt-6">
            <form action="{{ route('admin.admins.update', $admin) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="bg-white">
                        @include('admin.pages.admins._form')
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
