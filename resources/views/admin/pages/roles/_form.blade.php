@php
    $isEditMode = isset($role);
@endphp

<div class="px-4 py-5 bg-white sm:p-6 rounded-lg shadow">
    <div class="grid grid-cols-6 gap-6">
        <div class="col-span-6">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">اسم الدور</label>
            <div class="relative rounded-md shadow-sm">
                <input type="text" name="name" id="name" value="{{ old('name', $isEditMode ? $role->name : '') }}" 
                    class="block w-full pr-10 py-3 border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 sm:text-sm" 
                    placeholder="أدخل اسم الدور" required>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">الصلاحيات</label>
            
            @error('permissions')
                <p class="mb-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
            
            <div class="bg-gray-50 p-5 rounded-md border border-gray-200 shadow-sm">
                <div class="mb-4">
                    <div class="flex items-center">
                        <button type="button" id="select-all-permissions" class="inline-flex items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                            <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            تحديد الكل
                        </button>
                        <button type="button" id="deselect-all-permissions" class="mr-3 inline-flex items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            إلغاء تحديد الكل
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @php
                        // Group permissions by their module/group if they follow a pattern like module.action
                        $groupedPermissions = $permissions->groupBy(function($permission) {
                            $parts = explode('.', $permission->name);
                            return count($parts) > 1 ? $parts[0] : 'general';
                        });
                    @endphp
                    
                    @foreach($groupedPermissions as $group => $perms)
                        <div class="bg-white p-4 rounded-md shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                            <h3 class="text-md font-medium text-gray-800 mb-3 flex items-center">
                                <svg class="h-5 w-5 text-indigo-500 mr-1.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                {{ ucfirst($group) }}
                            </h3>
                            
                            <div class="space-y-3">
                                @foreach($perms as $permission)
                                    <div class="relative flex items-start p-2 rounded-md hover:bg-gray-50 transition-colors duration-200">
                                        <div class="flex items-center h-5">
                                            <input id="permission-{{ $permission->id }}" name="permissions[]" type="checkbox" value="{{ $permission->id }}"
                                                {{ (old('permissions') && in_array($permission->id, old('permissions'))) || ($isEditMode && $role->permissions->contains($permission->id)) ? 'checked' : '' }}
                                                class="permission-checkbox focus:ring-indigo-500 h-5 w-5 text-indigo-600 border-gray-300 rounded transition-colors duration-200">
                                        </div>
                                        <div class="mr-3 text-sm">
                                            <label for="permission-{{ $permission->id }}" class="font-medium text-gray-700">{{ $permission->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="px-4 py-3 bg-gray-50 text-left sm:px-6 rounded-b-lg mt-3">
    <button type="submit" class="inline-flex justify-center items-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
        {{ $isEditMode ? 'تحديث الدور' : 'إضافة الدور' }}
    </button>
    <a href="{{ route('admin.roles.index') }}" class="inline-flex justify-center items-center py-2.5 px-5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3 transition-colors duration-200">
        إلغاء
    </a>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select all permissions
        document.getElementById('select-all-permissions').addEventListener('click', function() {
            document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
                checkbox.checked = true;
            });
        });
        
        // Deselect all permissions
        document.getElementById('deselect-all-permissions').addEventListener('click', function() {
            document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
                checkbox.checked = false;
            });
        });
    });
</script>
@endsection