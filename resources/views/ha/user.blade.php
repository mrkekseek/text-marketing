<div class="page page-table" data-ng-controller="HomeAdvisorCtrl">
	<h2>
		{{ __('HomeAdvisor') }}	
		<i class="fa fa-question-circle-o help-icon" uib-tooltip="To get started, click the 'Activate HomeAdvisor' button. We will then speak to your HomeAdvisor rep to get you connected and we will alert you when we are done. Then you can customize the text you want your leads to receive. We recommend putting in the link to your booking site and your number and letting them know that they can reply by text as well. This gives the lead 3 ways to engage. On the right side of the page you will see a list of all of your leads. Leads who click the link will have a green check next to their name, while a blue check signifies that they texted a reply." tooltip-placement="right-top" aria-hidden="true"></i>
	</h2>
	<div class="row">
		<div class="col-sm-12 col-md-6">
			<div class="panel panel-default">
				<div class="panel-body">
					<div uib-alert class="alert-info" ng-show="!requestForHa">
						{{ __("To get started, click the 'Activate HomeAdvisor' button. We will then speak to your HomeAdvisor rep to get you connected and we will alert you when we are done. Then you can customize the text you want your leads to receive. We recommend putting in the link to your booking site and your number and letting them know that they can reply by text as well. This gives the lead 3 ways to engage. On the right side of the page you will see a list of all of your leads. Leads who click the link will have a green check next to their name, while a blue check signifies that they texted a reply.") }}
					</div>
					<div uib-alert class="alert-info" ng-show="requestForHa">
						{{ __("Your request was sent to HomeAdvisor. We will inform you when it will be processed") }}
					</div>
					<div uib-alert class="alert-success" ng-show="false">
						{{ __('Your request was approved by HomeAdvisor') }}
					</div>
					<button type="button" class="btn btn-primary" ng-show="!requestForHa" ng-click="activateHa()">{{ __('Activate HomeAdvisor') }}</button>
					<div ng-show="requestForHa">
						<div class="form-group">
							<label class="ui-switch ui-switch-success ui-switch-sm pull-right">
								<input id="enableHa" type="checkbox" ng-click="change_ha_enable()" />
								<i></i>
							</label>
							<label for="enableHa">
								{{ __('Enable text to Lead') }}
							</label>
						</div>
						<div>
							<div char-set ng-model="textData" options="TextCharSetOptions"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-12 col-md-6">
			
		</div>
	</div>
</div>