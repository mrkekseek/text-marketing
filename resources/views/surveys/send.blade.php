<div class="page page-table" data-ng-controller="SurveysCtrl" ng-init="init()">
	<h2>
		<span>{{ __('Send') }}</span>
		<i class="fa fa-question-circle-o help-icon" uib-tooltip-template="'surveysTooltip.html'" tooltip-placement="right-top" aria-hidden="true"></i>
	</h2>
	<div class="row">
		<div class="col-sm-3">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="clients">
						<form name="form_client">
							<div class="form-group">
								<button type="button" ng-show="!open_edit" class="btn btn-default child-button btn-block" ng-click="openClient()">
									<i class="fa fa-plus-circle" aria-hidden="true"></i> {{ __('Add New Client') }}
								</button>
								<div ng-show="open_edit">
									<div class="form-group search-group">
										<i class="fa fa-user search-icon" aria-hidden="true"></i>
										<input name="firstname" ng-model="client_firstname" class="form-control" type="text" placeholder="{{ __('First Name') }}" required="required" />
									</div>
									<div class="form-group search-group">
										<i class="fa fa-user-o search-icon" aria-hidden="true"></i>
										<input name="lastname" ng-model="client_lastname" class="form-control" type="text" placeholder="{{ __('Last Name') }}" />
									</div>
									<div class="form-group search-group">
										<i class="fa fa-phone search-icon" aria-hidden="true"></i>
										<input name="phone" ng-model="client_phone" class="form-control" type="text" placeholder="{{ __('Phone Number') }}" required="required" />
									</div>
									<div class="form-group search-group">
										<i class="fa fa-envelope-o search-icon" aria-hidden="true"></i>
										<input name="email" ng-model="client_email" class="form-control" type="email" placeholder="{{ __('Email') }}" required="required" />
									</div>
									<div class="form-group text-right">
										<button type="button" class="btn btn-default" ng-click="open_edit = !open_edit">{{ __('Cancel') }}</button>
										<button type="button" class="btn btn-primary" ng-click="save()">{{ __('Save') }}</button>
									</div>
								</div>
							</div>
						</form>

						<div ng-repeat="(key, client) in clients" class="phones-list">
							<div class="divider divider-dashed"></div>
							<div class="phones" ng-click="setClient(client.id);" ng-class="{'active': active_client.id == client.id}">
								<div>
									<input type="checkbox" />
								</div>
								<div>
									<strong>@{{ client.view_phone }}</strong>
									<span class="small-italic pull-right" ng-show="client.email"> email: @{{ client.email }}</span>
									<br />
									@{{ client.firstname }}
									@{{ client.lastname }}
									<span ng-show="!client.firstname && !client.lastname">{{ __('Anonymos') }}</span>
									<span class="small-italic pull-right">@{{ client.clients_create }}</span>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-9">
			<div class="panel panel-default">
				<div class="panel-body">
					<div ng-show="active_client.id">
						<div>
							<h3>
								<strong>@{{ active_client.firstname }} @{{ active_client.lastname }}</strong>
							</h3>
						</div>
						<div ng-show="active_client.id">
							<button type="button" class="btn btn-default" ng-click="edit(active_client)">
								<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
								{{ __('Edit') }}
							</button>
							<button ng-show="user.teams_leader" type="button" class="btn btn-default ng-hide" ng-click="remove(active_client.id)">
								<i class="fa fa-trash-o" aria-hidden="true"></i>
								{{ __('Remove') }}
							</button>
						</div>
						<div class="divider divider-dashed"></div>
					</div>
					<div ng-show="active_client.id">
						<div class="row">
							<div class="col-sm-6">
								<div class="panel panel-default">
									<div class="panel-heading">
										<strong>@{{ user.firstname }} {{ __(' - your contractor') }}</strong>
										<i class="fa fa-question-circle-o help-icon-review" uib-tooltip="Here you can customize the Review text. Make sure to choose a CompanyName (which is the first words of the text, the name of who it is coming from. Also make sure not to erase the Link at the end, that is the link they click to go to the Star Rating Question." tooltip-placement="right" aria-hidden="true"></i>
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="col-sm-6">
												<label class="ui-radio">
													<input name="surveys_schedule" ng-model="surveys_schedule" type="radio" value="0">
													<span>{{ __('Send Now') }}</span>
												</label>
												<br />
												<label class="ui-radio">
													<input name="surveys_schedule" ng-model="surveys_schedule" type="radio" value="1">
													<span>{{ __('Schedule Send') }}</span>
												</label>
											</div>
											<div class="col-sm-6">
												<div ng-show="surveys_schedule == 1">
													<span class="input-group">
														<input type="text" class="form-control" ng-model="seance_date" uib-datepicker-popup="dd-MMMM-yyyy" is-open="popup.popup_date" datepicker-options="dateOptions" close-text="Close" />
														<span class="input-group-btn">
											            	<button type="button" class="btn btn-default" ng-click="openDate()"><i class="glyphicon glyphicon-calendar"></i></button>
														</span>
													</span>
													<div uib-timepicker ng-model="seance_time" ng-change="changed()" hour-step="1" minute-step="10" show-meridian="ismeridian"></div>
												</div>
											</div>
										</div>
										<div class="divider divider-dashed"></div>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="panel panel-default">
									<div class="panel-heading">
										<strong>{{ __('completed surveys') }}</strong>
									</div>
									<div class="panel-body">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/ng-template" id="surveysTooltip.html">
	<span>{{ __('This is where you Add New Clients, put in their name and number, and Send the text asking for the star rating. You can Send Now or Schedule the text to send later. You can also email them if you choose.') }}</span>
	<img src="/img/survey_star_help.png" class="img-responsive" />
</script>