<div class="page page-table" ng-controller="ReviewsSettingsCtrl" ng-init="init()">
	<h2>
		{{ __('Review Sites') }}		
		<i class="fa fa-question-circle-o help-icon" uib-tooltip-template="'thankYouTooltip.html'" tooltip-placement="right-top" aria-hidden="true"></i>
	</h2>

	<div class="row">
		<div class="col-md-9 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<form name="form">
						<div class="divider"></div>

						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label>{{ __('Name') }}</label>
								</div>

								<div class="col-sm-6">
									<label>{{ __('Url') }}</label>
								</div>

								<div class="col-sm-3">
									<label>{{ __('Enabled') }}</label>
								</div>
							</div>
						</div>

						<div class="form-group url-block" ng-repeat="input in inputs">
							<div class="row">
								<div class="col-sm-3">
									<div class="span-url" ng-show=" ! input.editable">
										<img src="https://www.google.com/s2/favicons?domain=@{{ input.url }}" alt="" />
										<strong>@{{ input.name }}</strong>
									</div>

									<div ng-show="input.editable">
										<input type="text" name="name" class="form-control" ng-model="input.name"  />
									</div>
								</div>

								<div class="col-sm-6">
									<div class="span-url">
										<a href="@{{ input.url }}" target="_blank"><strong>@{{ input.url }}</strong></a>
									</div>

									<div ng-show="false">
										<input type="text" name="url" class="form-control" ng-model="input.url" />
									</div>
								</div>

								<div class="col-sm-1">
									<div class="switch-cell">
										<label class="ui-switch ui-switch-success ui-switch-sm url-switch">
											<input type="checkbox" ng-model="input.active" ng-change="active(input)" ng-true-value="1" ng-false-value="0" />
											<i></i>
										</label>
									</div>
								</div>

								<div class="col-sm-2">
									<button type="button" class="btn btn-primary" ng-click="save(input)" ng-show="$index == (inputs.length - 1)">
										{{ __('Add') }}
									</button>

									<div class="icon-cell" ng-show="$index < (inputs.length - 1) && ! input.editable">
										<div>
											<i class="fa fa-pencil text-success" ng-click="edit(input)" aria-hidden="true"></i>
										</div>

										<div>
											<i class="fa fa-trash text-danger" ng-click="remove(input, $index)" aria-hidden="true"></i>
										</div>
									</div>

									<div class="btn-group" role="group" ng-show="input.editable && $index != inputs.length - 1">
										<button type="button" ng-click="cancel($index)" class="btn btn-default">{{ __('Cancel') }}</button>
										<button type="button" class="btn btn-primary" ng-click="save(input)">{{ __('Save') }}</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/ng-template" id="thankYouTooltip.html">
	<span>{{ __('Here is where you put in your online review sites. Google and Yelp we will do for you, but we need you to put in the Facebook page link. Then follow the prompts.') }}</span>
	<img src="/img/thank_you_help.png" class="img-responsive" />
</script>