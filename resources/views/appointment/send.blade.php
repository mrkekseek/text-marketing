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

							<div class="divider divider-dashed"></div>

							<div uib-alert class="alert-info" ng-show=" ! partner.id">
								{{ __('Choose from list or create new Partner to send Reviews') }}
							</div>

							<div ng-show="partner.id">
								<div class="row">
									<div class="col-sm-6">
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
									</div>
								</div>
								<div class="divider divider-dashed"></div>
							</div>

							<div ng-show="partner.id">
								<div class="form-group">
									<div class="row">
										<div class="col-sm-6">
											<div uib-alert class="alert-info" ng-show="partner.company_status != 'verified' || companyChanged">
												{{ __('To send texts you should save Company Name and wait untill it will be verified. It may takes 15 minutes') }}
											</div>

											<div ng-show="partner.company_status == 'verified' && ! companyChanged">
												<div>
													<textarea rows="3" cols="10" class="form-control appointment-textarea" readonly="readonly">@{{ createText() }}</textarea>
												</div>
											</div>
											
										</div>
										<div class="col-sm-6" ng-show="partner.company_status == 'verified' && ! companyChanged">
											<div uib-timepicker ng-model="time" show-meridian="true"></div>
										</div>
									</div>
								</div>

								<div class="form-group" ng-show="partner.company_status == 'verified' && ! companyChanged">
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