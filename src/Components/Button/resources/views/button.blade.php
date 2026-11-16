@php($classes = $attributes->class([
    'fc:table fc:mx-auto fc:px-6 fc:py-3 fc:text-base fc:leading-none fc:text-center fc:no-underline fc:cursor-pointer fc:border-0 fc:font-base fc:text-primary-foreground fc:bg-primary fc:shadow-[inset_0_0_0_2px] fc:shadow-primary fc:hover:text-primary fc:hover:bg-primary-foreground fc:disabled:pointer-events-none fc:disabled:text-muted fc:disabled:bg-muted/15 fc:disabled:shadow-muted/15 fc:dark:scheme-dark',
    'fc:rounded-none' => $borderRadius === 'squared',
    'fc:rounded-small' => $borderRadius === 'rounded-s',
    'fc:rounded-medium' => $borderRadius === 'rounded-m',
    'fc:rounded-pill' => $borderRadius === 'rounded',
]))

@if(! $link)
    <button
        {{ $classes }}
        id="{{ $id }}"
        type="submit"
    >
        {!! $slot != '' ? $slot : $title !!}
    </button>
@else
    <a
        {{ $classes }}
        id="{{ $id }}"
        href="{{ $link }}"
    >
        {!! $slot != '' ? $slot : $title !!}
    </a>
@endif
