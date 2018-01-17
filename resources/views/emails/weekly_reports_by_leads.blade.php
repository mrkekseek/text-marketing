@component('mail::message')

Hi {{ $firstname }},<br />

Hope you had a great week! Here’s a snapshot of your past week on ContractorTexter:<br />

{{ $result['clients_count'] }} of HomeAdvisor Leads<br />
{{ $result['clicked_count'] }} of Leads that clicked on the link to your booking site<br />
{{ $result['clicked_client'] }}<br />
{{ $result['reply_count'] }} of Leads that texted you back<br />
{{ $result['reply_client'] }}<br />

Remember to ask your clients their thoughts on the text so we can improve and close more.<br />


Thanks,<br>
{{ config('app.name') }}
@endcomponent