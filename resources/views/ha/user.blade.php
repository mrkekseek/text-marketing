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

					<div uib-alert class="alert-info" ng-show="ha.send_request && ! list.length">
						{{ __("Your request was sent to HomeAdvisor. We will inform you when it will be processed") }}
					</div>

					<div uib-alert class="alert-success" ng-show="list.length">
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
								<label>{{ __('Instant Text') }}</label>
								<char-set ng-model="ha.text" unique-id="'ha'" max-firstname="maxChars('firstname')" max-lastname="maxChars('lastname')" company="user.company_name" btn-firstname="true" btn-lastname="true" btn-shortlink="true" lms="true"></char-set>
							</div>

							<div class="form-group">
								<label>{{ __('Follow-Up Text') }}</label>
								<i class="fa fa-question-circle-o" uib-tooltip="Follow-Up Text goes out an hour after the Instant Text, if the Lead does not click on your link or does not text you back." tooltip-placement="right" aria-hidden="true"></i>
								<input class="form-control" type="text" disabled="disabled" ng-model="followupText" />
							</div>

							<div class="form-group">
								<span class="upload-button-box">
									<button type="button" class="btn btn-sm btn-default">
										<i class="fa fa-picture-o"></i> {{ __("Choose File") }}
									</button>
									<input onchange="angular.element(this).scope().uploadFile(event.target.files[0])" accept="image/jpeg,image/png,image/gif,image/bmp,video/avi,video/mp4,video/quicktime,video/x-ms-wmv" type="file" />
								</span>

								<span class="upload-tooltip" uib-tooltip="{{ __('Image size limit is 500 KB; supported image file types include .JPG, .PNG, .GIF (non-animated), .BMP Video size limit is 3 MB; supported video file types include .AVI, .MP4, .WMV, and .MOV') }}">
									<i class="fa fa-question-circle"></i> {{ __('Upload details') }}
								</span>
							</div>
							<div class="form-group">
								<img ng-show="file.url" src="@{{ file.url }}" class="preview-mms" />
								<i ng-show="file.url" ng-click="removeMMS()" class="fa fa-times mms-remove" aria-hidden="true"></i>
								<i ng-show="request" class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
							</div>

							<div class="form-group">
								<label>{{ __('My number for alerts') }}</label>
								<i class="fa fa-question-circle-o" uib-tooltip="If the Lead clicks your link or texts you back, the platform will send a text to this number alerting you." tooltip-placement="right" aria-hidden="true"></i>
								<input type="text" name="phone" class="form-control" ng-model="user.view_phone" required="required" />
							</div>

							<label>{{ __('Additional phones') }}</label>
							<span class="fa fa-question-circle-o" uib-tooltip="You can add additional numbers that will be alerted." tooltip-placement="right"></span>
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
				<div class="panel-body leads">
					<div ng-repeat="item in list">
						<div class="leads-list">
							<div class="pull-left message-icon">
								<i ng-show="item.count == 0" class="fa fa-envelope-o" aria-hidden="true" uib-tooltip="{{ __('You haven\'t new message') }}"></i>
								<a ng-show="item.count > 0" href="/marketing/inbox/@{{item.id}}/" class="fa fa-envelope-o text-primary" aria-hidden="true" uib-tooltip="{{ __('You have new message(s)') }}"></a>
							</div>
							<div class="pull-left">
								<div>
									<strong>@{{ item.view_phone }}</strong>
									<span class="small-italic">(@{{ item.created_at | date: 'MMMM d' }}@{{ getSuffix(item.created_at | date: 'd') }} @{{ item.created_at | date: 'h:mm a' }})</span>
								</div>
								<div>
									@{{ item.firstname }}
									@{{ item.lastname }}
									<i ng-show="item.inbox" class="fa fa-check-circle-o text-success" aria-hidden="true" uib-tooltip="Lead texted a reply"></i>
									<span> </span>
									<i ng-show="item.clicked" class="fa fa-check-circle-o text-info" aria-hidden="true" uib-tooltip="Link clicked"></i>
								</div>
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