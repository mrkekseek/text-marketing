<div class="page page-table" data-ng-controller="HomeAdvisorCtrl" ng-init="init()">
	<h2>
		{{ __('HomeAdvisor') }}	
		<i class="fa fa-question-circle-o help-icon" uib-tooltip="To get started, please click the 'Activate’ button. We then send your details to HomeAdvisor to get you connected - this can take them a few days (they move slowly). Please enter your Company Name, Website, Office and Cell Numbers, Logo and Job Pics below. We’ve already put in the text templates for you, but you can always customize them. To come back to this page anytime, go to app.contractortexter.com. Once you are connected we will send a test text to your Cell." tooltip-placement="right-top" aria-hidden="true"></i>
	</h2>

	<div class="row">
		<div class="col-sm-12 col-md-6">
			<div class="panel panel-default">
				<div class="panel-body">
					<div uib-alert class="alert-info" ng-show=" ! ha.send_request">
						<ol class="unstyled_ul">
							<li>{{ __("To get started, please click the 'Activate’ button. We then send your details to HomeAdvisor to get you connected - this can take them a few days (they move slowly).") }}</li>
							<li>{{ __("Please enter your Company Name, Website, Office and Cell Numbers, Logo and Job Pics below.") }}</li>
							<li>{{ __("We've already put in the text templates for you, but you can always customize them. To come back to this page anytime, go to")}} <a href="https://app.contractortexter.com">app.contractortexter.com</a>.</li>
							<li>{{ __("Once you are connected we will send a test text to your Cell.") }}</li>
						</ol>
						Thanks!
					</div>

					<div uib-alert class="alert-info" ng-show="ha.send_request && ! list.length">
						{{ __("Your request was sent to HomeAdvisor. We will inform you when it will be processed") }}
					</div>

					<div uib-alert class="alert-success" ng-show="list.length">
						{{ __('Want 3 Free Months? Refer 1 Friend. Click ')}}<a href="/ha/referral">HERE</a>{{ __(' for details') }}
					</div>

					<button type="button" class="btn btn-primary" ng-show=" ! ha.send_request" ng-click="activate()">{{ __('Activate HomeAdvisor') }}</button>
					
					<div ng-show="ha.send_request">
						<div class="form-group" ng-show="list.length">
							<label class="ui-switch ui-switch-success ui-switch-sm pull-right">
								<input id="enable" type="checkbox" ng-model="ha.active" ng-click="enable()" ng-true-value="1" ng-false-value="0" />
								<i></i>
							</label>

							<label for="enable">
								{{ __('Enable text to Lead') }}
							</label>
						</div>

						<form name="form_ha" novalidate="novalidate">
							<div class="form-group">
								<label>{{ __('Company Name') }}</label>
								<i class="fa fa-question-circle-o" uib-tooltip-template="'companyTooltip.html'" tooltip-placement="right" aria-hidden="true"></i>
								<div class="input-group">
									<input type="text" class="form-control" maxlength="32" ng-model="user.company_name" ng-change="companyChange()" placeholder="{{ __('Company Name') }}" />
									<span class="input-group-addon bg-success" ng-show="user.company_status == 'verified' && ! companyChanged">{{ __('Verified') }}</span>
									<span class="input-group-addon bg-warning" ng-show="user.company_status == 'pending' && ! companyChanged">{{ __('Pending') }}</span>
									<span class="input-group-addon bg-danger" ng-show="user.company_status == 'denied' && ! companyChanged">{{ __('Denied') }}</span>
									<span class="input-group-addon bg-default" ng-show="user.company_status == '' && ! companyChanged">{{ __('Need to Verify') }}</span>
								</div>
							</div>

							<div uib-alert class="alert-info" ng-show="user.company_status != 'verified' || companyChanged">
								{{ __('This can take up to 15 minutes') }}
							</div>

							<div class="row">
								<div class="col-sm-6 col-xs-12">
									<div class="form-group">
										<label>{{ __('Website') }}</label>
										<input type="text" class="form-control" name="website" ng-model="user.website" placeholder="{{ __('Website') }}" ng-required="user.company_status != 'verified'" />
									</div>
									
									<div class="form-group">
										<label>{{ __('Office Number') }}</label>
										<input type="text" class="form-control" name="office_phone" ng-model="user.office_phone" placeholder="{{ __('Office Number') }}" ng-required="user.company_status != 'verified'" />
									</div>
								</div>
							</div>

							<div class="form-group" ng-show="user.company_status == 'verified' && ! companyChanged">
								<div class="form-group">
									<label>{{ __('Instant Text') }}</label>
									<char-set ng-model="ha.text" unique-id="'ha'" max-firstname="maxChars('firstname')" max-lastname="maxChars('lastname')" company="user.company_name" btn-firstname="true" btn-lastname="true" btn-hapage="true" btn-shortlink="true" btn-website="true" btn-office-phone="true" lms="true"></char-set>
								</div>
							
								<label>{{ __('Texts') }}</label>
								<i class="fa fa-question-circle-o" uib-tooltip="Follow-Up Text goes out an hour after the Instant Text, if the Lead does not click on your link or does not text you back." tooltip-placement="right" aria-hidden="true"></i>
								
								<div class="form-group">
									<div class="followup_group">
										<div class="followup_switcher">
											<label class="ui-switch ui-switch-success ui-switch-sm">
												<input id="enable_followup_first" type="checkbox" ng-model="ha.first_followup_active" ng-true-value="1" ng-false-value="0" />
												<i></i>
											</label>
											<span>&nbsp; Enable First Follow-Up</span>
										</div>

										<div ng-show="ha.first_followup_active">
											<div class="followup_text">
												<char-set ng-model="ha.first_followup_text" class="followup_textarea" ng-class="{disabled_followup: ! ha.first_followup_active}" ng-disabled=" ! ha.first_followup_active" unique-id="'first_followup'" max-firstname="maxChars('firstname')" max-lastname="maxChars('lastname')" company="user.company_name" btn-firstname="true" btn-hapage="true" btn-link="true" btn-website="true" btn-office-phone="true"></char-set>
											</div>

											<div class="followup_delay">
												<select class="form-control" ng-class="{disabled_followup: ! ha.first_followup_active}" ng-model="ha.first_followup_delay" ng-disabled=" ! ha.first_followup_active">
													<option value="@{{ hour.value }}" ng-repeat="hour in followup_hours">@{{ hour.text + ' ' + getHourText(hour.value) }}</option>
												</select>
												<i class="fa fa-question-circle-o" ng-class="{disabled_followup: ! ha.first_followup_active}" uib-tooltip="Time after Lead came in. Follow up texts will never be sent between midnight and 6 AM." tooltip-placement="bottom" tooltip-append-to-body="true"></i>
											</div>
										</div>
									</div>
								</div>

								<div class="form-group">
									<div class="followup_group">
										<div class="followup_switcher">
											<label class="ui-switch ui-switch-success ui-switch-sm">
												<input id="enable_followup_second" type="checkbox" ng-model="ha.second_followup_active" ng-true-value="1" ng-false-value="0" />
												<i></i>
											</label>
											<span>&nbsp; Enable Second Follow-Up</span>
										</div>

										<div ng-show="ha.second_followup_active">
											<div class="followup_text">
												<char-set ng-model="ha.second_followup_text" class="followup_textarea" unique-id="'first_followup'" max-firstname="maxChars('firstname')" max-lastname="maxChars('lastname')" company="user.company_name" ng-class="{disabled_followup: ha.second_followup_active == 0}" ng-disabled=" ! ha.second_followup_active" btn-firstname="true" btn-hapage="true" btn-link="true" btn-website="true" btn-office-phone="true"></char-set>
											</div>

											<div class="followup_delay">
												<select class="form-control" ng-class="{disabled_followup: ha.second_followup_active == 0}" ng-model="ha.second_followup_delay" ng-disabled=" ! ha.second_followup_active">
													<option value="@{{ hour.value }}" ng-repeat="hour in followup_hours">@{{ hour.text + ' ' + getHourText(hour.value) }}</option>
												</select>
												<i class="fa fa-question-circle-o" ng-class="{disabled_followup: ha.second_followup_active == 0}" uib-tooltip="Time after Lead came in. Follow up texts will never be sent between midnight and 6 AM." tooltip-placement="bottom" tooltip-append-to-body="true"></i>
											</div>
										</div>
									</div>
								</div>
							</div>

							<label>Upload Logo</label>
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

							<div class="row">
								<div class="col-sm-6 col-xs-12">
									<div class="form-group">
										<label>{{ __('My Cell for Text Alerts') }}</label>
										<i class="fa fa-question-circle-o" uib-tooltip="If the Lead clicks your link or texts you back, the platform will send a text to this number alerting you." tooltip-placement="right" aria-hidden="true"></i>
										<input type="text" name="phone" class="form-control" ng-model="user.view_phone" required="required" />
									</div>

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
								</div>

								<div class="col-sm-6 col-xs-12" ng-show="user.company_status == 'verified' && ! companyChanged">
									<label>{{ __('My Email for Alerts') }}</label>
									<div class="form-group" ng-repeat="email in emails track by $index">
										<div class="input-group">
											<input type="text" name="email_@{{$index}}" class="form-control" ng-model="emails[$index]" placeholder="{{ __('Enter email here...') }}" />
											<span class="input-group-btn" ng-show="$index == emails.length - 1">
												<button class="btn btn-default" type="button" ng-click="emailsAdd();">
													<i class="fa fa-plus" aria-hidden="true"></i>
												</button>
											</span>

											<span class="input-group-btn" ng-show="$index < emails.length - 1">
												<button class="btn btn-default" type="button" ng-click="emailsRemove($index);">
													<i class="fa fa-minus" aria-hidden="true"></i>
												</button>
											</span>
										</div>
									</div>
								</div>
							</div>

							<label>{{ __('Upload Job Pics') }}</label>
							<i class="fa fa-question-circle-o" uib-tooltip="Here you can upload up to 5 pictures of jobs you've done. A link will then be added to the text which the Lead can click to see the pics." tooltip-placement="right" aria-hidden="true"></i>
							<div class="form-group">
								<span class="upload-button-box">
									<button type="button" class="btn btn-sm btn-default">
										<i class="fa fa-picture-o"></i> {{ __("Choose File") }}
									</button>
									<input ng-disabled="uploading.pictures > 0" onchange="angular.element(this).scope().uploadPictures(event.target.files)" multiple="multiple" accept="image/jpeg,image/png,image/gif,image/bmp" type="file" />
								</span>

								<span ng-show="uploading.pictures == 0" class="upload-tooltip" uib-tooltip="{{ __('You can upload up to 5 images. Image size limit is 2 MB; supported image file types include .JPG, .PNG, .GIF (non-animated), .BMP') }}">
									<i class="fa fa-question-circle"></i> {{ __('Upload details') }}
								</span>

								<span ng-show="uploading.pictures > 0" class="upload-tooltip">
									<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Uploading @{{ uploading.pictures }} file@{{ uploading.pictures == 1 ? '' : 's' }}. Please, wait...
								</span>

								&nbsp;&nbsp;&nbsp;&nbsp;

								<a href="/ha-job/@{{ user.id }}" target="_blank" class="btn btn-default btn-sm">
									<i class="fa fa-image"></i>
									See Your Pictures
								</a>
							</div>

							<div class="form-group">
								<div class="images-preview" ng-repeat="picture in pictures">
									<img src="@{{ picture.url }}" alt="" />
									<div class="removeIcon" ng-click="removePicture($index)" >
										<i class="fa fa-times" aria-hidden="true"></i>
									</div>
								</div>
							</div>

							<hr />

							<div class="form-group" ng-show="user.company_status == 'verified'">
								<button class="btn btn-primary" type="submit" ng-click="save()">{{ __('Save') }}</button>
							</div>
							
							<div class="form-group" ng-show="user.company_status != 'verified'">
								<button class="btn btn-primary" type="submit" ng-click="saveBeforeActivation()">{{ __('Save') }}</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-12 col-md-6">
			<div class="referral_section" ng-show="show_referral && ! list.length">
				<form name="form" novalidate="novalidate">
					<div class="ref-block">
						<div class="ref-title text-center">
							<h4>Want 3 Free Months? Refer 1 Friend</h4>
						</div>
	
						<div class="ref-body">					
							<p>
								<b>1.</b> Just enter your friend’s name + contact info<br />
								<b>2.</b> If they sign up, you get 3 free months<br />
								<b>3.</b> 3 Friends - 1 year free
							</p>
	
							<div class="form-group">
								<label>{{ __("Friend's Name:") }}</label>
								<input type="text" name="name" class="form-control" ng-model="referral.name" placeholder="{{ __('Enter name here...') }}" required="required" />
							</div>
	
							<div class="form-group">
								<label>{{ __("Friend's Email or Number:") }}</label>
								<input type="text" name="contacts" class="form-control" ng-model="referral.contacts" placeholder="{{ __('Enter number or email here...') }}" required="required" />
							</div>
	
							<button class="btn btn-default" ng-click="registerReferral()">{{ __('Send') }}</button>
						</div>
					</div>
				</form>
			</div>

			<div class="panel panel-default" ng-show="list.length">
				<div class="panel-heading">
					<strong>{{ __('Leads') }}</strong>
					<strong class="pull-right">{{ __('Pause Followups') }}</strong>
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
									<span class="small-italic">(@{{ item.created_at_string}})</span>
								</div>
								<div>
									@{{ item.firstname }}
									@{{ item.lastname }}
									<i ng-show="item.inbox" class="fa fa-check-circle-o text-success" aria-hidden="true" uib-tooltip="Lead texted a reply"></i>
									<span> </span>
									<i ng-show="item.clicked" class="fa fa-check-circle-o text-info" aria-hidden="true" uib-tooltip="Link clicked"></i>
									<span> </span>
									<i ng-show="item.hapage" class="fa fa-check-circle-o text-danger" aria-hidden="true" uib-tooltip="Lead visited HA Page"></i>
								</div>
							</div>
							<div class="pause-followup pull-right">
								<label class="ui-switch ui-switch-success ui-switch-sm">
									<input type="checkbox" ng-click="disableFollowup(item.id)" ng-model="item.followup_disabled" ng-true-value="1" ng-false-value="0" />
									<i></i>
								</label>
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