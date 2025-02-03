<x-mail::message>
# hello

Please click the button below to verify your email address.

<x-mail::button :url="$url">
    verify email
</x-mail::button>

Thank you!,<br>
{{ config('app.name') }}
</x-mail::message>