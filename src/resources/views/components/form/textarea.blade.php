<label class="fc-form-group {{ $label }} tg-caption" label-type="{{ $labelType }}" @error($name) invalid @enderror for="{{ $name }}">

    <textarea {{ $attributes->merge(['class' => 'form-group__input form-group__textarea']) }}
              id="{{ $id }}"
              name="{{ $name }}"
              placeholder="{{ $title ?: Str::title($name) }}"
              rows="2"
              border="{{ $border }}"
              border-radius="{{ $borderRadius }}"
              with-icon="{{ $showIcon && $icon ? 'true' : 'false' }}"
              interactive="{{ config('form_components.interactive') ? 'true' : 'false' }}"
              autoexpand="{{ config('form_components.auto_expand') ? 'true' : 'false' }}"
    >{!! $value !!}</textarea>

    <span class="form-group__title @if($invalidatedTitle && $errors->has($name)) fc-is-invalid @endif">{!! $title !!}</span>

    @if($showIcon && $icon)
        <span class="form-group__icon">
            {!! $icon !!}
        </span>
    @endif

    <x-form::error name="{{ $name  }}" />
</label>
