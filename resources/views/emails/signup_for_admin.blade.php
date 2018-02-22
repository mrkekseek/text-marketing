@component('mail::message')
You have new Sign Up at {{ $project }}<br />
<b>Name: </b>{{ $user['firstname'] }}<br />
<b>Email: </b>{{ $user['email'] }}<br />
<b>Cell #: </b>{{ $user['phone'] }}<br />
<b>Plan: </b>{{ $user['plans_id'] }}<br />

<a href="{{ $link }}">{{ $project }}</a>

@endcomponent