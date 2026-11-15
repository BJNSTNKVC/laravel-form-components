@error($name)
    <span
        {{ $attributes->class('form-group__error fc:[grid-area:error] fc:inline-block fc:mt-1 fc:text-red-600') }}
        role="alert"
    >
        @foreach($errors->get($name) as $message)
            <strong class="fc:block fc:text-xs fc:leading-4">{{ $message }}</strong>
        @endforeach
    </span>
@enderror
