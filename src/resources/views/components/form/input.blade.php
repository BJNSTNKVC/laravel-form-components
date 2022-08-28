<label class="fc-form-group {{ $label }} tg-caption" label-type="{{ $labelType }}" @error($name) invalid @enderror for="{{ $name }}">
    <input {{ $attributes->merge(['class' => 'form-group__input']) }}
           type="text" id="{{ $id }}"
           name="{{ $name }}"
           placeholder="{{ $title ?: Str::title($name) }}"
           value="{{ $value }}"
           border="{{ $border }}"
           border-radius="{{ $borderRadius }}"
           invalidated-title="{{ $invalidatedTitle ? 'true' : 'false' }}"
           interactive="{{ config('form_components.interactive') ? 'true' : 'false' }}"
           autofocus>
    <span class="form-group__title @if($invalidatedTitle && $errors->has($name)) fc-is-invalid @endif">{!! $title !!}</span>
    <x-form::error name="{{ $name  }}" />
</label>
