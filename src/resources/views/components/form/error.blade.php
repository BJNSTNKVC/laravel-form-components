@error($name)
<span {{ $attributes->merge(['class' => 'form-group__error invalid-feedback']) }} role="alert">
        @foreach($errors->get($name) as $message)
        <strong class="form-group__message tg-helper">{{ $message }}</strong>
    @endforeach
    </span>
@enderror
