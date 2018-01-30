@component('mail::message')

Hi {{ $firstname }},<br />

Hope you had a great week! Here’s a snapshot of your past week on ContractorTexter:<br />

<ul style="padding: 0">
	<li><p>{{ $result['clients_count'] }} HomeAdvisor Leads</p></li>
	<li><p>{{ $result['clicked_count'] }} Leads clicked on the link to your booking site: <i>{{ $result['clicked_client'] }}</i></p></li>
	<li><p>{{ $result['reply_count'] }} Leads texted you back: <i>{{ $result['reply_client'] }}</i></p></li>
</ul>

<p>As always, you can log into your dashboard to see everything: <a href="{{ config('app.url') }}">app.contractortexter.com</a></p>
Remember to ask your clients their thoughts on the text so we can improve and close more.<br />


Thanks,<br>
{{ config('app.name') }}
@endcomponent