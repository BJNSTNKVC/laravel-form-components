<label class="fc-form-group form-group--radio {{ $label }} {{ $switch ? 'form-group--switch' : '' }} tg-caption" position="{{ $position }}" @error($name) invalid @enderror for="{{ $name }}">

    <input {{ $attributes->merge(['class' => $switch ? 'form-group--switchput' : 'form-group__input']) }}
           type="radio"
           id="{{ $id }}"
           name="{{ $name }}"
           {{ $value }}
           interactive="{{ config('form_components.interactive') ? 'true' : 'false' }}"
           @if ($checked) checked @endif">

    @if($switch)
        <i class="form-group__switcher"></i>
    @endif

    <span class="form-group__title @if($invalidatedTitle && $errors->has($name)) fc-is-invalid @endif">{!! $title !!}</span>

    <x-form::error name="{{ $name  }}" />
</label>
