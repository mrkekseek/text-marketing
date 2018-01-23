<div class="page page-table" data-ng-controller="SettingsCtrl" data-ng-init="init()">
	<h2>
		{{ __('Settings') }}
	</h2>
	<div class="row">
		<div class="col-md-9 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label>{{ __('Follow-Up Text') }}</label>
								<div text-area ng-model="settings.followup_text" btn-link="true" company="companyName"></div>
							</div>
							
							<div class="form-group">
					    		<button type="button" class="btn btn-primary" ng-click="save();">{{ __('Save') }}</button>
				    		</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>