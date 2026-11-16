@error($name)
    <span
        {{ $attributes->class('fc:[grid-area:error] fc:inline-block fc:[&[hidden]]:hidden fc:mt-1 fc:text-danger fc:dark:scheme-dark') }}
        role="alert"
    >
        @foreach($errors->get($name) as $message)
            <strong class="fc:block fc:text-xs fc:leading-4">{{ $message }}</strong>
        @endforeach
    </span>
@enderror
