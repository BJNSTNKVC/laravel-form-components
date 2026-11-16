<div>
    <x-form::password
        name="password"
        title="Password"
    />

    @if($added)
        <x-form::search
            name="search"
            title="Search"
        />
    @endif

    <button
        id="add"
        type="button"
        wire:click="add"
    >
        Add a Field
    </button>
</div>
