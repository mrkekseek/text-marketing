<div class="page page-table" data-ng-controller="SurveysCtrl" ng-init="initPartners()">
	<h2>
		<span>{{ __('Partners') }}</span>
		<i class="fa fa-question-circle-o help-icon" uib-tooltip-template="'surveysTooltip.html'" tooltip-placement="right-top" aria-hidden="true"></i>
	</h2>

	<div class="row">
		<div class="col-sm-3 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="clients">
						<form name="form_client">
							<div class="form-group">
								<button type="button" ng-show=" ! open_edit" class="btn btn-default child-button btn-block" ng-click="openClient()">
									<i class="fa fa-plus-circle" aria-hidden="true"></i> {{ __('Add New Client') }}
								</button>

								<div ng-show="open_edit">
									<div class="form-group search-group">
										<i class="fa fa-user search-icon" aria-hidden="true"></i>
										<input type="text" name="firstname" ng-model="client.firstname" class="form-control" placeholder="{{ __('First Name') }}" required="required" />
									</div>

									<div class="form-group search-group">
										<i class="fa fa-user-o search-icon" aria-hidden="true"></i>
										<input type="text" name="lastname" ng-model="client.lastname" class="form-control" placeholder="{{ __('Last Name') }}" />
									</div>

									<div class="form-group search-group">
										<i class="fa fa-phone search-icon" aria-hidden="true"></i>
										<div class="input-group">
											<span class="input-group-addon" id="basic-addon1">+1</span>
											<input type="text" name="phone" ng-model="client.view_phone" class="form-control" placeholder="{{ __('Phone Number') }}" required="required" />
										</div>
									</div>

									<div class="form-group search-group">
										<i class="fa fa-envelope-o search-icon" aria-hidden="true"></i>
										<input type="email" name="email" ng-model="client.email" class="form-control" placeholder="{{ __('Email') }}" required="required" />
									</div>

									<div class="form-group text-right">
										<button type="button" class="btn btn-default" ng-click="open_edit = ! open_edit">{{ __('Cancel') }}</button>
										<button type="button" class="btn btn-primary" ng-click="saveClient()">{{ __('Save') }}</button>
									</div>
								</div>
							</div>
						</form>

						<div ng-show=" ! open_edit" ng-repeat="(key, client) in clients" class="phones-list">
							<div class="divider divider-dashed"></div>

							<div class="relative" ng-class="{'active': active_client.id == client.id}">
								<div class="phones" ng-click="selectClient(client);">
									<div class="checker-client">
										<i class="choose-list fa" ng-class="{'fa-check-circle-o selected': isSelected(client), 'fa-circle-o': ! isSelected(client)}"></i>
									</div>

									<div class="client-item">
										<strong>@{{ client.view_phone }}</strong>
										<span class="small-italic pull-right" ng-show="client.email">email: @{{ client.email }}</span>
										<br />
										@{{ client.firstname }}
										@{{ client.lastname }}
									</div>
								</div>

								<a href="javascript:;" class="a-icon text-success icon-client icon-edit" ng-click="editClient(client)"><i class="fa fa-pencil"></i></a>
								<a href="javascript:;" class="a-icon text-danger icon-client icon-remove" ng-click="removeClient(client.id)"><i class="fa fa-trash"></i></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-9 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<div uib-alert class="alert-info" ng-show=" ! clients.length">
						{{ __('To send Review messages You need to add clients first') }}
					</div>

					<div ng-show="clients.length">
						<div uib-alert class="alert-info" ng-show=" ! selectedClients.length">
							{{ __('To send Review message select clients from the list at the right side') }}
						</div>

						<div ng-show="selectedClients.length">
							<div class="form-group">
								{{ __('Review will be send to:') }}
								<span ng-repeat="c in selectedClients">
									<b>@{{ c.firstname + ' ' + c.lastname }}</b><b ng-show="$index < selectedClients.length - 1">, </b>
								</span>
							</div>

							<div>
								<label class="ui-radio">
									<input name="surveySchedule" ng-model="surveySchedule" type="radio" value="0" />
									<span>{{ __('Send Now') }}</span>
								</label>

								<label class="ui-radio">
									<input name="surveySchedule" ng-model="surveySchedule" type="radio" value="1" />
									<span>{{ __('Schedule Send') }}</span>
								</label>

								<div ng-show="surveySchedule == 1">
									<div class="calendar-box">
										<span class="input-group">
											<input type="text" class="form-control" ng-model="seanceDate" uib-datepicker-popup="dd-MMMM-yyyy" is-open="popup.popup_date" datepicker-options="dateOptions" close-text="Close" />
											<span class="input-group-btn">
												<button type="button" class="btn btn-default" ng-click="openDate()"><i class="glyphicon glyphicon-calendar"></i></button>
											</span>
										</span>
									</div>

									<div class="time-box">
										<div uib-timepicker ng-model="seanceTime" hour-step="1" minute-step="1" show-meridian="false"></div>
									</div>
								</div>
							</div>
						</div>

						<div class="divider divider-dashed"></div>
					</div>

					<div ng-show="selectedClients.length">
						<div class="form-group">
							<div ng-show="partners.length" class="btn-group" uib-dropdown dropdown-append-to-body>
								<button id="btn-append-to-body" type="button" class="btn btn-default" uib-dropdown-toggle>
									<span ng-show="partner.id">@{{ partner.firstname + ' ' + partner.lastname }}</span>
									<span ng-show=" ! partner.id">{{ __('Select Partner from a list') }}</span>
									<span class="caret"></span>
								</button>

								<ul class="dropdown-menu" uib-dropdown-menu role="menu" aria-labelledby="btn-append-to-body">
									<li role="menuitem" ng-repeat="partner in partners"><a href="javascript:;" ng-click="setPartner(partner)">@{{ partner.firstname + ' ' + partner.lastname }}</a></li>
								</ul>
							</div>

							<span ng-show="partner.id">
								<button type="button" class="btn btn-default" ng-click="addPartner(partner.id)">{{ __('Edit') }}</button>
								<button type="button" class="btn btn-default" ng-click="removePartner(partner.id)">{{ __('Remove') }}</button>
							</span>

							<span ng-show="partners.length">&nbsp;&nbsp;{{ __('or') }}&nbsp;&nbsp;</span>

							<button type="button" class="btn btn-primary" ng-click="addPartner()">{{ __('Create New Partner') }}</button>
						</div>

						<form name="form" ng-show="partner.id">
							<div class="divider divider-dashed"></div>

							<div class="form-group url-block" ng-repeat="input in partner.urls">
								<div class="row" ng-show="input.default">
									<div class="col-sm-3">
										<div class="span-url">
											<img src="https://www.google.com/s2/favicons?domain=@{{ input.default == '1' ? ('http://' + input.name + '.com') : input.url }}" alt="" />
											<strong>@{{ input.name }}</strong>
										</div>
									</div>

									<div class="col-sm-6">
										<input type="text" name="url" class="form-control" ng-model="input.url" />
									</div>

									<div class="col-sm-3">
										<div class="switch-cell">
											<label class="ui-switch ui-switch-success ui-switch-sm url-switch">
												<input type="checkbox" ng-model="input.active" ng-change="activeUrl(input)" ng-true-value="1" ng-false-value="0" />
												<i></i>
											</label>
										</div>
									</div>
								</div>
							</div>

							<div>
								<button type="button" class="btn btn-primary" ng-click="saveUrls()">{{ _('Save Review Sites') }}</button>
							</div>
						</form>

						<div class="divider divider-dashed"></div>

						<div uib-alert class="alert-info" ng-show=" ! partner.id">
							{{ __('Choose from list or create new Partner to send Reviews') }}
						</div>

						<div ng-show="partner.id">
							<div class="row form-group">
								<div class="col-sm-6 col-xs-12">
									<div class="panel panel-default">
										<div class="panel-heading">
											<b>{{ __('Text') }}</b>
										</div>

										<div class="panel-body">
											<div class="form-group">
												<label class="ui-switch ui-switch-success ui-switch-sm">
													<input type="checkbox" ng-model="seanceText" id="seanceText" ng-true-value="1" ng-false-value="0" />
													<i></i>
												</label>
												<label for="seanceText" class="team-leader">{{ __('Send Text Message') }}</label>
											</div>

											<div ng-show="seanceText == '1'">
												<div class="form-group">
													<label>{{ __('Company Name') }}</label>
													<div class="input-group">
														<input type="text" class="form-control" maxlength="32" ng-model="partner.company_name" ng-change="companyChange()" placeholder="{{ __('Company Name') }}" />
														<span class="input-group-addon bg-success" ng-show="partner.company_status == 'verified' && ! companyChanged">{{ __('Verified') }}</span>
														<span class="input-group-addon bg-warning" ng-show="partner.company_status == 'pending' && ! companyChanged">{{ __('Pending') }}</span>
														<span class="input-group-addon bg-danger" ng-show="partner.company_status == 'denied' && ! companyChanged">{{ __('Denied') }}</span>
														<span class="input-group-btn" ng-show="partner.company_status == '' || companyChanged">
															<button class="btn btn-default" ng-click="companySave()">{{ __('Save') }}</button>
														</span>
													</div>
												</div>

												<div ng-show="partner.company_status == 'verified' && ! companyChanged">
													<div class="form-group">
														<label>{{ __('SMS Text') }}</label>
														<char-set ng-model="survey.text" unique-id="'text'" max-firstname="maxChars('firstname')" max-lastname="maxChars('lastname')" company="partner.company_name" btn-firstname="true" btn-lastname="true" btn-link="true" lms="true"></char-set>
													</div>

													<button type="button" class="btn" ng-click="saveSurveyText()" ng-disabled=" ! textChanged" ng-class="{'btn-default': ! textChanged, 'btn-primary': textChanged}">{{ __('Update SMS Text') }}</button>
												</div>

												<div uib-alert class="alert-info" ng-show="partner.company_status != 'verified' || companyChanged">
													{{ __('To send texts you should save Company Name and wait untill it will be verified. It may takes 15 minutes') }}
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="col-sm-6 col-xs-12">
									<div class="panel panel-default">
										<div class="panel-heading">
											<b>{{ __('Email') }}</b>
										</div>

										<div class="panel-body">
											<div class="form-group">
												<label class="ui-switch ui-switch-success ui-switch-sm">
													<input type="checkbox" ng-model="seanceEmail" id="seanceEmail" ng-true-value="1" ng-false-value="0" />
													<i></i>
												</label>
												<label for="seanceEmail" class="team-leader">{{ __('Send Email') }}</label>
											</div>

											<div ng-show="seanceEmail == '1'">
												<div class="form-group">
													<label>{{ __('Sender Name') }}</label>
													<input type="text" class="form-control" ng-change="changeEmail()" ng-model="survey.sender" placeholder="{{ __('Sender Name') }}" />
												</div>

												<div class="form-group">
													<label>{{ __('Subject Line') }}</label>
													<input type="text" class="form-control" ng-change="changeEmail()" ng-model="survey.subject" placeholder="{{ __('Subject Line') }}" />
												</div>
												
												<div class="form-group">
													<label>{{ __('Email Text') }}</label>
													<char-set ng-model="survey.email" unique-id="'email'" btn-firstname="true"></char-set>
												</div>

												<button type="button" class="btn" ng-click="saveSurveyEmail()" ng-disabled=" ! emailChanged" ng-class="{'btn-default': ! emailChanged, 'btn-primary': emailChanged}">{{ __('Update Email Data') }}</button>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div ng-show="seanceText == '1' || seanceEmail == '1'">
								<button class="btn btn-primary" ng-click="send()">
									<span>{{ __('Send Review as') }}</span>
									<span ng-show="seanceText == '1'">{{ __('Text') }}</span>
									<span ng-show="seanceText == '1' && seanceEmail == '1'">{{ __('and') }}</span>
									<span ng-show="seanceEmail == '1'">{{ __('Email') }}</span>
								</button>
								<i class="fa fa-question-circle-o help-send-icon" uib-tooltip-template="'sendTooltip.html'" tooltip-placement="right" aria-hidden="true"></i>
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

<script type="text/ng-template" id="PartnersCreate.html">
	<form name="form_partner" method="post" novalidate="novalidate">
		<div class="modal-header">
			<h4 class="modal-title" ng-show=" ! partner.id">{{ __("Create New Partner") }}</h4>
			<h4 class="modal-title" ng-show="partner.id">{{ __("Edit Partner") }}</h4>
		</div>

		<div class="modal-body">
			<div class="row">
				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label>{{ __("First Name") }}</label>
						<input type="text" name="firstname" class="form-control" ng-model="partner.firstname" required="required" />
					</div>

					<div class="form-group">
						<label>{{ __("Last Name") }}</label>
						<input type="text" class="form-control" ng-model="partner.lastname" />
					</div>
				</div>

				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label>{{ __("Email") }}</label>
						<input type="email" class="form-control" name="email" ng-model="partner.email" required="required" />
					</div>

					<div class="form-group">
						<label>{{ __("Phone") }}</label>
						<input type="text" class="form-control" name="view_phone" ng-model="partner.view_phone" />
					</div>
				</div>
			</div>
		</div>

		<div class="modal-footer">
			<button type="submit" class="btn btn-primary" ng-click="save()">{{ __('Save') }}</button>
			<button type="button" class="btn btn-default" ng-click="cancel()">{{ __('Cancel') }}</button>
		</div>
	</form>
</script>