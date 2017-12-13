<div class="page page-table" ng-controller="AlertsCtrl" ng-init="init()">
	<h2>
		{{ __('Alerts') }}
		<i class="fa fa-question-circle-o help-icon" uib-tooltip="Here you can enter your email to be alerted when new Star Ratings come in. You can set it so you are alerted when a 5 star comes in, or a 1 star, or however you want. You can also set it so you are sent an email right away, or once a day, or however you want." tooltip-placement="right" aria-hidden="true"></i>
	</h2>
	<div class="row">
		<div class="col-sm-8 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<form name="form">
						<div class="row">
							<div class="col-sm-3">
								<strong>{{ __('Email Addresses') }}</strong>
							</div>
							<div class="col-sm-9">
								<div class="form-group" ng-repeat="item in inputs track by $index">
									<div class="input-group">
										<input type="text" class="form-control" ng-model="inputs[$index]" placeholder="{{ __('Enter email here...') }}" />
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
					    	</div>
				    	</div>

				    	<div class="divider divider-dashed divider-lg pull-in"></div>

				    	<div class="form-horizontal">
							<div class="form-group">
					    		<label class="col-sm-3 control-label">
					    			<span class="pull-left">{{ __('Get email when') }}</span>
				    			</label>
					    		<div class="col-sm-9">
						    		<select class="form-control" ng-model="survey.alerts_stars">
						    			<option value="1">{{ __('1 star Overall review comes in') }}</option>
						    			<option value="2">{{ __('2 star (and below) Overall review comes in') }}</option>
						    			<option value="3">{{ __('3 star (and below) Overall review comes in') }}</option>
						    			<option value="4">{{ __('4 star (and below) Overall review comes in') }}</option>
						    			<option value="5">{{ __('5 star (and below) Overall review comes in') }}</option>
						    		</select>
					    		</div>
				    		</div>
				    		<div class="divider divider-dashed divider-lg pull-in"></div>

				    		<div class="form-group">
					    		<label class="col-sm-3 control-label">
					    			<span class="pull-left">{{ __('How often') }}</span>
				    			</label>
					    		<div class="col-sm-9">
						    		<select class="form-control" ng-model="survey.alerts_often">
						    			<option value="0">{{ __('Receive alerts as they come') }}</option>
						    			<option value="1">{{ __('Once an hour') }}</option>
						    			<option value="2">{{ __('Once every 2 hours') }}</option>
						    			<option value="3">{{ __('Once every 3 hours') }}</option>
						    			<option value="24">{{ __('Once a day') }}</option>
						    			<option value="48">{{ __('Once every 2 days') }}</option>
						    			<option value="168">{{ __('Once a week') }}</option>
						    		</select>
					    		</div>
				    		</div>
			    		</div>

			    		<div class="divider divider-dashed divider-lg pull-in"></div>

			    		<div>
			    			<button type="submit" class="btn btn-primary" ng-click="save();">{{ __('Save Alerts') }}</button>
			    		</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>