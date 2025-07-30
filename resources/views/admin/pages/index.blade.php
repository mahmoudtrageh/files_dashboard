@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Welcome Card -->
    <div class="bg-white rounded-xl p-5 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between shadow-card border border-gray-100 hover:shadow-card-hover transition-all duration-300">
        <div class="flex items-center mb-4 sm:mb-0">
            @if(Auth::guard('admin')->user()->getFirstMediaUrl('profile_image'))
                <img src="{{ Auth::guard('admin')->user()->getFirstMediaUrl('profile_image') }}" alt="User Avatar" class="w-12 h-12 rounded-full">
            @else
                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                    <span class="text-indigo-800 font-medium text-lg">{{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) }}</span>
                </div>
            @endif
            <div class="mr-3">
                <div class="flex items-center">
                    <h2 class="text-lg font-bold text-gray-900">مرحبًا، {{ Auth::guard('admin')->user()->name }}</h2>
                    <span class="mr-2 px-2 py-0.5 text-xs rounded-full bg-primary-100 text-primary-800">{{ Auth::guard('admin')->user()->getRoleNames()->first() }}</span>
                </div>
            </div>
        </div>
        {{-- <div>
            <button class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200">
                <i class="fas fa-plus ml-2"></i>
                إضافة منتج جديد
            </button>
        </div> --}}
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
         <div class="bg-white rounded-xl p-5 shadow-card border border-gray-100 hover:shadow-card-hover transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">الملفات</h3>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ getFilesCount() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-file text-blue-600"></i>
                </div>
            </div>
            {{-- <div class="flex items-center text-sm text-green-600">
                <i class="fas fa-arrow-up ml-1"></i>
                <span>زيادة 32% من الشهر الماضي</span>
            </div> --}}
        </div>

         <div class="bg-white rounded-xl p-5 shadow-card border border-gray-100 hover:shadow-card-hover transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">الأقسام</h3>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ getCategoriesCount() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-list text-blue-600"></i>
                </div>
            </div>
            {{-- <div class="flex items-center text-sm text-green-600">
                <i class="fas fa-arrow-up ml-1"></i>
                <span>زيادة 32% من الشهر الماضي</span>
            </div> --}}
        </div>

        <!-- Revenue Card -->
        <div class="bg-white rounded-xl p-5 shadow-card border border-gray-100 hover:shadow-card-hover transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">المشرفين</h3>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ getAdminCount() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
            </div>
            {{-- <div class="flex items-center text-sm text-green-600">
                <i class="fas fa-arrow-up ml-1"></i>
                <span>زيادة 32% من الشهر الماضي</span>
            </div> --}}
        </div>

        <!-- Customers Card -->
        <div class="bg-white rounded-xl p-5 shadow-card border border-gray-100 hover:shadow-card-hover transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">الأدوار</h3>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ getRoleCount() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fa-solid fa-users-gear"></i>
                </div>
            </div>
            {{-- <div class="flex items-center text-sm text-red-600">
                <i class="fas fa-arrow-down ml-1"></i>
                <span>انخفاض 3% من الشهر الماضي</span>
            </div> --}}
        </div>

        <!-- Orders Card -->
        <div class="bg-white rounded-xl p-5 shadow-card border border-gray-100 hover:shadow-card-hover transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">الصلاحيات</h3>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ getPermissionCount() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
            </div>
            {{-- <div class="flex items-center text-sm text-green-600">
                <i class="fas fa-arrow-up ml-1"></i>
                <span>زيادة 7% من الشهر الماضي</span>
            </div> --}}
        </div>

        <!-- Products Card -->
        {{-- <div class="bg-white rounded-xl p-5 shadow-card border border-gray-100 hover:shadow-card-hover transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">المنتجات</h3>
                    <p class="text-2xl font-bold text-gray-900 mt-1">512</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-box text-purple-600"></i>
                </div>
            </div>
            <div class="flex items-center text-sm text-green-600">
                <i class="fas fa-arrow-up ml-1"></i>
                <span>زيادة 12% من الشهر الماضي</span>
            </div>
        </div> --}}
    </div>

    <!-- Latest Orders -->
    {{-- <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900">أحدث الطلبات</h3>
                <p class="text-sm text-gray-500 mt-1">إجمالي 213 طلب مفتوح</p>
            </div>
            <div class="w-full sm:w-auto flex items-center">
                <div class="relative flex-grow sm:flex-grow-0">
                    <input type="text" placeholder="بحث في الطلبات..." class="w-full sm:w-64 py-2 pr-10 pl-4 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                    <i class="fas fa-search text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                </div>
                <button class="ml-3 p-2 rounded-lg text-primary-600 hover:bg-primary-50 transition-all duration-200 focus:outline-none hidden sm:block">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
        </div>
        <div class="overflow-x-auto clean-scrollbar">
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr class="bg-gray-50">
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-start">
                                تاريخ الطلب
                                <i class="fas fa-sort mr-1 text-gray-400"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-start">
                                الرقم
                                <i class="fas fa-sort mr-1 text-gray-400"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-start">
                                العميل
                                <i class="fas fa-sort mr-1 text-gray-400"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-start">
                                السعر الإجمالي
                                <i class="fas fa-sort mr-1 text-gray-400"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <span class="sr-only">الإجراءات</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">4 مايو, 2025</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#OR42739</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-xs">
                                    ر.ك
                                </div>
                                <div class="mr-3">
                                    <div class="text-sm font-medium text-gray-900">ريمنجتون كوهلمان</div>
                                    <div class="text-xs text-gray-500">remington@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="status-badge status-new">
                                <i class="fas fa-circle-notch fa-spin"></i>
                                جديد
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">$1,522.39</td>
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                            <div class="flex items-center space-x-reverse space-x-2">
                                <button class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-gray-400 hover:text-red-500 focus:outline-none">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">4 مايو, 2025</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#OR219286</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-xs">
                                    ج.ه
                                </div>
                                <div class="mr-3">
                                    <div class="text-sm font-medium text-gray-900">جودسون هيلز</div>
                                    <div class="text-xs text-gray-500">judson@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="status-badge status-delivered">
                                <i class="fas fa-check-circle"></i>
                                تم التسليم
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">$1,579.63</td>
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                            <div class="flex items-center space-x-reverse space-x-2">
                                <button class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-gray-400 hover:text-red-500 focus:outline-none">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">3 مايو, 2025</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#OR423362</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-xs">
                                    إ.إ
                                </div>
                                <div class="mr-3">
                                    <div class="text-sm font-medium text-gray-900">إيف إيمريش</div>
                                    <div class="text-xs text-gray-500">eve@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="status-badge status-processing">
                                <i class="fas fa-cog fa-spin"></i>
                                قيد المعالجة
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">$1,034.57</td>
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                            <div class="flex items-center space-x-reverse space-x-2">
                                <button class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-gray-400 hover:text-red-500 focus:outline-none">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2 مايو, 2025</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#OR150112</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-xs">
                                    ه.ك
                                </div>
                                <div class="mr-3">
                                    <div class="text-sm font-medium text-gray-900">هيرشيل كلوكو</div>
                                    <div class="text-xs text-gray-500">herschel@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="status-badge status-shipped">
                                <i class="fas fa-shipping-fast"></i>
                                تم الشحن
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">$1,883.54</td>
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                            <div class="flex items-center space-x-reverse space-x-2">
                                <button class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-gray-400 hover:text-red-500 focus:outline-none">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center">
                <span class="text-sm text-gray-700 ml-2">لكل صفحة</span>
                <div class="relative">
                    <select class="appearance-none bg-white border border-gray-200 text-gray-700 py-2 px-3 pr-8 rounded-lg leading-tight focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-2 text-gray-400">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-reverse space-x-2">
                <button class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200" disabled>
                    السابق
                </button>
                <div class="hidden sm:flex items-center space-x-reverse space-x-1">
                    <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-primary-600 text-white text-sm font-medium">1</a>
                    <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-500 hover:bg-gray-100 text-sm font-medium">2</a>
                    <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-500 hover:bg-gray-100 text-sm font-medium">3</a>
                    <span class="text-gray-500">...</span>
                    <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-500 hover:bg-gray-100 text-sm font-medium">8</a>
                </div>
                <button class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200">
                    التالي
                </button>
            </div>
        </div>
    </div> --}}

    <!-- Recent Activity and Quick Actions -->
    {{-- <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Recent Activity -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">النشاط الأخير</h3>
            </div>
            <div class="divide-y divide-gray-100">
                <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                    <div class="flex">
                        <div class="flex-shrink-0 ml-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-user-plus text-blue-600"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">تم تسجيل عميل جديد</p>
                            <p class="text-sm text-gray-500">قام أحمد محمد بالتسجيل في المتجر</p>
                            <p class="text-xs text-gray-400 mt-1">منذ 20 دقيقة</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                    <div class="flex">
                        <div class="flex-shrink-0 ml-3">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-shopping-cart text-green-600"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">طلب جديد #OR42739</p>
                            <p class="text-sm text-gray-500">قام ريمنجتون كوهلمان بشراء آيفون 15 برو</p>
                            <p class="text-xs text-gray-400 mt-1">منذ 35 دقيقة</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                    <div class="flex">
                        <div class="flex-shrink-0 ml-3">
                            <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                                <i class="fas fa-star text-yellow-600"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">تقييم جديد</p>
                            <p class="text-sm text-gray-500">قام هيرشيل كلوكو بتقييم سماعات آبل إيربودز برو 2</p>
                            <div class="flex items-center mt-1">
                                <div class="flex items-center text-yellow-400">
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                </div>
                                <span class="text-xs text-gray-400 mr-2">منذ ساعة</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-3 border-t border-gray-100">
                <a href="#" class="text-primary-600 hover:text-primary-700 text-sm font-medium">عرض كل الأنشطة &leftarrow;</a>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">إجراءات سريعة</h3>
            </div>
            <div class="p-6 space-y-4">
                <button class="w-full flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center text-primary-600">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="mr-3 text-right">
                            <span class="block text-sm font-medium text-gray-900">إضافة منتج</span>
                            <span class="block text-xs text-gray-500">إضافة منتج جديد للمتجر</span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-left text-gray-400"></i>
                </button>
                <button class="w-full flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                            <i class="fas fa-tag"></i>
                        </div>
                        <div class="mr-3 text-right">
                            <span class="block text-sm font-medium text-gray-900">إنشاء خصم</span>
                            <span class="block text-xs text-gray-500">إنشاء كود خصم جديد</span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-left text-gray-400"></i>
                </button>
                <button class="w-full flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="mr-3 text-right">
                            <span class="block text-sm font-medium text-gray-900">تقرير المبيعات</span>
                            <span class="block text-xs text-gray-500">عرض تقرير المبيعات الشهري</span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-left text-gray-400"></i>
                </button>
            </div>
        </div>
    </div> --}}
@endsection
