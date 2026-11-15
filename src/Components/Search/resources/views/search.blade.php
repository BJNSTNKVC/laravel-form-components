<label class="fc-form-group {{ $label }} tg-caption" label-type="{{ $labelType }}" @error($name) invalid @enderror for="{{ $name }}">
    <input {{ $attributes->merge(['class' => 'form-group__input']) }}
           type="search"
           id="{{ $id }}"
           name="{{ $name }}"
           placeholder="{{ $title ?: Str::title($name) }}"
           value="{{ $value }}"
           border="{{ $border }}"
           border-radius="{{ $borderRadius }}"
           with-icon="{{ $showIcon && $icon ? 'true' : 'false' }}"
           with-clearing="{{ $searchClearing ? 'true' : 'false' }}"
           invalidated-title="{{ $invalidatedTitle ? 'true' : 'false' }}"
           interactive="{{ config('form_components.interactive') ? 'true' : 'false' }}">

    <span class="form-group__title @if($invalidatedTitle && $errors->has($name)) fc-is-invalid @endif">{!! $title !!}</span>

    @if($showIcon && $icon || $searchClearing)
        <span class="form-group__icon">
            @if($searchClearing)
                {!! $searchClearIcon !!}
            @endif
            @if($showIcon)
                {!! $icon !!}
            @endif
        </span>
    @endif

    <x-form::error name="{{ $name  }}" />
</label>
