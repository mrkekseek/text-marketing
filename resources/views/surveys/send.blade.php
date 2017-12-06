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
							<div class="phones" ng-click="setClient(client);" ng-class="{'active': active_client.id == client.id}">
								<div class="checker-client">
									<i class="choose-list fa fa-check-circle-o fa-circle-o" ng-class="client.send ? 'fa-check-circle-o selected' : 'fa-circle-o'"></i>
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
							<div class="col-sm-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										&nbsp;
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
										<div class="form-group">
											<div class="row">
												<div class="col-sm-4">
													<div>
														<label class="ui-switch ui-switch-success ui-switch-sm">
															<input type="checkbox" ng-model="seance_text" ng-true-value="'text'" ng-false-value="''" />
															<i></i>
														</label>
														<span class="team-leader">{{ __('Text') }}</span>
													</div>
													<div>
														<label class="ui-switch ui-switch-success ui-switch-sm">
															<input type="checkbox" ng-model="seance_email" ng-true-value="'email'" ng-false-value="''" />
															<i></i>
														</label>
														<span class="team-leader">{{ __('Email') }}</span>
													</div>
												</div>
												<div class="col-sm-8">
													<div class="form-group" ng-show="seance_text">
														<label>{{ __('Company Name') }}</label>
														<input type="text" class="form-control" maxlength="32" ng-model="survey.company_name" placeholder="{{ __('Company Name') }}" />
													</div>
													<div class="form-group" ng-show="seance_text">
														<label>{{ __('SMS Text') }}</label>
														<div class="chars-area" ng-class="{'danger': charsCount(survey.text) > max_text_len}">
															<textarea id="sms_text" class="form-control" ng-model="survey.text" ng-change="charsCount(survey.text)" placeholder="{{ __('SMS Text') }}"></textarea>
															<span>
																<span ng-show="charsCount(survey.text) > max_text_len">{{ __('3 messages') }}</span>
																<span ng-bind="charsCount(survey.text)">0</span> / 
																<span ng-show="charsCount(survey.text) <= max_text_len" ng-bind="max_text_len">140</span>
																<span ng-show="charsCount(survey.text) > max_text_len" ng-bind="max_lms_text_len">500</span>
																<span class="fa fa-question-circle-o" uib-tooltip="You can go over @{{ max_text_len }} characters and have @{{ max_lms_text_len }}. This will cost 3 text credits." tooltip-placement="left"></span>
															</span>
														</div>
														<div class="btn-group btn-group-justified move-top-pixel">
															<div class="btn-group">
																<button ng-click="insertMask('sms_text', '[$Link]')" ng-disabled="check_link" type="button" class="btn btn-sm btn-default">
																	<i class="fa fa-link"></i> {{ __('Short Link') }}
																</button>
															</div>
															<div class="btn-group">
																<button type="button" ng-click="insertMask('sms_text', '[$clients_firstname]')" ng-disabled="check_firstname" class="btn btn-sm btn-default">
																	<i class="fa fa-user"></i> {{ __('First Name') }}
																</button>
															</div>
														</div>
													</div>
													<div class="form-group" ng-show="seance_email">
														<div class="form-group">
															<label>{{ __('Sender Name') }}</label>
															<input type="text" class="form-control" ng-model="survey.sender" placeholder="{{ __('Sender Name') }}" />
														</div>
														<div class="form-group">
															<label>{{ __('Subject Line') }}</label>
															<input type="text" class="form-control" ng-model="survey.subject" placeholder="{{ __('Subject Line') }}" />
														</div>
														<div class="chars-area">
															<label>{{ __('Email Text') }}</label>
															<textarea id="email_text" class="form-control" ng-model="survey.email" placeholder="{{ __('Email Text') }}"></textarea>
														</div>
														<div class="btn-group btn-group-justified move-top-pixel">
															<div class="btn-group">
																<button type="button" ng-click="insertMask('email_text', '[$FirstName]')" class="btn btn-sm btn-default">
																	<i class="fa fa-user"></i> {{ __('First Name') }}
																</button>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="form-group">
											<button class="btn btn-default btn-primary" ng-click="send()">
												<span>{{ __('Send') }}</span>
											</button>
											<i class="fa fa-question-circle-o help-send-icon" uib-tooltip-template="'sendTooltip.html'" tooltip-placement="right" aria-hidden="true"></i>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-12" id="surveys-send-accordion">
								<div class="panel panel-default">
									<div class="panel-heading">
										<strong>{{ __('completed') }}</strong>
									</div>
									<div class="panel-body" >
										<uib-accordion close-others="true">
										    <div uib-accordion-group class="panel-default" ng-repeat="seance in seances">
										    	<uib-accordion-group>
										    		<uib-accordion-heading>
											    		@{{seance.created_at}}
											    		<span class="pull-right">@{{ seance.surveys_id }}</span>
											    	</uib-accordion-heading>
											    	{{ __('Content') }}
										    	</uib-accordion-group>
										    </div>
										</uib-accordion>
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

<script type="text/ng-template" id="sendTooltip.html">
	<span>{{ __("Here is an example of how the text will look, with your and your client's names of course.") }}</span>
	<img src="/img/survey_send_help.png" class="img-responsive" />
</script>