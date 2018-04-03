<div class="page page-table new_users" data-ng-controller="NewUsersCtrl" data-ng-init="init()">
	<h2>
		{{ __('New Users') }}
	</h2>

    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
						<div class="col-sm-12" id="new_users_texts">
							<div class="form-group">
								<label>{{ __('Thank You For Signup Text') }}</label>
                                <div text-area ng-model="texts.thank_you_signup"></div>
                                <!-- <char-set ng-model="texts.thank_you_signup" class="followup_textarea" unique-id="'thankyou_text'" max-firstname="maxChars('firstname')" max-lastname="maxChars('lastname')" btn-firstname="true" btn-lastname="true" btn-link="true" btn-website="true" btn-office-phone="true"></char-set> -->
                            </div>
                            
                            <div class="form-group">
								<label>{{ __('2 Days After Signup Text') }}</label>
								<div text-area ng-model="texts.two_days_not_active"></div>
                            </div>
                            
                            <div class="form-group">
								<label>{{ __('4 Days After Signup Text') }}</label>
								<div text-area ng-model="texts.four_days_not_active"></div>
							</div>
                            
                            <div class="form-group">
								<label>{{ __('New Users Text') }}</label>
								<div text-area ng-model="texts.new_user"></div>
                            </div>
                            
                            <div class="form-group">
								<label>{{ __('Default Instant Text') }}</label>
								<div text-area ng-model="texts.instant"></div>
                            </div>
                            
                            <div class="form-group">
								<label>{{ __('Default First Followup Text') }}</label>
								<div text-area ng-model="texts.first_followup"></div>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Default First Followup Delay') }}</label>
                                <div class="followup_delay">
                                    <select class="form-control" ng-model="texts.first_followup_delay">
                                        <option value="@{{ hour.value }}" ng-repeat="hour in followup_hours">@{{ hour.text + ' ' + getHourText(hour.value) }}</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
								<label>{{ __('Default Second Followup Text') }}</label>
								<div text-area ng-model="texts.second_followup"></div>
							</div>

                            <div class="form-group">
                                <label>{{ __('Default Second Followup Delay') }}</label>
                                <div class="followup_delay">
                                    <select class="form-control" ng-model="texts.second_followup_delay">
                                        <option value="@{{ hour.value }}" ng-repeat="hour in followup_hours">@{{ hour.text + ' ' + getHourText(hour.value) }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
								<label>{{ __("'Lead Clicks' Alert Text (text to User)") }}</label>
								<div text-area ng-model="texts.lead_clicks_alert"></div>
							</div>

                            <div class="form-group">
								<label>{{ __("'Lead Reply' Alert Text (text to User)") }}</label>
								<div text-area ng-model="texts.lead_reply_alert"></div>
							</div>

                            <div class="form-group">
								<label>{{ __("Lead Clicks Link To Site (text to Lead)") }}</label>
								<div text-area ng-model="texts.lead_clicks"></div>
							</div>

                            <div class="form-group">
								<label>{{ __("User Does Not Click Link In Reply Alert (text to User)") }}</label>
								<div text-area ng-model="texts.user_click_reminder"></div>
							</div>
							
							<div class="form-group">
					    		<button type="button" class="btn btn-primary" ng-click="save()">{{ __('Save') }}</button>
				    		</div>
						</div>
					</div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>{{ __('Send texts to new users') }}</strong>
                </div>

                <div class="panel-body">
                    <div class="form-group">
                        <span class="upload-button-box">
                            <button type="button" class="btn btn-sm btn-default">
                                <i class="fa fa-list"></i> {{ __("Choose File") }}
                            </button>
                            <input ng-disabled="uploading.file > 0" onchange="angular.element(this).scope().uploadFile(event.target.files[0])" multiple="multiple" accept="csv" type="file" />
                        </span>
                    </div>

                    <div class="form-group">
                        <span>@{{ file.name }}</span>
                        <i ng-show="file.url" ng-click="removeFile()" class="fa fa-times pointer" aria-hidden="true"></i>
                        <i ng-show="request" class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-primary" ng-click="send()">{{ __('Send') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>