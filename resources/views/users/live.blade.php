<div class="page page-table" data-ng-controller="UsersCtrl" data-ng-init="initLive()">
	<h2>
		<div class="pull-right">
			<button type="button" class="btn btn-primary" ng-click="create()"><i class="fa fa-plus-circle"></i><span class="hidden-xs"> {{ __('Create New Teammate') }}</span></button>
		</div>

		<div class="search-bar pull-right">
			<input type="text" class="form-control" ng-model="quickSearch" placeholder="{{ __('Quick Search...') }}" />
		</div>

		{{ __('Live Users') }}
	</h2>

	<div class="content-loader" ng-show=" ! request_finish">
		<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
	</div>

	<div ng-show="request_finish">
		<div uib-alert class="alert-info" ng-show=" ! list.length">
			{{ __("You haven't any teammate yet.") }}
			<a href="javascript:;" ng-click="create()">{{ __("Create New Teammate") }}</a> {{ __("now") }}
		</div>

		<div uib-alert class="alert-warning" ng-show="(list | filter : quickSearch).length == 0">
			{{ __("Nothing found.") }}
		</div>

		<section class="panel panel-default table-dynamic table-responsive " ng-show="(list | filter : quickSearch).length">
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
								{{ __('Cell #') }}
							</div>
						</th>

						<th class="ha_number">
							<div class="th">
								{{ __('Account #') }}
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Current Plan') }}
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Downgrade Plan to Free') }}
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Cancel Subscription') }}
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Assign Plan') }}
							</div>
						</th>

						<th class="th-button">
							<div class="tiny-th">
								{{ __('Allow access') }}
							</div>
						</th>

						<th class="th-button">
							<div class="tiny-th">
								{{ __('Enable PAT') }}
							</div>
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
					<tr ng-repeat="user in list | filter : quickSearch">
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
							@{{ user.view_phone }}
						</td>

						<td>
							<div class="ha_account_column">
								@{{ user.rep }}
							</div>
						</td>

						<td>
							@{{ user.current_plan }}
						</td>

						<td class="td-button text-center">
							<button class="btn btn-primary btn-danger" ng-class="{'disabled': ! user.has_subscription || user.plans_id == 'pre-appointment-text-contractortexter'}" ng-click="confirmSubscription(user, 'downgrade')">Downgrade</button>
						</td>

						<td class="td-button text-center">
							<button class="btn btn-primary btn-danger" ng-click="confirmSubscription(user, 'cancel')">Cancel</button>
						</td>

						<td class="td-button text-center">
							<button class="btn btn-primary btn-danger" ng-click="confirmSubscription(user, 'assign')">Assign</button>
						</td>

						<td class="td-button text-center">
							<div class="access_switcher">
								<label class="ui-switch ui-switch-success ui-switch-sm">
									<input id="allow_access" type="checkbox" ng-click="allowAccess(user.id)" ng-model="user.allow_access" ng-true-value="1" ng-false-value="0" />
									<i></i>
								</label>
							</div>
						</td>

						<td class="td-button text-center">
							<div class="access_switcher" ng-show="user.plans_id != 'pre-appointment-text-contractortexter'">
								<label class="ui-switch ui-switch-success ui-switch-sm">
									<input id="enable_pat" type="checkbox" ng-click="enablePat(user.id)" ng-model="user.enable_pat" ng-true-value="1" ng-false-value="0" />
									<i></i>
								</label>
							</div>
						</td>

						<td class="td-button text-center">
							<a href="javascript:;" class="a-icon text-warning" ng-click="magic(user.id)">
								<i class="fa fa-lock" aria-hidden="true"></i>
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
						<label>{{ __("Payment Plan") }}</label>
						<select class="form-control" name="plans_id" ng-model="user.plans_id" required="required" ng-options="plan.plans_id as plan.name for plan in plans">
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
						<input type="text" class="form-control" ng-model="user.view_phone" />
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

<script type="text/ng-template" id="ModalConfirmPlan.html">
	<form name="form" method="post" novalidate="novalidate">
		<div class="modal-header">
			<h4 class="modal-title">{{ __("Confirmation") }}</h4>
		</div>

		<div class="modal-body">
			<div class="row">
				<div class="col-sm-12">
					<div class="content-loader" ng-show="! request_finish">
						<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
					</div>

					<div ng-show="request_finish">
						<h4 ng-if="action == 'downgrade'">
							Do you really want to downgrade @{{ user.firstname + ' ' + user.lastname }}'s plan to Free?
						</h4>

						<h4 ng-if="action == 'cancel'">
							Do you really want to cancel @{{ user.firstname + ' ' + user.lastname }}'s subscription?
						</h4>

						<div ng-if="action == 'assign'">
							<label>Please choose a plan for @{{ user.firstname + ' ' + user.lastname }}:</label>

							<div class="form-group">
								<select class="form-control assign_dropdown" ng-model="plans_id">
									<option value="@{{ plan.id }}" ng-repeat="plan in list">@{{ plan.name + ' ($' + plan.amount + ' /' + plan.interval + ')' }}</option>
								</select>
							</div>

							<div class="form-group">
								<button type="button" class="btn btn-primary btn-danger" ng-click="assign(plans_id)">{{ __('Assign') }}</button>
								<button type="button" class="btn btn-default" ng-click="cancel()">{{ __('Cancel') }}</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="modal-footer" ng-show="action != 'assign'">
			<button type="button" class="btn btn-primary btn-danger" ng-click="aprove()">{{ __('Yes') }}</button>
			<button type="button" class="btn btn-default" ng-click="cancel()">{{ __('No') }}</button>
		</div>
	</form>
</script>