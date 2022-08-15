<label class="fc-form-group {{ $label }} tg-caption" type="{{ $labelType }}" @error($name) invalid @enderror for="{{ $name }}">

    @if(! Str::contains($labelType, 'floating'))
        <span class="form-group__title">{!! $title !!}</span>
    @endif

    <textarea {{ $attributes->merge(['class' => 'form-group__input form-group__textarea']) }}
              id="{{ $id }}"
              name="{{ $name }}"
              placeholder="{{ $title ?: Str::title($name) }}"
              rows="2"
              cols=""
              maxlength=""
              interactive="{{ config('form_components.interactive') }}"
              autoexpand="{{ config('form_components.auto_expand') }}"
              autofocus
    >{!! $value !!}</textarea>

    @if(Str::contains($labelType, 'floating'))
        <span class="form-group__title">{!! $title !!}</span>
    @endif

    <x-form::error name="{{ $name  }}" />
</label>
