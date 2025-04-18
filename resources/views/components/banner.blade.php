@props(['style', 'display', 'fixed', 'position'])

@if(app('impersonate')->isImpersonating() && app('impersonate')->getImpersonatorGuardUsingName() === filament()->getCurrentPanel()->getAuthGuard())

@php
$display = $display ?? Filament\Facades\Filament::getUserName(Filament\Facades\Filament::auth()->user());
$fixed = $fixed ?? config('filament-impersonate.banner.fixed');
$position = $position ?? config('filament-impersonate.banner.position');
$borderPosition = $position === 'top' ? 'bottom' : 'top';

$style = $style ?? config('filament-impersonate.banner.style');
$styles = config('filament-impersonate.banner.styles');
$default = $style === 'auto' ? 'light' : $style;
$flipped = $default === 'dark' ? 'light' : 'dark';
@endphp

<style>
    :root {
        --impersonate-banner-height: 50px;

        --impersonate-light-bg-color: {{ $styles['light']['background'] }};
        --impersonate-light-text-color: {{ $styles['light']['text'] }};
        --impersonate-light-border-color: {{ $styles['light']['border'] }};
        --impersonate-light-button-bg-color: {{ implode(',', sscanf($styles['dark']['background'], "#%02x%02x%02x")) }};
        --impersonate-light-button-text-color: {{ $styles['dark']['text'] }};

        --impersonate-dark-bg-color: {{ $styles['dark']['background'] }};
        --impersonate-dark-text-color: {{ $styles['dark']['text'] }};
        --impersonate-dark-border-color: {{ $styles['dark']['border'] }};
        --impersonate-dark-button-bg-color: {{ implode(',', sscanf($styles['light']['background'], "#%02x%02x%02x")) }};
        --impersonate-dark-button-text-color: {{ $styles['light']['text'] }};
    }
    html {
        margin-{{ $position }}: var(--impersonate-banner-height);
    }


    #impersonate-banner {
        position: {{ $fixed ? 'fixed' : 'absolute' }};
        height: var(--impersonate-banner-height);
        {{ $position }}: 0;
        width: 100%;
        display: flex;
        column-gap: 20px;
        justify-content: center;
        align-items: center;
        background-color: var(--impersonate-{{ $default }}-bg-color);
        color: var(--impersonate-{{ $default }}-text-color);
        border-{{ $borderPosition }}: 1px solid var(--impersonate-{{ $default }}-border-color);
        z-index: 45;
    }

    @if($style === 'auto')
    .dark #impersonate-banner {
        background-color: var(--impersonate-dark-bg-color);
        color: var(--impersonate-dark-text-color);
        border-{{ $borderPosition }}: 1px solid var(--impersonate-dark-border-color);
    }
    @endif

    #impersonate-banner a {
        display: block;
        padding: 4px 20px;
        border-radius: 5px;
        background-color: rgba(var(--impersonate-{{ $default }}-button-bg-color), 0.7);
        color: var(--impersonate-{{ $default }}-button-text-color);
    }

    @if($style === 'auto')
    .dark #impersonate-banner a {
        background-color: rgba(var(--impersonate-dark-button-bg-color), 0.7);
        color: var(--impersonate-dark-button-text-color);
    }
    @endif

    #impersonate-banner a:hover {
        background-color: rgb(var(--impersonate-{{ $default }}-button-bg-color));
    }

    @if($style === 'auto')
    .dark #impersonate-banner a:hover {
        background-color: rgb(var(--impersonate-dark-button-bg-color));
    }
    @endif

    @if($fixed)
    div.fi-layout > aside.fi-sidebar {
        height: calc(100vh - var(--impersonate-banner-height));
    }

    @if($position === 'top')
    .fi-topbar {
        top: var(--impersonate-banner-height);
    }
    div.fi-layout > aside.fi-sidebar {
        top: var(--impersonate-banner-height);
    }
    @endif

    @else
    div.fi-layout > aside.fi-sidebar {
        padding-bottom: var(--impersonate-banner-height);
    }
    @endif

    @media print{
        aside, body {
            margin-top: 0;
        }

        #impersonate-banner {
            display: none;
        }
    }
</style>

<div id="impersonate-banner">
    <div>
        {{ __('filament-impersonate::banner.impersonating') }} <strong>{{ $display }}</strong>
    </div>

    <a href="{{ route('filament-impersonate.leave') }}">{{ __('filament-impersonate::banner.leave') }}</a>
</div>
@endIf
