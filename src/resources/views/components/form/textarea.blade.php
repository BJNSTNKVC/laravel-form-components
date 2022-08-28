<label class="fc-form-group {{ $label }} tg-caption" label-type="{{ $labelType }}" @error($name) invalid @enderror for="{{ $name }}">
    <textarea {{ $attributes->merge(['class' => 'form-group__input form-group__textarea']) }}
              id="{{ $id }}"
              name="{{ $name }}"
              placeholder="{{ $title ?: Str::title($name) }}"
              rows="2"
              border="{{ $border }}"
              border-radius="{{ $borderRadius }}"
              interactive="{{ config('form_components.interactive') ? 'true' : 'false' }}"
              autoexpand="{{ config('form_components.auto_expand') ? 'true' : 'false' }}"
              autofocus
    >{!! $value !!}</textarea>
    <span class="form-group__title @if($invalidatedTitle && $errors->has($name)) fc-is-invalid @endif">{!! $title !!}</span>
    <x-form::error name="{{ $name  }}" />
</label>
