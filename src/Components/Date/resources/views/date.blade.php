<label class="fc-form-group {{ $label }} tg-caption" label-type="{{ $labelType }}" @error($name) invalid @enderror for="{{ $name }}">

    <input {{ $attributes->merge(['class' => 'form-group__input']) }}
           type="date"
           id="{{ $id }}"
           name="{{ $name }}"
           value="{{ $value }}"
           step="1"
           border="{{ $border }}"
           border-radius="{{ $borderRadius }}"
           with-icon="{{ $showIcon && $icon ? 'true' : 'false' }}"
           interactive="{{ config('form_components.interactive') ? 'true' : 'false' }}">

    <span class="form-group__title @if($invalidatedTitle && $errors->has($name)) fc-is-invalid @endif">{!! $title !!}</span>

    @if($showIcon && $icon)
        <span class="form-group__icon">
            {!! $icon !!}
        </span>
    @endif

    <x-form::error name="{{ $name  }}" />
</label>
