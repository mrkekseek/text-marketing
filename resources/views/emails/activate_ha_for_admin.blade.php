@component('mail::message')
<p>
	User {{ $user['firstname'] }} wants to activate HomeAdvisor.
</p>
<p>
	<b>Link for HomeAdvisor:</b><span> {{ $user['link'] }}</span>
</p>
<p>
	<b>Success String:</b><span> {{ $user['success'] }}</span>
</p>
@endcomponent
