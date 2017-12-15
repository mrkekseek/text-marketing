@component('mail::message')

You have a message from {{ config('app.name') }} website

<b>Name</b>: {{ $sender['name'] }}<br />
<b>Email</b>: {{ $sender['email'] }}<br />
<b>Message</b>: {{ str_replace("\n", '<br />', $sender['message']) }}


Thanks,<br>
{{ config('app.name') }}
@endcomponent
