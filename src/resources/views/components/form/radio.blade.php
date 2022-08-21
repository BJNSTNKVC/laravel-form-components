<label class="fc-form-group form-group--radio tg-caption" position="{{ $position }}" @error($name) invalid @enderror for="{{ $name }}">

    <span class="form-group__title @if($invalidatedTitle && $errors->has($name)) fc-is-invalid @endif">{!! $title !!}</span>
    <input {{ $attributes->merge(['class' => 'form-group__input']) }}
           type="radio"
           id="{{ $id }}"
           name="{{ $name }}"
           {{ $value }}
           interactive="{{ config('form_components.interactive') }}"
           autofocus
    >

    <x-form::error name="{{ $name  }}" />
</label>
