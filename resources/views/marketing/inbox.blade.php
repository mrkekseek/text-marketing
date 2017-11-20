<div class="page page-table page-fixed ng-scope" ng-controller="MarketingSettingsCtrl">

	<h2>
		Inbox
		<i class="fa fa-question-circle-o help-icon" uib-tooltip="If one of your contacts texts you back, this is where the replies go." tooltip-placement="right" aria-hidden="true"></i>
	</h2>
	<div class="dialogs panel panel-default">
		<div class="content-loader ng-hide" ng-show="request_finish" >
			<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
		</div>
		<div class="phones-body hidden-xs">
		</div>
		<div class="dialogs-body">
			<div class="send-group">
				<form name="form_chat" class="ng-pristine ng-invalid ng-invalid-required">
					<div class="chat-text chars-area">
						<textarea name="messages_text" class="form-control area-resize"
							ng-model="messages_text" placeholder="Enter your text here..." required="required">
						</textarea>
						<span><span ng-bind="messages_text.length">0</span> / <span >130</span></span>
					</div>

					<div class="chat-button">
						<button type="button" class="btn btn-block send-btn btn-primary">
							<i class="fa fa-envelope-o" aria-hidden="true"></i>
							<span class="hidden-xs">
								<span class="">Send</span>
							</span>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>