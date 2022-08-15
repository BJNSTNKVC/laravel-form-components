<label class="fc-form-group form-group--checkbox tg-caption" position="{{ $position }}" @error($name) invalid @enderror for="{{ $name }}">

    <span class="form-group__title">{!! $title !!}</span>
    <input {{ $attributes->merge(['class' => 'form-group__input']) }}
           type="checkbox"
           id="{{ $id }}"
           name="{{ $name }}"
           {{ $value }}
           interactive="{{ config('form_components.interactive') }}"
           autofocus
    >

    <x-form::error name="{{ $name  }}" />
</label>
