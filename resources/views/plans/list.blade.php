<div class="page page-table" data-ng-controller="PlansCtrl" data-ng-init="init()">
	<h2>
		<div class="pull-right">
			<button type="button" class="btn btn-primary" ng-click="create()"><i class="fa fa-plus-circle"></i><span class="hidden-xs"> {{ __('Create New Plan') }}</span></button>
		</div>

		{{ __('Payment Plans') }}
	</h2>

	<div class="content-loader" ng-show=" ! request_finish">
		<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
	</div>

	<div ng-show="request_finish">
		<div uib-alert class="alert-info" ng-show=" ! list.length">
			{{ __("You haven't any plan yet.") }}
			<a href="javascript:;" ng-click="create()">{{ __("Create New Plan") }}</a> {{ __("now") }}
		</div>

		<section class="panel panel-default table-dynamic table-responsive " ng-show="list.length">
			<table class="table table-bordered table-striped table-middle">
				<thead>
					<tr>
						<th>
							<div class="th">
								{{ __('Plans Name') }}
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Plans Price') }}
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Plans Interval') }}
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Number of Users') }}
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Trial Period Days') }}
							</div>
						</th>

						<th class="th-button">
						</th>

					</tr>
				</thead>

				<tbody>
					<tr ng-repeat="plan in list">
						<td>
							@{{ plan.plans_name }}
						</td>

						<td>
							@{{ plan.plans_price }}
						</td>
						
						<td>
							@{{ plan.plans_interval }}
						</td>
						
						<td>
							@{{ plan.num }}
						</td>

						<td>
							@{{ plan.trial }}
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

<script type="text/ng-template" id="PlanCreate.html">
	<form name="form" method="post" novalidate="novalidate">
		<div class="modal-header">
			<h4 class="modal-title">{{ __("Create Plan") }}</h4>
		</div>

		<div class="modal-body">
			<div class="form-group">
				<label>{{ __("Name") }}</label>
				<input type="text" name="firstname" class="form-control" ng-model="user.firstname" required="required" />
			</div>
			<div class="form-group ">
				<label>{{ __("Amount") }}</label>
				<div class="input-group">
					<input type="text" class="form-control" ng-model="user.lastname" required="required" />
					<span class="input-group-addon">$</span>
				</div>
			</div>
			<div class="form-group">
				<label>{{ __("Interval") }}</label>
				<select class="form-control" ng-model="plan.interval">
					<option value="day">{{ __("Day") }}</option>
					<option value="week">{{ __("Week") }}</option>
					<option value="month">{{ __("Month") }}</option>
					<option value="year">{{ __("Year") }}</option>
				</select>
			</div>
			<div class="form-group">
				<label>{{ __("Counts per week") }}</label>
				<input type="text" class="form-control" ng-model="user.phone" />
			</div>
			<div class="form-group">
				<label>{{ __("Trial Period (days)") }}</label>
				<input type="text" class="form-control" ng-model="user.phone" />
			</div>
		</div>

		<div class="modal-footer">
			<button type="submit" class="btn btn-primary" ng-click="save()">{{ __('Save') }}</button>
			<button type="button" class="btn btn-default" ng-click="cancel()">{{ __('Cancel') }}</button>
		</div>
	</form>
</script>