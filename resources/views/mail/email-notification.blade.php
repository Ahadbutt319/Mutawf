<x-mail::message>
# {{ config('app.name') }} Email Verification

Hi {{ $name }}! <br />
Hi {{ $email }}! <br />
Hi {{ $description }}! <br />
Hi {{ $phone }}! <br />
Your email verification code is: {{ $email }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
