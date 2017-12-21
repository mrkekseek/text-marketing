<div class="page page-tables" data-ng-controller="MarketingOutboxCtrl" data-ng-init="init()">
	<h2>
		{{ __('Outbox') }}		
		<i class="fa fa-question-circle-o help-icon" uib-tooltip="Once you send or schedule a text, you will see them here." tooltip-placement="right" aria-hidden="true"></i>
	</h2>

	<div class="row">
		<div class="col-xs-12">
			<div class="tab-content">
				<div class="tab-panel">
					<div ng-show="list.length">
						<div ng-repeat="item in list track by $index">
							<div class="item-panel" ng-class="{'active': selectedMessage.id == item.id}">
								<div class="action-div" ng-click="choose($index)">
								</div>
								<div class="row-name">
									<span class="small-name" ng-show=" ! item.editable">@{{ item.text }} </span>
									<a href="/marketing/add/@{{ item.id }}" class="a-icon text-success">
										<i class="fa fa-pencil"></i>
									</a>
									<a href="javascript:;" class="a-icon text-danger" ng-click="remove($index)">
										<i class="fa fa-trash"></i>
									</a>
									<span class="small-italic a-icon" ng-click="choose($index)">{{ __('Click to see report') }}</span>
								</div>

								<div class="row-info">
									<span class="messages-info-send">
										<span>Will be sent on @{{ item.sendDate | date: 'MMMM d' }}th at @{{ item.sendDate | date: 'h:mm a' }}</span>
									</span>

									<span>0 lists / 0 numbers.</span>
									
									<div class="row-info-item pull-right">
										<label class="ui-switch ui-switch-success ui-switch-sm">
											<input type="checkbox" ng-model="item.active">
											<i></i>
										</label>
										<span class="team-leader">{{ __('Active Message') }}</span>
									</div>
								</div>

								<div ng-show="selected == $index && ! item.isSended" class="alert-info alert" role="alert">
									<div>
										This message wasn't sent yet
									</div>
								</div>
							</div>
							<div ng-show="$index < (list.length - 1)" class="divider divider-dashed divider-sm pull-in"></div>
						</div>
					</div>
					<div class="alert-info alert" ng-show=" ! list.length" role="alert">
						<div>
							{{ __("You haven't any messages yet.") }} <a href="/marketing/add/">{{ __('Create New Message') }}</a> {{ __('now') }}						
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>