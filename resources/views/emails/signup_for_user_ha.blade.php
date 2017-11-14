@component('mail::message')

Hi {{ $user->firstname }},<br />
Thanks for signing up with {{ $project }}!<br />
Please email us the name of your HomeAdvisor rep so we can set up the integration and get you started right away. If you have any questions, please respond to this email.<br />

Thanks!<br />
CEO | Cofounder <br />
{{ $project }}
@endcomponent