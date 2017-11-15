@component('mail::message')
You have new Sign Up at {{ $project }}<br />
<b>Name:</b>{{ $user['firstname'] }}<br />
<b>Email:</b>{{ $user['email'] }}<br />
<b>Plan:</b>{{ $user['email'] }}<br />

<a href="{{ $link }}">{{ $project }}</a>

@endcomponent