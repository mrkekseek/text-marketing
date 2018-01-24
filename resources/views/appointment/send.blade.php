<div class="page page-table" data-ng-controller="AppointmentCtrl" ng-init="init()">
	<h2>
		<span>{{ __('Appointment Confirmation') }}</span>
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

						<div class="leads">
							<div ng-show=" ! open_edit" ng-repeat="(key, client) in clients" class="phones-list">
								<div class="divider divider-dashed"></div>
								<div class="relative">
									<div class="phones" ng-class="{'active': client.id == selectedClient.id}" ng-click="setClient(client);">
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
		</div>

		<div class="col-sm-9 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<div uib-alert class="alert-info" ng-show=" ! clients.length">
						{{ __('To send messages You need to add clients first') }}
					</div>

					<div ng-show="clients.length">
						<div uib-alert class="alert-info" ng-show=" ! selectedClient.id">
							{{ __('To send message select client from the list at the left side') }}
						</div>

						<div ng-show="selectedClient.id">
							<div class="form-group">
								<div ng-show="employees.length" class="btn-group" uib-dropdown dropdown-append-to-body>
									<button id="btn-append-to-body" type="button" class="btn btn-default" uib-dropdown-toggle>
										<span ng-show="employee.id">@{{ employee.firstname + ' ' + employee.lastname }}</span>
										<span ng-show=" ! employee.id">{{ __('Select Employee from a list') }}</span>
										<span class="caret"></span>
									</button>

									<ul class="dropdown-menu" uib-dropdown-menu role="menu" aria-labelledby="btn-append-to-body">
										<li role="menuitem" ng-repeat="employee in employees"><a href="javascript:;" ng-click="setEmployee(employee)">@{{ employee.firstname + ' ' + employee.lastname }}</a></li>
									</ul>
								</div>

								<span ng-show="employee.id">
									<button type="button" class="btn btn-default" ng-click="addEmployee(employee.id)">{{ __('Edit') }}</button>
									<button type="button" class="btn btn-default" ng-click="removeEmployee(employee.id)">{{ __('Remove') }}</button>
								</span>

								<span ng-show="employees.length">&nbsp;&nbsp;{{ __('or') }}&nbsp;&nbsp;</span>

								<button type="button" class="btn btn-primary" ng-click="addEmployee()">{{ __('Create New Employee') }}</button>
							</div>

							<div class="divider divider-dashed"></div>

							<div ng-show="employee.id">
								<label class="ui-radio">
									<input name="appointmentSchedule" ng-model="appointmentSchedule" type="radio" value="0" />
									<span>{{ __('Send Now') }}</span>
								</label>

								<label class="ui-radio">
									<input name="appointmentSchedule" ng-model="appointmentSchedule" type="radio" value="1" />
									<span>{{ __('Schedule Send') }}</span>
								</label>

								<div ng-show="appointmentSchedule == 1">
									<div class="calendar-box">
										<span class="input-group">
											<input type="text" class="form-control" ng-model="appointmentDate" uib-datepicker-popup="dd-MMMM-yyyy" is-open="popup.popup_appointment_date" datepicker-options="dateOptions" close-text="Close" />
											<span class="input-group-btn">
												<button type="button" class="btn btn-default" ng-click="openDate('popup_appointment_date')"><i class="glyphicon glyphicon-calendar"></i></button>
											</span>
										</span>
									</div>

									<div class="time-box">
										<div uib-timepicker ng-model="appointmentTime" hour-step="1" minute-step="1" min="timeMin" max="timeMax" show-meridian="true"></div>
									</div>
								</div>

								<div class="divider divider-dashed"></div>
							</div>

							

							<div uib-alert class="alert-info" ng-show=" ! employee.id">
								{{ __('Choose from list or create new Employee to send message') }}
							</div>

							<div ng-show="employee.id">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label>{{ __('Company Name') }}</label>
											<div class="input-group">
												<input type="text" class="form-control" maxlength="32" ng-model="employee.company_name" ng-change="companyChange()" placeholder="{{ __('Company Name') }}" />
												<span class="input-group-addon bg-success" ng-show="employee.company_status == 'verified' && ! companyChanged">{{ __('Verified') }}</span>
												<span class="input-group-addon bg-warning" ng-show="employee.company_status == 'pending' && ! companyChanged">{{ __('Pending') }}</span>
												<span class="input-group-addon bg-danger" ng-show="employee.company_status == 'denied' && ! companyChanged">{{ __('Denied') }}</span>
												<span class="input-group-btn" ng-show="employee.company_status == '' || companyChanged">
													<button class="btn btпеn-default" ng-click="companySave()">{{ __('Save') }}</button>
												</span>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<img ng-show="file.url" src="@{{ file.url }}" class="preview-mms" />
										</div>
									</div>
								</div>
								<div class="divider divider-dashed"></div>
							</div>

							<div ng-show="employee.id">
								<div class="form-group">
									<div class="row">
										<div class="col-sm-6">
											<div uib-alert class="alert-info" ng-show="employee.company_status != 'verified' || companyChanged">
												{{ __('To send texts you should save Company Name and wait untill it will be verified. It may takes 15 minutes') }}
											</div>

											<div ng-show="employee.company_status == 'verified' && ! companyChanged">
												<div class="form-group">
													<textarea class="form-control appointment-textarea" readonly="readonly">@{{ createText() }}</textarea>
												</div>
											</div>
											
										</div>
										<div class="col-sm-6" ng-show="employee.company_status == 'verified' && ! companyChanged">
											<div>
												<label class="ui-radio">
													<input name="windowTime" ng-model="windowTime" type="radio" value="0" />
													<span>{{ __('Exact Time') }}</span>
												</label>

												<label class="ui-radio">
													<input name="windowTime" ng-model="windowTime" type="radio" value="1" />
													<span>{{ __('Window Time') }}</span>
												</label>
											</div>

											<div class="switch-box">
												<div uib-timepicker ng-model="time" show-meridian="true" ng-show="windowTime == 0">
												</div>
												<div class="wrap-window-time" ng-show="windowTime == 1">
													<select class="form-control window-time" ng-model="fromTime">
														<option value="@{{$index + 9}}AM" ng-repeat="item in getArray(4) track by $index">@{{$index + 9}} AM</option>
														<option value="@{{$index + 1}}PM" ng-repeat="item in getArray(9) track by $index">@{{$index + 1}} PM</option>
													</select>
													-
													<select class="form-control window-time" ng-model="toTime">
														<option value="@{{$index + 9}}AM" ng-repeat="item in getArray(4) track by $index">@{{$index + 9}} AM</option>
														<option value="@{{$index + 1}}PM" ng-repeat="item in getArray(9) track by $index">@{{$index + 1}} PM</option>
													</select>
												</div>
											</div>
											<div class="calendar-box">
												<span class="input-group">
													<input type="text" class="form-control" ng-disabled="! activeDate" ng-model="date" uib-datepicker-popup="dd-MMMM-yyyy" is-open="popup.popup_date" datepicker-options="dateOptions" close-text="Close" />
													<span class="input-group-btn">
														<button type="button" class="btn btn-default" ng-click="openDate('popup_date')"><i class="glyphicon glyphicon-calendar"></i></button>
													</span>
												</span>
											</div>
											<div class="switch-box">
												<label class="ui-switch ui-switch-success ui-switch-sm url-switch">
													<input type="checkbox" ng-model="activeDate" ng-change="toggleDate()" />
													<i></i>
													<span ng-switch="activeDate" class="team-leader">{{ __('Date ') }} 
														<span class="team-leader" ng-switch-when="false">{{ __('off') }}</span>
														<span class="team-leader" ng-switch-when="true">{{ __('on') }}</span>
													</span>
												</label>
											</div>
										</div>
									</div>
								</div>

								<div class="form-group" ng-show="employee.company_status == 'verified' && ! companyChanged">
									<button class="btn btn-primary" ng-click="send()">{{ __('Send') }}</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/ng-template" id="EmployeesCreate.html">
	<form name="form_employee" method="post" novalidate="novalidate">
		<div class="modal-header">
			<h4 class="modal-title" ng-show=" ! employee.id">{{ __("Create New Employee") }}</h4>
			<h4 class="modal-title" ng-show="employee.id">{{ __("Edit Employee") }}</h4>
		</div>

		<div class="modal-body">
			<div class="row">
				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label>{{ __("First Name") }}</label>
						<input type="text" name="firstname" class="form-control" ng-model="employee.firstname" required="required" />
					</div>

					<div class="form-group">
						<label>{{ __("Last Name") }}</label>
						<input type="text" class="form-control" ng-model="employee.lastname" />
					</div>
					<div class="form-group">
						<label>{{ __("Avatar") }}</label>
						<div class="form-group">
							<span class="upload-button-box">
								<button type="button" class="btn btn-sm btn-default">
									<i class="fa fa-picture-o"></i> {{ __("Choose File") }}
								</button>
								<input onchange="angular.element(this).scope().uploadFile(event.target.files[0])" accept="image/jpeg,image/png,image/gif,image/bmp,video/avi,video/mp4,video/quicktime,video/x-ms-wmv" type="file" />
							</span>

							<span class="upload-tooltip" uib-tooltip="{{ __('Image size limit is 500 KB; supported image file types include .JPG, .PNG, .GIF (non-animated), .BMP Video size limit is 3 MB; supported video file types include .AVI, .MP4, .WMV, and .MOV') }}">
								<i class="fa fa-question-circle"></i> {{ __('Upload details') }}
							</span>
						</div>
						<div class="form-group">
							<img ng-show="file.url" src="@{{ file.url }}" class="preview-mms" />
							<i ng-show="file.url" ng-click="removeMMS()" class="fa fa-times mms-remove" aria-hidden="true"></i>
							<i ng-show="request" class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
						</div>
					</div>
				</div>

				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label>{{ __("Email") }}</label>
						<input type="email" class="form-control" name="email" ng-model="employee.email" required="required" />
					</div>

					<div class="form-group">
						<label>{{ __("Phone") }}</label>
						<input type="text" class="form-control" name="view_phone" ng-model="employee.view_phone" />
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