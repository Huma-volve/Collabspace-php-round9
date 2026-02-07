@php
    $url = Storage::disk('public')->url($record->url);
    $extension = pathinfo($record->url, PATHINFO_EXTENSION);
@endphp

@if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp']))
    <a href="{{ $url }}" target="_blank" class="text-primary-600 underline">

        <img src="{{ $url }}" width="100px" height="100px" class="h-12 rounded cursor-pointer" />
    </a>
@elseif ($extension === 'pdf')
    <a href="{{ $url }}" target="_blank" class="text-primary-600 underline">
        Preview PDF
    </a>
@else
    <span class="text-gray-500">No preview</span>
@endif
