<label class="fc-form-group {{ $label }} tg-caption" type="{{ $labelType }}" @error($name) invalid @enderror for="{{ $name }}">

    @if(! Str::contains($labelType, 'floating'))
        <span class="form-group__title @if($invalidatedTitle && $errors->has($name)) fc-is-invalid @endif">{!! $title !!}</span>
    @endif

    <input {{ $attributes->merge(['class' => 'form-group__input']) }}
           type="datetime-local"
           id="{{ $id }}"
           name="{{ $name }}"
           value="{{ $value }}"
           step="1"
           interactive="{{ config('form_components.interactive') }}"
           autofocus
    >

    @if(Str::contains($labelType, 'floating'))
        <span class="form-group__title @if($invalidatedTitle && $errors->has($name)) fc-is-invalid @endif">{!! $title !!}</span>
    @endif

    <x-form::error name="{{ $name  }}" />
</label>
