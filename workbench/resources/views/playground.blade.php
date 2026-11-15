<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Components Playground</title>
    <style>{!! $styles !!}</style>
    <style>
        body { margin: 0 auto; max-width: 960px; padding: 2rem 1rem; font-family: system-ui, sans-serif; }
        section { margin-bottom: 3rem; }
        .playground-grid { display: grid; gap: 1.5rem; }
    </style>
</head>
<body>
    <h1>Form Components Playground</h1>

    @foreach (['column', 'row', 'floating'] as $labelType)
        <section id="label-type-{{ $labelType }}">
            <h2>{{ ucfirst($labelType) }} Labels</h2>

            <x-form::base action="#" id="form-{{ $labelType }}" class="playground-grid">
                <x-form::input name="input_{{ $labelType }}" title="Input" label-type="{{ $labelType }}" />
                <x-form::email name="email_{{ $labelType }}" title="Email" label-type="{{ $labelType }}" />
                <x-form::password name="password_{{ $labelType }}" title="Password" label-type="{{ $labelType }}" />
                <x-form::search name="search_{{ $labelType }}" title="Search" label-type="{{ $labelType }}" />
                <x-form::textarea name="textarea_{{ $labelType }}" title="Textarea" label-type="{{ $labelType }}" />
                <x-form::select name="select_{{ $labelType }}" title="Select" label-type="{{ $labelType }}" values="Option A, Option B" />
                <x-form::date name="date_{{ $labelType }}" title="Date" label-type="{{ $labelType }}" />
                <x-form::time name="time_{{ $labelType }}" title="Time" label-type="{{ $labelType }}" />
                <x-form::date-time name="date_time_{{ $labelType }}" title="Date &amp; Time" label-type="{{ $labelType }}" />
                <x-form::file name="file_{{ $labelType }}" title="File" label-type="{{ $labelType }}" />
            </x-form::base>
        </section>
    @endforeach

    <section id="choices">
        <h2>Checkboxes, Radios and Buttons</h2>

        <x-form::base action="#" id="form-choices" class="playground-grid">
            <x-form::checkbox name="checkbox" switch="false" />
            <x-form::checkbox name="checkbox_switch" title="Checkbox Switch" checked="true" />
            <x-form::radio name="radio" switch="false" />
            <x-form::radio name="radio_switch" title="Radio Switch" />
            <x-form::button name="submit" />
            <x-form::button title="Link" link="#" />
        </x-form::base>
    </section>

    <script>{!! $scripts !!}</script>
</body>
</html>
