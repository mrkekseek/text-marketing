<div class="page page-table" data-ng-controller="HomeAdvisorCtrl" ng-init="init()">
	<h2>
		{{ __('HomeAdvisor') }}	
		<i class="fa fa-question-circle-o help-icon" uib-tooltip="To get started, click the 'Activate HomeAdvisor' button. We will then speak to your HomeAdvisor rep to get you connected and we will alert you when we are done. Then you can customize the text you want your leads to receive. We recommend putting in the link to your booking site and your number and letting them know that they can reply by text as well. This gives the lead 3 ways to engage. On the right side of the page you will see a list of all of your leads. Leads who click the link will have a green check next to their name, while a blue check signifies that they texted a reply." tooltip-placement="right-top" aria-hidden="true"></i>
	</h2>

	<div class="row">
		<div class="col-sm-12 col-md-6">
			<div class="panel panel-default">
				<div class="panel-body">
					<div uib-alert class="alert-info" ng-show=" ! ha.send_request">
						{{ __("To get started, click the 'Activate HomeAdvisor' button. We will then speak to your HomeAdvisor rep to get you connected and we will alert you when we are done. Then you can customize the text you want your leads to receive. We recommend putting in the link to your booking site and your number and letting them know that they can reply by text as well. This gives the lead 3 ways to engage. On the right side of the page you will see a list of all of your leads. Leads who click the link will have a green check next to their name, while a blue check signifies that they texted a reply.") }}
					</div>

					<div uib-alert class="alert-info" ng-show="ha.send_request">
						{{ __("Your request was sent to HomeAdvisor. We will inform you when it will be processed") }}
					</div>

					<div uib-alert class="alert-success" ng-show="false">
						{{ __('Your request was approved by HomeAdvisor') }}
					</div>

					<button type="button" class="btn btn-primary" ng-show=" ! ha.send_request" ng-click="activate()">{{ __('Activate HomeAdvisor') }}</button>
					
					<div ng-show="ha.send_request">
						<div class="form-group">
							<label class="ui-switch ui-switch-success ui-switch-sm pull-right">
								<input id="enable" type="checkbox" ng-model="ha.active" ng-click="enable()" ng-true-value="1" ng-false-value="0" />
								<i></i>
							</label>

							<label for="enable">
								{{ __('Enable text to Lead') }}
							</label>
						</div>

						<div class="form-group">
							<label>{{ __('Company Name') }}</label>
							<i class="fa fa-question-circle-o" uib-tooltip-template="'companyTooltip.html'" tooltip-placement="right" aria-hidden="true"></i>
							<div class="input-group">
								<input type="text" class="form-control" maxlength="32" ng-model="user.company_name" ng-change="companyChange()" placeholder="{{ __('Company Name') }}" />
								<span class="input-group-addon bg-success" ng-show="user.company_status == 'verified' && ! companyChanged">{{ __('Verified') }}</span>
								<span class="input-group-addon bg-warning" ng-show="user.company_status == 'pending' && ! companyChanged">{{ __('Pending') }}</span>
								<span class="input-group-addon bg-danger" ng-show="user.company_status == 'denied' && ! companyChanged">{{ __('Denied') }}</span>
								<span class="input-group-btn" ng-show="user.company_status == '' || companyChanged">
									<button class="btn btn-default" ng-click="companySave()">{{ __('Save') }}</button>
								</span>
							</div>
						</div>

						<div uib-alert class="alert-info" ng-show="user.company_status != 'verified' || companyChanged">
							{{ __('To send texts you should save Company Name and wait untill it will be verified. It may takes 15 minutes') }}
						</div>

						<form name="form_ha" novalidate="novalidate" ng-show="user.company_status == 'verified' && ! companyChanged">
							<div class="form-group">
								<char-set ng-model="ha.text" unique-id="'ha'" max-firstname="maxChars('firstname')" max-lastname="maxChars('lastname')" company="user.company_name" btn-firstname="true" btn-lastname="true" lms="true"></char-set>
							</div>

							<div class="form-group">
								<label>{{ __('My number for alerts') }}</label>
								<input type="text" name="phone" class="form-control" ng-model="user.phone" required="required" />
							</div>

							<label>{{ __('Additional phones') }}</label>
							<span class="fa fa-question-circle-o" uib-tooltip="Insert your cell number here so if you get a text reply, we text your actual cell with the message you’ve received." tooltip-placement="right"></span>
							<div class="form-group" ng-repeat="input in inputs track by $index">
								<div class="input-group">
									<input type="text" name="phone_@{{$index}}" class="form-control" ng-model="inputs[$index]" placeholder="{{ __('Enter phone here...') }}" />
									<span class="input-group-btn" ng-show="$index == inputs.length - 1">
										<button class="btn btn-default" type="button" ng-click="add();">
											<i class="fa fa-plus" aria-hidden="true"></i>
										</button>
									</span>

									<span class="input-group-btn" ng-show="$index < inputs.length - 1">
										<button class="btn btn-default" type="button" ng-click="remove($index);">
											<i class="fa fa-minus" aria-hidden="true"></i>
										</button>
									</span>
								</div>
							</div>

							<div class="form-group">
								<button class="btn btn-primary" type="submit" ng-click="save()">{{ __('Save') }}</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-12 col-md-6">
			<div class="panel panel-default" ng-show="list.length">
				<div class="panel-heading">
					<strong>{{ __('Leads') }}</strong>
				</div>
				<div class="panel-body">
					<div ng-repeat="item in list">
						<div class="leads-list">
							<div class="pull-left message-icon">
								<i class="fa fa-envelope-o" aria-hidden="true" uib-tooltip="{{ __('You haven\'t new message') }}"></i>
								<a href="/marketing/inbox/@{{item.id}}/" class="fa fa-envelope-o text-primary" aria-hidden="true" uib-tooltip="{{ __('You have new message(s)') }}"></a>
							</div>
							<div class="pull-left">
								<div>
									<strong>@{{ item.view_phone }}</strong>
									<span class="small-italic">(@{{ item.created_at | date: 'MMMM d' }}@{{ getSuffix(item.created_at | date: 'd') }} @{{ item.created_at | date: 'h:mm a' }})</span>
								</div>
								<div>
									@{{ item.firstname }}
									@{{ item.lastname }}
									<i class="fa fa-check-circle-o text-success" aria-hidden="true" uib-tooltip="Lead texted a reply"></i>
									<span> </span>
									<i class="fa fa-check-circle-o text-info" aria-hidden="true" uib-tooltip="Link clicked"></i>
								</div>
							</div>
							<div class="pull-right">
								<a href="/marketing/inbox/@{{item.id}}/" class="btn btn-default">
									<i class="fa fa-envelope-o" aria-hidden="true"></i>
									<span>{{ __('Respond') }}</span>
								</a>
							</div>
						</div>
						<div class="divider divider-dashed divider-sm pull-in">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/ng-template" id="companyTooltip.html">
	<span>{{ __('Customize your Company Name at the beginning of your text, like in the image. The limit for the Company Name is 32 characters.') }}</span>
	<img src="/img/company_name_help.png" class="img-responsive" />
</script>