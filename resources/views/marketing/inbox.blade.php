<div class="page page-table page-fixed" ng-controller="MarketingInboxCtrl" data-ng-init="init()">
	<h2>
		{{ __('Inbox') }}
		<i class="fa fa-question-circle-o help-icon" uib-tooltip="{{ __('If one of your contacts texts you back, this is where the replies go.') }}" tooltip-placement="right" aria-hidden="true"></i>
	</h2>

	<div class="dialogs panel panel-default">
		<div class="phones-body hidden-xs">
			<div class="btn-body text-center">
				<div>
					<div class="search-group">
						<i class="fa fa-search search-icon" aria-hidden="true"></i>
						<input ng-model="search.$" class="form-control" type="text" placeholder="{{ __('Search from list...') }}" />
					</div>
				</div>
			</div>
			<div ng-repeat="(key, client) in dialogs | filter: search">
				<div class="divider divider-dashed"></div>
				<div class="phones" ng-click="setClient(client)" ng-class="{'active': client.clients_id == activeClient.clients_id}">
					<div class="row">
						<div class="col-sm-10">
							<div>
								<strong>@{{ client.view_phone }}</strong>
								<span class="small-italic">{{ __("from") }} @{{ client.source }}</span>
							</div>
							<div class="phone-name">
								@{{ client.firstname }}
								@{{ client.lastname }}
							</div>
						</div>
						<div class="col-sm-2 text-right">
							<span class="badge badge-primary">@{{ client.count }}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="dialogs-body">
			<div class="chat-body" scroll-bottom="messages">
				<div class="chat-wrap">
					<div ng-repeat="message in messages">
						<div class="message-row">
							<div class="message-avatar text-center">
								<i ng-show="message.my" class="fa fa-user-circle fa-2x" aria-hidden="true"></i>
								<i ng-show="! message.my" class="fa fa-commenting-o fa-2x" aria-hidden="true"></i>
							</div>
							<div class="message-body">
								@{{ message.text }}
								<div class="text-right">
									<span class="small-italic">@{{ message.created_at }}</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="send-group">
				<form name="form_chat">
					<div class="chat-text chars-area" ng-class="{'danger': charsCount(messages_text) > max_text_len}">
						<textarea name="messages_text" class="form-control area-resize" ng-model="messages_text" placeholder="{{ __("Enter your text here...") }}" required="required"></textarea>
						<span>
							<span ng-show="charsCount(messages_text) > max_text_len">{{ __('3 messages') }} </span>
							<span ng-bind="charsCount(messages_text)">0</span> / 
							<span ng-show="charsCount(messages_text) <= max_text_len" ng-bind="max_text_len">140</span>
							<span ng-show="charsCount(messages_text) > max_text_len" ng-bind="max_lms_text_len">140</span>
						</span>
					</div>
					<div class="chat-button">
						<button type="button" class="btn btn-block send-btn" ng-class="{'btn-default': sent, 'btn-primary': ! sent}" ng-click="send();">
							<i class="fa fa-envelope-o" aria-hidden="true"></i>
							<span class="hidden-xs">
								<span ng-show="! sent">{{ __("Send") }}</span>
								<span ng-show="sent">{{ __("Sent!") }}</span>
							</span>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>