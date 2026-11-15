@php($classes = $attributes->class([
    'fc:table fc:mx-auto fc:px-6 fc:py-3 fc:text-base fc:leading-none fc:text-center fc:no-underline fc:cursor-pointer fc:border-0 fc:text-white fc:bg-black fc:shadow-[inset_0_0_0_2px_black] fc:hover:text-black fc:hover:bg-white fc:disabled:pointer-events-none fc:disabled:text-stone-500 fc:disabled:bg-stone-500/15 fc:disabled:shadow-stone-500/15',
    'fc:rounded-none' => $borderRadius === 'squared',
    'fc:rounded-lg' => $borderRadius === 'rounded-s',
    'fc:rounded-xl' => $borderRadius === 'rounded-m',
    'fc:rounded-3xl' => $borderRadius === 'rounded',
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
