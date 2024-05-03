<x-mail::message style="background-color: black; color: limegreen;">
    # Introduction

    <x-mail::button :url="''" style="background-color: limegreen; color: black;">
        Button Text
    </x-mail::button>

    Thanks,<br>
    <span style="color: limegreen;">{{ config('app.name') }}</span>
</x-mail::message>
