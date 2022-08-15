@if(! $link)
    <button {{ $attributes->merge(['class' => 'form-btn btn-black btn-' . $borderRadius . ' tg-button']) }} id="{{ $id }}" type="submit">{!! $slot != '' ? $slot : $title !!}</button>
@else
    <a {{ $attributes->merge(['class' => 'form-btn btn-black btn-' . $borderRadius . ' tg-button']) }} id="{{ $id }}" href="{{ $link }}">{!! $slot != '' ? $slot : $title !!}</a>
@endif
