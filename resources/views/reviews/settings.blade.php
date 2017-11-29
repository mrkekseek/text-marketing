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

						<div class="form-group" ng-repeat="input in inputs track by $index">
							<div class="row">
								<div class="col-sm-3">
									<label>{{ __('Name') }}</label>
									<input type="text" class="form-control" ng-model="inputs[$index].name"  />
								</div>
								<div class="col-sm-6">
									<label>{{ __('Url') }}</label>
									<input type="text" class="form-control" ng-model="inputs[$index].url" />
								</div>
								<div class="col-sm-3">
									<div><label>&nbsp;</label></div>
									<button type="button" class="btn btn-default" ng-click="addInput()" ng-if="$index == inputs.length - 1">
										<i class="fa fa-plus" aria-hidden="true"></i>
									</button>
									<button type="button" class="btn btn-default" ng-click="removeInput($index)" ng-if="$index < inputs.length - 1">
										<i class="fa fa-minus" aria-hidden="true"></i>
									</button>
									<label class="ui-switch ui-switch-success ui-switch-sm url-switch">
										<input type="checkbox" ng-model="inputs[$index].active" ng-click="changeActive(inputs[$index])" />
										<i></i>
									</label>
									<span ng-if="inputs[$index].active">{{ __('Active') }}</span>
									<span ng-if="!inputs[$index].active">&nbsp;</span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<button type="button" class="btn btn-primary" ng-click="save()">{{ __('Save Pages') }}</button>
							<i class="fa fa-question-circle-o help-icon-review" uib-tooltip="If only Google is Active, your 5 star reviewers will be redirected straight to Google Reviews. If Facebook or Yelp is Active, 5 star reviewers will be redirected to a Page where they can click and go to those. Note: Reviewer needs the Facebook/Yelp app to access those." tooltip-placement="right" aria-hidden="true"></i>
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