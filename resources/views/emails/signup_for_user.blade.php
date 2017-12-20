@component('mail::message')

Hi {{ $user->firstname }},<br />
Thanks for signing up with {{ $project }}!<br />
As you'll see, it's extremely easy to use and will gather 5 star online reviews and automate text message marketing. If you need any assistance whatsoever, please respond to this email.<br />
Click here to go to your account: <a href="{{ $link }}">{{ $project }}</a><br />

<p>Thanks!</p>
Uri <br />
CEO | Cofounder <br />
{{ config('mail.from.address') }}
@endcomponent