<x-mail::message>
# hello

Please click the button below to go to password form.

<x-mail::button :url="$url">
    reset password
</x-mail::button>

Thank you!,<br>
{{ config('app.name') }}
</x-mail::message>