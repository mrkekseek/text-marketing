<div class="page page-table" ng-controller="MarketingSettingsCtrl" ng-init="init()">
	<h2>
		{{ __('Setting') }}
	</h2>

	<div class="row">
		<div class="col-md-9 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label>{{ __('Company Name') }}</label>
								<span class="fa fa-question-circle-o" uib-tooltip="Each text starts off with the Company Name, which are the first words in the text, so the receiver knows who it’s from. The limit for the Company Name is 32 characters." tooltip-placement="right">	
								</span>
								<input type="text" class="form-control" ng-model="user.company_name" placeholder="{{ __('Enter Team Name') }}" maxlength="32">
							</div>
							<label>{{ __('Your Cell for Inbox Alerts') }}</label>
							<span class="fa fa-question-circle-o" uib-tooltip="Insert your cell number here so if you get a text reply, we text your actual cell with the message you’ve received." tooltip-placement="right"></span>
							<div class="form-group" ng-repeat="input in inputs track by $index">
								<div class="input-group">
									<input type="text" name="phone-@{{$index + 1}}" class="form-control" ng-model="inputs[$index]" placeholder="{{ __('Enter phone here...') }}" />
									<span class="input-group-btn" ng-if="$index == inputs.length - 1">
										<button class="btn btn-default" type="button" ng-click="addInput(input);">
											<i class="fa fa-plus" aria-hidden="true"></i>
										</button>
									</span>
									<span class="input-group-btn" ng-if="$index < inputs.length - 1">
										<button class="btn btn-default" type="button" ng-click="removeInput($index);">
											<i class="fa fa-minus" aria-hidden="true"></i>
										</button>
									</span>
								</div>
							</div>
							<div class="form-group">
					    		<button type="button" class="btn btn-primary" ng-click="saveSettings();">{{ __('Save') }}</button>
				    		</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>