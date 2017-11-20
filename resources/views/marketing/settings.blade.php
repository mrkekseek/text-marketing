<div class="page page-table ng-scope" ng-controller="MarketingSettingsCtrl" ng-init="init()">
	<h2>
		Settings	</h2>
	<div class="row">
		<div class="col-md-9 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label>Company Name</label>
								<span class="fa fa-question-circle-o" uib-tooltip="Each text starts off with the Company Name, which are the first words in the text, so the receiver knows who it’s from." tooltip-placement="right">	
								</span>
								<input type="text" class="form-control ng-pristine ng-valid ng-not-empty ng-touched" ng-model="team.company_name" placeholder="Enter Team Name">
							</div>
							<label>Your Cell for Inbox Alerts</label>
							<span class="fa fa-question-circle-o" uib-tooltip="Insert your cell number here so if you get a text reply, we text your actual cell with the message you’ve received." tooltip-placement="right"></span>
							<div class="form-group ng-scope">
								<div class="input-group">
									<input type="text" name="phone-1" class="form-control ng-pristine ng-valid ng-empty ng-touched" placeholder="Enter phone here...">
									<span class="input-group-btn ng-scope">
										<button class="btn btn-default" type="button" ng-click="add_input();">
											<i class="fa fa-plus" aria-hidden="true"></i>
										</button>
									</span>
								</div>
							</div>
							<div class="form-group">
					    		<button type="button" class="btn btn-primary" ng-click="save_alternative_name();">Save</button>
				    		</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>