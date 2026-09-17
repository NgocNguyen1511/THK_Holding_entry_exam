@props([
    'type' => 'success', // 'success' or 'danger'
    'title' => null,
])

@php
    $isSuccess = $type === 'success';
    $defaultTitle = $isSuccess ? 'Success' : 'Please check your input:';
    $title = $title ?? $defaultTitle;
@endphp

<div {{ $attributes->merge(['class' => 'alert alert-' . $type]) }}>
    <div class="alert-icon">
        @if($isSuccess)
            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
        @else
            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
        @endif
    </div>
    <div class="alert-body">
        @if($title)
            <strong>{{ $title }}</strong>
        @endif
        {{ $slot }}
    </div>
</div>
