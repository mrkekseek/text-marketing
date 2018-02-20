@component('mail::message')
<p>
	User {{ $user->firstname }} wants to activate HomeAdvisor.
</p>
<p>
	<b>Link for HomeAdvisor:</b><span> {{ $link->url }}</span>
</p>
<p>
	<b>Success String:</b><span> {{ $link->success }}</span>
</p>
@if ( ! empty($user->phone))
<p>
	<b>Cell #:</b><span> {{ $user->phone }}</span>
</p>
@endif

@if ( ! empty($ha->rep))
<p>
	<b>HomeAdvisor Rep:</b><span> {{ $ha->rep }}</span>
</p>
@endif
@endcomponent
