<div class="page page-table ng-scope" data-ng-controller="MarketingSettingsCtrl">
	<h2>
		Outbox		
		<i class="fa fa-question-circle-o help-icon" uib-tooltip="Once you send or schedule a text, you will see them here." tooltip-placement="right" 
			aria-hidden="true"></i>
	</h2>

	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div active="active" class="ng-isolate-scope">
				<div class="tab-content">
					<div class="tab-pane ng-scope active">
						<div>
							<div class="ng-scope">
								<div class="item-panel">
									<div class="action-div" ng-click="choose()">
									</div>
									<div class="row-name">
										<span class="small-name ng-binding">sdfsdfdsf </span>
										<a href="javascript:;" class="a-icon text-success"><i class="fa fa-pencil"></i></a>
										<a href="javascript:;" class="a-icon text-danger" ng-click="remove(item.messages_id)"><i class="fa fa-trash"></i></a>
										<span class="small-italic a-icon" ng-click="choose(item.messages_id)">Click to see report</span>
									</div>

									<div class="row-info">
										<span class="messages-info-send">
											<span class="ng-binding">Will be sent on November 20th at 10:58 AM</span>
										</span>

										<span class="ng-binding">0 lists / 0 numbers.</span>
										
										<div class="row-info-item pull-right">
											<label class="ui-switch ui-switch-success ui-switch-sm">
												<input type="checkbox" class="ng-pristine ng-untouched ng-valid ng-not-empty">
												<i></i>
											</label>
											<span class="team-leader">Active Message</span>
										</div>
									</div>

									<div  class="ng-hide" ng-show="show">
										<div class="alert-info ng-scope ng-isolate-scope alert" role="alert" >
											This message wasn't sent yet
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="content-loader ng-hide">
							<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
						</div>
						<div class="alert-info ng-scope ng-isolate-scope alert ng-hide" role="alert">
							<div>
								You haven't any messages yet. <a href="/messages/add/" class="ng-scope">Create New Message</a> now						
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>