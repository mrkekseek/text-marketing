<div class="page page-table" data-ng-controller="UsersCtrl" data-ng-init="initAdmin()">
	<h2>
		<div class="pull-right">
			<button type="button" class="btn btn-primary" ng-click="create()"><i class="fa fa-plus-circle"></i><span class="hidden-xs"> {{ __('Create New Teammate') }}</span></button>
		</div>

		{{ __('Users') }}
	</h2>

	<div class="content-loader" ng-show=" ! request_finish">
		<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
	</div>

	<div ng-show="request_finish">
		<div uib-alert class="alert-info" ng-show=" ! list.length">
			{{ __("You haven't any teammate yet.") }}
			<a href="javascript:;" ng-click="create()">{{ __("Create New Teammate") }}</a> {{ __("now") }}
		</div>

		<section class="panel panel-default table-dynamic table-responsive " ng-show="list.length">
			<table class="table table-bordered table-striped table-middle">
				<thead>
					<tr>
						<th>
							<div class="th">
								{{ __('First Name') }}
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Last Name') }}
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Email') }}
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Phone') }}
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Team') }}
							</div>
						</th>
						
						<th class="text-center">
							{{ __('Team Leader') }}
						</th>

						<th class="th-button">
						</th>

						<th class="th-button">
						</th>

						<th class="th-button">
						</th>

						<th class="th-button">
						</th>
					</tr>
				</thead>

				<tbody>
					<tr ng-repeat="user in list">
						<td>
							@{{ user.firstname }}
						</td>

						<td>
							@{{ user.lastname }}
						</td>
						
						<td>
							@{{ user.email }}
						</td>
						
						<td>
							@{{ user.phone }}
						</td>

						<td>
							@{{ user.teams.name }}
						</td>
						
						<td class="text-center">
							<label class="ui-switch ui-switch-success ui-switch-sm">
								<input type="checkbox" ng-model="user.teams_leader" ng-checked="user.teams_leader == 1" ng-click="teams_leader(user.id, user.teams_leader)" />
								<i></i>
							</label>
						</td>
						
						<td class="td-button text-center">
							<a href="javascript:;" class="a-icon text-warning" ng-click="sign_in(user.id)">
								<i class="fa fa-lock" aria-hidden="true"></i>
							</a>
						</td>

						<td class="td-button text-center">
							<a href="javascript:;" class="a-icon text-primary" ng-click="settings(user.id)">
								<i class="fa fa-cog"></i>
							</a>
						</td>

						<td class="td-button text-center">
							<a href="javascript:;" class="a-icon text-success" ng-click="create(user.id)">
								<i class="fa fa-pencil-square-o"></i>
							</a>
						</td>

						<td class="td-button text-center">
							<a href="javascript:;" class="a-icon text-danger" ng-click="remove(user.id)">
								<i class="fa fa-trash"></i>
							</a>
						</td>
					</tr>
				</tbody>
			</table>
		</section>
	</div>
</div>

<script type="text/ng-template" id="UsersCreate.html">
	<form name="form" method="post" novalidate="novalidate">
		<div class="modal-header">
			<h4 class="modal-title" ng-show=" ! user.id">{{ __("Create New Teammate") }}</h4>
			<h4 class="modal-title" ng-show="user.id">{{ __("Edit Teammate") }}</h4>
		</div>

		<div class="modal-body">
			<div class="row">
				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label>{{ __("Team") }}</label>
						<select class="form-control" name="teams_id" ng-model="user.teams_id" required="required">
							<option value="0">{{ __('Select a Team...') }}</option>
							<option ng-repeat="team in teams" value="@{{ team.id }}">@{{ team.name }}</option>
						</select>
					</div>
					<div class="form-group">
						<label>{{ __("First Name") }}</label>
						<input type="text" name="firstname" class="form-control" ng-model="user.firstname" required="required" />
					</div>
					<div class="form-group">
						<label>{{ __("Last Name") }}</label>
						<input type="text" class="form-control" ng-model="user.lastname" />
					</div>
					<div class="form-group">
						<label class="ui-switch ui-switch-success ui-switch-sm pull-right">
							<input type="checkbox" ng-model="user.teams_leader" ng-true-value="'1'" ng-false-value="'0'" />
							<i></i>
						</label>
						<strong class="team-leader">{{ __(' Team Leader') }}</strong>
					</div>
					<div class="form-group">
						<label class="ui-switch ui-switch-success ui-switch-sm pull-right">
							<input type="checkbox" ng-model="user.active" ng-true-value="'1'" ng-false-value="'0'" />
							<i></i>
						</label>
						<strong class="team-leader">{{ __('Activate teammate') }}</strong>
					</div>
				</div>
				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label>{{ __("Email") }}</label>
						<input type="email" class="form-control" name="email" ng-model="user.email" required="required" />
					</div>
					<div class="form-group">
						<label>{{ __("Password") }}</label>
						<input type="password" class="form-control" ng-model="user.password" />
					</div>
					<div class="form-group">
						<label>{{ __("Phone") }}</label>
						<input type="text" class="form-control" ng-model="user.phone" />
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

<script type="text/ng-template" id="UsersSettings.html">
	<form name="form" method="post" novalidate="novalidate">
		<div class="modal-header">
			<h4 class="modal-title">{{ __("Settings") }}</h4>
		</div>

		<div class="modal-body">
			<div class="row">
				<div class="col-sm-6 col-xs-12 form-group">
					<label>{{ __("Max Texts per week") }}</label>
					<input type="text" class="form-control" ng-model="user.limit" />
				</div>

				<div class="col-sm-6 col-xs-12 form-group">
					<p><strong class="team-leader">{{ __('Show questions at the Responses') }}</strong></p>
					<label class="ui-switch ui-switch-success ui-switch-sm">
						<input type="checkbox" ng-model="user.responses" ng-true-value="'1'" ng-false-value="'0'" />
						<i></i>
					</label>
				</div>
			</div>
		</div>

		<div class="modal-footer">
			<button type="submit" class="btn btn-primary" ng-click="save()">{{ __('Save') }}</button>
			<button type="button" class="btn btn-default" ng-click="cancel()">{{ __('Cancel') }}</button>
		</div>
	</form>
</script>