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
						<div class="form-horizontal" ng-repeat="url in list">
							<div class="form-group">
								<label class="col-sm-3 control-label">
									<span class="pull-left">
										<i class="fa fa-@{{ url.name.toLowerCase() }} reviews-icons" aria-hidden="true"></i>@{{ url.name }}</span>
									</label>
								<div class="col-sm-6">
									<div ng-class="{'input-group': url.name == 'Facebook'}">
										<input name="@{{ url.name }}" type="text" class="form-control" ng-model="url.url" />
										<span class="input-group-btn" ng-show="url.name == 'Facebook'">
											<button type="button" class="btn btn-default">
												<span class="">{{ __('Login') }}</span>
											</button>
										</span>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="ui-switch ui-switch-success ui-switch-sm">
										<input type="checkbox" ng-model="url.active" ng-click="changeActive(url)" />
										<i></i>
									</label>
									<span ng-show="url.active" class="team-leader">{{ __('Active') }}</span>
									<span ng-show="!url.active">&nbsp;</span>
								</div>
							</div>
							<div class="divider divider-dashed divider-lg pull-in"></div>
						</div>

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

						<div class="form-group url-block" ng-repeat="input in inputs track by $index">
							<div class="row">
								<div class="col-sm-3">
									<div class="span-url" ng-show="! inputs[$index].editable">
										<img src="/img/icon_url_@{{ inputs[$index].id }}.ico" />
										<strong>@{{ inputs[$index].name }}</strong>
									</div>
									<div ng-show="inputs[$index].editable">
										<input type="text" class="form-control" ng-model="inputs[$index].name"  />
									</div>
								</div>
								<div class="col-sm-6">
									<div class="span-url" ng-show="! inputs[$index].editable">
										<a href="@{{ inputs[$index].url }}" target="_blank"><strong>@{{ inputs[$index].url }}</strong></a>
									</div>
									<div ng-show="inputs[$index].editable">
										<input type="text" class="form-control" ng-model="inputs[$index].url" />
									</div>
								</div>
								<div class="col-sm-1">
									<div class="switch-cell">
										<label class="ui-switch ui-switch-success ui-switch-sm url-switch">
											<input type="checkbox" ng-model="inputs[$index].active" ng-click="changeActive(inputs[$index])" />
											<i></i>
										</label>
									</div>
								</div>
								<div class="col-sm-2">
									<button type="button" class="btn btn-primary" ng-click="save(inputs[$index])" ng-if="$index == inputs.length - 1">
										{{ __('Add') }}
									</button>
									<div class="icon-cell" ng-show="$index < inputs.length - 1 && ! inputs[$index].editable">
										<div>
											<i class="fa fa-pencil text-success" ng-click="edit(inputs[$index])" aria-hidden="true"></i>
										</div>
										<div>
											<i class="fa fa-trash text-danger" ng-click="removeInput(inputs[$index], $index)" aria-hidden="true"></i>
										</div>
									</div>
									<div class="btn-group" role="group" ng-show="inputs[$index].editable && $index != inputs.length - 1">
										<button type="button" ng-click="cancel($index)" class="btn btn-default">{{ __('Cancel') }}</button>
										<button type="button" class="btn btn-primary" ng-click="save(inputs[$index])">{{ __('Save') }}</button>
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