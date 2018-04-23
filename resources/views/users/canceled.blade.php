<div class="page page-table" data-ng-controller="UsersCtrl" data-ng-init="initCanceled()">
	<h2>
		<div class="pull-right">
			<button type="button" class="btn btn-primary" ng-click="create()"><i class="fa fa-plus-circle"></i><span class="hidden-xs"> {{ __('Create New Teammate') }}</span></button>
		</div>

		<div class="search-bar pull-right">
			<input type="text" class="form-control" ng-model="quickSearch" placeholder="{{ __('Quick Search...') }}" />
		</div>

		{{ __('Canceled Users') }}
	</h2>

	<div class="content-loader" ng-show=" ! request_finish">
		<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
	</div>

	<div ng-show="request_finish">
		<div uib-alert class="alert-info" ng-show=" ! list.length">
			{{ __("There are no users that have canceled their subscription.") }}
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

						<th>
							<div class="th">
								{{ __('Team') }}
							</div>
						</th>
						
						<th class="ha_number">
							<div class="th">
								{{ __('Account #') }}
							</div>
						</th>
						
						<th>
							<div class="th">
								{{ __('Cancellation Reason') }}
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Reactivate Plan') }}
							</div>
						</th>
						
						<th>
							<div class="th">
								{{ __('Reactivate to Free Plan') }}
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
							@{{ user.teams.name }}
						</td>
						
						<td>
							@{{ user.rep }}
						</td>
						
						<td>
							<div class="cancel_reason" uib-tooltip="Click to view full text" tooltip-append-to-body="true" ng-click="viewFullCancelReason(user.cancellation_reason)">
								<span >
									@{{ user.cancellation_reason }}
								</span>
							</div>
						</td>

						<td class="td-button text-center">
							<button class="btn btn-primary btn-danger" ng-click="confirmSubscription(user, 'reactivate')">Reactivate</button>
						</td>

						<td class="td-button text-center">
							<button class="btn btn-primary btn-danger" ng-click="confirmSubscription(user, 'upgrade')">Make Free</button>
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
						<h4 ng-if="action == 'reactivate'">
							Do you really want to reactivate @{{ user.firstname + ' ' + user.lastname }}'s plan?
						</h4>
						
						<h4 ng-if="action == 'upgrade'">
							Do you really want to upgrade @{{ user.firstname + ' ' + user.lastname }}'s plan to Free?
						</h4>
					</div>
				</div>
			</div>
		</div>

		<div class="modal-footer">
			<button type="button" class="btn btn-primary btn-danger" ng-click="aprove()">{{ __('Yes') }}</button>
			<button type="button" class="btn btn-default" ng-click="cancel()">{{ __('No') }}</button>
		</div>
	</form>
</script>

<script type="text/ng-template" id="ModalViewCancelReason.html">
	<form name="form" method="post" novalidate="novalidate">
		<div class="modal-header">
			<h4 class="modal-title">{{ __("Cancellation Reason") }}</h4>
		</div>

		<div class="modal-body">
			<div class="row">
				<div class="col-sm-12">
					<div class="modal_cancel_reason">
						"@{{ reason }}"
					</div>
				</div>
			</div>
		</div>

		<div class="modal-footer">
			<button type="button" class="btn btn-default" ng-click="cancel()">{{ __('Close') }}</button>
		</div>
	</form>
</script>