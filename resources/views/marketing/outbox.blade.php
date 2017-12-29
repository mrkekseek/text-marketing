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
										<span ng-show="item.lastText.send_at < item.lastText.created_at">{{ __('Sent on') }} @{{ item.lastText.send_at | date: 'MMMM d' }}@{{ getSuffix(item.lastText.send_at | date: 'd') }}</span>
										<span ng-show="item.lastText.send_at >= item.lastText.created_at">{{ __('Will be sent on') }} @{{ item.lastText.send_at | date: 'MMMM d' }}@{{ getSuffix(item.lastText.send_at | date: 'd') }}</span>
										<span>at @{{ item.lastText.send_at | date: 'h:mm a' }}</span>
									</span>

									<span>@{{ item.countList }} {{ __('lists') }} / @{{ item.lastText.receivers.length }} {{ __('numbers') }}.</span>
									
									<div class="row-info-item pull-right">
										<label class="ui-switch ui-switch-success ui-switch-sm">
											<input type="checkbox" ng-model="item.active" ng-click="changeActive(item)">
											<i></i>
										</label>
										<span class="team-leader">{{ __('Active Message') }}</span>
									</div>
								</div>

								<div ng-show="item.id == selectedMessage.id">
									<div class="item-panel panel-child">
										<div class="phones-details-wrap small-italic">
											<div ng-repeat="receiver in item.lastText.receivers">
												<i ng-show=" ! receiver.success" class="fa fa-exclamation-triangle text-orange"></i>
												<i ng-show="receiver.success" class="fa fa-check-circle-o text-success"></i>
												<strong>@{{ clients[receiver.client_id].view_phone }}: </strong> 
												<span ng-show="receiver.message">@{{ receiver.message }}</span>
												<span ng-show="! receiver.message && receiver.success">{{ __('Messsage successfully sent.') }}</span>
												<span ng-show="! receiver.message && ! receiver.success">{{ __('Wait for report.') }}</span>
											</div>
										</div>
									</div>
									<div ng-show="item.texts.length">
										<div class="prev-title" ng-click="textsToggle($index)">
											{{ __('Previous messages') }}
											<span class="small-italic a-icon">{{ __('Click to see report') }}</span>
										</div>
										<div ng-repeat="texts in item.texts" class="prev-text" ng-show="item.id == selectedTexts.id">
											{{ __('Sent on ') }} @{{ texts.send_at | date: 'MMMM d' }}@{{ getSuffix(texts.send_at | date: 'd') }}
											<span>at @{{ texts.send_at | date: 'h:mm a' }}</span>
											<div class="item-panel panel-child">
												<div class="phones-details-wrap small-italic">
													<div ng-repeat="other in texts.receivers">
														<i ng-show=" ! other.success" class="fa fa-exclamation-triangle text-orange"></i>
														<i ng-show="other.success" class="fa fa-check-circle-o text-success"></i>
														<strong>@{{ clients[other.client_id].view_phone }}: </strong> 
														<span ng-show="other.message">@{{ other.message }}</span>
														<span ng-show="! other.message && other.success">{{ __('Messsage successfully sent.') }}</span>
														<span ng-show="! other.message && ! other.success">{{ __('Wait for report.') }}</span>
													</div>
												</div>
											</div>
										</div>
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