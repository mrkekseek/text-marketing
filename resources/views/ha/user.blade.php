<div class="page page-table ng-scope">
	<h2>
		{{ __('HomeAdvisor') }}	
		<i class="fa fa-question-circle-o help-icon" uib-tooltip="To get started, click the 'Activate HomeAdvisor' button. We will then speak to your HomeAdvisor rep to get you connected and we will alert you when we are done. Then you can customize the text you want your leads to receive. We recommend putting in the link to your booking site and your number and letting them know that they can reply by text as well. This gives the lead 3 ways to engage. On the right side of the page you will see a list of all of your leads. Leads who click the link will have a green check next to their name, while a blue check signifies that they texted a reply." tooltip-placement="right-top" aria-hidden="true"></i>
	</h2>
	<div class="row">
		<div class="col-sm-12 col-md-5">
			<div class="panel panel-default">
				<div class="panel-body">
					<form novalidate="novalidate">
						<div class="alert-info alert" role="alert">
						{{ __("To get started, click the 'Activate HomeAdvisor' button. We will then speak to your HomeAdvisor rep to get you connected and we will alert you when we are done. Then you can customize the text you want your leads to receive. We recommend putting in the link to your booking site and your number and letting them know that they can reply by text as well. This gives the lead 3 ways to engage. On the right side of the page you will see a list of all of your leads. Leads who click the link will have a green check next to their name, while a blue check signifies that they texted a reply.") }}
						</div>
						<button type="submit" class="btn btn-primary" ng-click="activate_ha()">{{ __('Activate HomeAdvisor') }}</button>
					</form>
				</div>
			</div>
		</div>

	</div>
	<div class="col-sm-12 col-md-7"></div>
</div>
</div>