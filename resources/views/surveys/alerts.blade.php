<div class="page page-table ng-scope" ng-controller="MarketingSettingsCtrl" >
	<h2>
		Alerts		<i class="fa fa-question-circle-o help-icon" uib-tooltip="Here you can enter your email to be alerted when new Star Ratings come in. You can set it so you are alerted when a 5 star comes in, or a 1 star, or however you want. You can also set it so you are sent an email right away, or once a day, or however you want." tooltip-placement="right" aria-hidden="true"></i>
	</h2>

	<div class="row">
		<div class="col-sm-8 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<form name="form" class="ng-pristine ng-valid">
						<div class="row">
							<div class="col-sm-3">
								<strong>Email Addresses</strong>
							</div>

							<div class="col-sm-9">
								<div class="form-group" ng-repeat="item in emails track by $index">
									<div class="input-group">
										<input type="text" name="email-1" class="form-control" ng-model="emails[$index]" placeholder="Enter email here...">
										<span class="input-group-btn ng-scope">
											<button class="btn btn-default" type="button" ng-click="removeEmail($index);">
												<i class="fa fa-minus" aria-hidden="true"></i>
											</button>
										</span>
									</div>
								</div>

								<div class="form-group">
									<div class="input-group">
										<input type="text" name="email-1" class="form-control" ng-model="email" placeholder="Enter email here...">
										<span class="input-group-btn ng-scope">
											<button class="btn btn-default" type="button" ng-click="addEmail(email);">
												<i class="fa fa-plus" aria-hidden="true"></i>
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
					    			<span class="pull-left">Get email when</span>
				    			</label>
					    		<div class="col-sm-9">
						    		<select class="form-control" ng-model="user.users_alerts_stars">
						    			<option value="1">1 star Overall review comes in</option>
						    			<option value="2">2 star (and below) Overall review comes in</option>
						    			<option value="3">3 star (and below) Overall review comes in</option>
						    			<option value="4">4 star (and below) Overall review comes in</option>
						    			<option value="5">5 star (and below) Overall review comes in</option>
						    		</select>
					    		</div>
				    		</div>
				    		<div class="divider divider-dashed divider-lg pull-in"></div>

				    		<div class="form-group">
					    		<label class="col-sm-3 control-label">
					    			<span class="pull-left">How often</span>
				    			</label>
					    		<div class="col-sm-9">
						    		<select class="form-control" ng-model="user.users_alerts_often">
						    			<option value="0" selected="selected">Receive alerts as they come</option>
						    			<option value="1">Once an hour</option>
						    			<option value="2">Once every 2 hours</option>
						    			<option value="3">Once every 3 hours</option>
						    			<option value="4">Once a day</option>
						    			<option value="5">Once every 2 days</option>
						    			<option value="6">Once a week</option>
						    		</select>
					    		</div>
				    		</div>
			    		</div>

			    		<div class="divider divider-dashed divider-lg pull-in"></div>

			    		<div>
			    			<button type="submit" class="btn btn-primary" ng-click="save();">Save Alerts</button>
			    		</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>