<div class="page page-tables" data-ng-controller="MarketingSettingsCtrl">
	<h2>
		Outbox		
		<i class="fa fa-question-circle-o help-icon" uib-tooltip="Once you send or schedule a text, you will see them here." tooltip-placement="right" 
			aria-hidden="true"></i>
	</h2>

	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div active="active">
				<div class="tab-content">
					<div class="tab-pane active">
						<div>
							<div>
								<div class="item-panel">
									<div class="action-div" ng-click="choose()">
									</div>
									<div class="row-name">
										<span class="small-name">sdfsdfdsf </span>
										<a href="javascript:;" class="a-icon text-success"><i class="fa fa-pencil"></i></a>
										<a href="javascript:;" class="a-icon text-danger" ng-click="remove(item.messages_id)"><i class="fa fa-trash"></i></a>
										<span class="small-italic a-icon" ng-click="choose(item.messages_id)">Click to see report</span>
									</div>

									<div class="row-info">
										<span class="messages-info-send">
											<span>Will be sent on November 20th at 10:58 AM</span>
										</span>

										<span>0 lists / 0 numbers.</span>
										
										<div class="row-info-item pull-right">
											<label class="ui-switch ui-switch-success ui-switch-sm">
												<input type="checkbox">
												<i></i>
											</label>
											<span class="team-leader">Active Message</span>
										</div>
									</div>

									<div ng-show="show">
										<div class="alert-info" role="alert" >
											This message wasn't sent yet
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="content-loader" ng-show="false">
							<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
						</div>
						<div class="alert-info alert" role="alert" ng-show="false">
							<div>
								You haven't any messages yet. <a href="/messages/add/">Create New Message</a> now						
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>