@php
    // جلب البيانات من الموديل مباشرة داخل الفيو
    $completed = $getRecord()->completed_tasks ?? 0;
    $total = $getRecord()->total_tasks ?? 1; // حطينا 1 عشان ميعملش قسمة على صفر
    $percentage = ($completed / $total) * 100;
@endphp

<div class="flex items-center w-full gap-x-3" style="min-width: 140px;">
    {{-- شريط التحميل بتنسيق Tailwind --}}
    <div class="flex-1 h-2 bg-gray-200 rounded-full dark:bg-gray-700">
        <div class="h-2 rounded-full bg-primary-600" style="width: {{ $percentage }}%"></div>
    </div>
    {{-- النسبة المئوية --}}
    <span class="text-xs font-bold text-gray-600 dark:text-gray-300">
        {{ round($percentage) }}%
    </span>
</div>
