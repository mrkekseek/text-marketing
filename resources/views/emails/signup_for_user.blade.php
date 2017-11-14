
@component('mail::message')

Hi {{ $user->firstname }}
Thanks for signing up with {{ config('app.name') }}!
As you’ll see, it’s extremely easy to use and will gather 5 star online reviews and automate text message marketing. If you need any assistance whatsoever, please respond to this email.
Click here to go to your account: <a href="{{ $link }}">{{ config('name') }}</a>

Thanks!<br />
CEO | Cofounder <br />
{{ config('app.name') }}
@endcomponent