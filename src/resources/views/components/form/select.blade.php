<label class="fc-form-group {{ $label }} tg-caption" label-type="{{ $labelType }}" @error($name) invalid @enderror for="{{ $name }}">
    <select {{ $attributes->merge(['class' => 'form-group__select']) }}
            id="{{ $id }}"
            name="{{ $name }}"
            value="{{ old($name) ?: $default }}"
            border="{{ $border }}"
            border-radius="{{ $borderRadius }}"
            with-icon="{{ $icon && ! $attributes['multiple'] ? 'true' : 'false' }}"
            interactive="{{ config('form_components.interactive') ? 'true' : 'false' }}">

        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @if(! $model)
            @foreach ($values as $key => $value)
                <option value="{{ Str::slug($key) }}" {{ Str::is($value, $default) && is_null(old($name)) ? 'selected' : '' }} {{ !is_null(old($name)) && old($name) == $key ? 'selected' : '' }}>{{ $value  }}</option>
            @endforeach
        @endif


        @if($model)
            @foreach ($model as $m)
                <option @if($jsKey) {{ $jsSet . $m->$jsKey }} @endif value="{{ $m->$modelKey }}" {{ Str::is($m->$modelValue, $default) && is_null(old($name)) ? 'selected' : '' }} {{ old($name) == $m->$modelKey ? 'selected' : '' }}>{{ $m->$modelValue }}</option>
            @endforeach
        @endif
    </select>

    <span class="form-group__title @if($invalidatedTitle && $errors->has($name)) fc-is-invalid @endif">{!! $title !!}</span>

    @if(! $attributes['multiple'])
        <span class="form-group__icon">
            {!! $icon !!}
        </span>
    @endif

    <x-form::error name="{{ $name  }}" />
</label>
