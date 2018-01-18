<div class="page page-table" data-ng-controller="SurveysCtrl" ng-init="init()">
	<h2>
		<span>{{ __('Send') }}</span>
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
											<input type="text" name="phone" ng-model="client.view_phone" ng-readonly="client.id" class="form-control" placeholder="{{ __('Phone Number') }}" required="required" />
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
						<div class="leads">
							<div ng-show=" ! open_edit" ng-repeat="(key, client) in clients" class="phones-list">
								<div class="divider divider-dashed"></div>

								<div class="relative" ng-class="{'active': active_client.id == client.id}">
									<div class="phones" ng-click="selectClient(client);">
										<div class="checker-client">
											<i class="choose-list fa" ng-class="{'fa-check-circle-o selected': isSelected(client), 'fa-circle-o': ! isSelected(client)}"></i>
										</div>

										<div class="client-item">
											<strong>@{{ client.view_phone }}</strong>
											<span class="small-italic pull-right" ng-show="client.email">@{{ client.email }}</span>
											<br />
											@{{ client.firstname }}
											@{{ client.lastname }}
										</div>
									</div>
									<a href="javascript:;" class="a-icon text-info icon-client icon-report" ng-click="reportClient(client.id)" uib-tooltip="See reports">
										<i class="fa fa-list-alt" aria-hidden="true"></i>
									</a>
									<a href="javascript:;" class="a-icon text-success icon-client icon-edit" ng-click="editClient(client)" uib-tooltip="Edit">
										<i class="fa fa-pencil"></i>
									</a>
									<a href="javascript:;" class="a-icon text-danger icon-client icon-remove" ng-click="removeClient(client.id)" uib-tooltip="Remove">
										<i class="fa fa-trash"></i>
									</a>
								</div>
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
										<div uib-timepicker ng-model="seanceTime" hour-step="1" minute-step="1" min="timeMin" max="timeMax" show-meridian="true"></div>
									</div>
								</div>
							</div>
						</div>

						<div class="divider divider-dashed"></div>
					</div>

					<div ng-show="clients.length">
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
													<input type="text" class="form-control" maxlength="32" ng-model="user.company_name" ng-change="companyChange()" placeholder="{{ __('Company Name') }}" />
													<span class="input-group-addon bg-success" ng-show="user.company_status == 'verified' && ! companyChanged">{{ __('Verified') }}</span>
													<span class="input-group-addon bg-warning" ng-show="user.company_status == 'pending' && ! companyChanged">{{ __('Pending') }}</span>
													<span class="input-group-addon bg-danger" ng-show="user.company_status == 'denied' && ! companyChanged">{{ __('Denied') }}</span>
													<span class="input-group-btn" ng-show="user.company_status == '' || companyChanged">
														<button class="btn btn-default" ng-click="companySave()">{{ __('Save') }}</button>
													</span>
												</div>
											</div>

											<div ng-show="user.company_status == 'verified' && ! companyChanged">
												<div class="form-group">
													<label>{{ __('SMS Text') }}</label>
													<char-set ng-model="survey.text" unique-id="'text'" max-firstname="maxChars('firstname')" max-lastname="maxChars('lastname')" company="user.company_name" btn-firstname="true" btn-lastname="true" btn-link="true" lms="true"></char-set>
												</div>

												<button type="button" class="btn" ng-click="saveSurveyText()" ng-disabled=" ! textChanged" ng-class="{'btn-default': ! textChanged, 'btn-primary': textChanged}">{{ __('Update SMS Text') }}</button>
											</div>

											<div uib-alert class="alert-info" ng-show="user.company_status != 'verified' || companyChanged">
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

<script type="text/ng-template" id="surveysTooltip.html">
	<span>{{ __('This is where you Add New Clients, put in their name and number, and Send the text asking for the star rating. You can Send Now or Schedule the text to send later. You can also email them if you choose.') }}</span>
	<img src="/img/survey_star_help.png" class="img-responsive" />
</script>

<script type="text/ng-template" id="sendTooltip.html">
	<span>{{ __("Here is an example of how the text will look, with your and your client's names of course.") }}</span>
	<img src="/img/survey_send_help.png" class="img-responsive" />
</script>

<script type="text/ng-template" id="ReportsReviews.html">
	<form name="form_partner" method="post" novalidate="novalidate">
		<div class="modal-header">
			<button type="button" class="close" ng-click="cancel()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">{{ __("Reviews") }}</h4>
		</div>

		<div class="modal-body">
			<table class="table table-striped">
				<tr>
					<th>{{ __('Created at') }}</th>
					<th>{{ __('Date for review') }}</th>
					<th>{{ __('Completed') }}</th>
					<th>{{ __('Type') }}</th>
					<th>{{ __('Score') }}</th>
				</tr>
				<tr ng-repeat="report in reports">
					<td>@{{ report.created_at | date: 'MMM d h:mm a' }}</td>
					<td>@{{ report.date | date: 'MMM d h:mm a' }}</td>
					<td>
						@{{ report.completed | date: 'MMM d h:mm a' }}
						<span ng-show="! report.completed">{{ __('Not complete yet.') }}</span>
					</td>
					<td>@{{ report.type }}</td>
					<td>
						<div stars="@{{report.value}}"></div>
						<span ng-show="report.value == 0">N/A</span>
					</td>
				</tr>
			</table>
		</div>

		<div class="modal-footer">
			<button type="button" class="btn btn-default" ng-click="cancel()">{{ __('Close') }}</button>
		</div>
	</form>
</script>