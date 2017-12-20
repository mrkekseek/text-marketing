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
								{{ __('Name') }}
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Price') }}
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Trial Period') }}
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Texts for Reviews') }}
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Texts for TM') }}
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Emails') }}
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Users with Plan') }}
							</div>
						</th>

						<th class="th-button">
						</th>

						<th class="th-button">
						</th>
					</tr>
				</thead>

				<tbody>
					<tr ng-repeat="plan in list">
						<td>
							@{{ plan.name }}
						</td>

						<td>
							$@{{ plan.amount }} / @{{ plan.interval }}
						</td>

						<td>
							@{{ plan.trial }} {{ __('day(s)') }}
						</td>

						<td>
							@{{ plan.reviews }}
						</td>

						<td>
							@{{ plan.tms }}
						</td>

						<td>
							@{{ plan.emails }}
						</td>

						<td>
							@{{ plan.users_count }}
						</td>

						<td class="td-button">
							<a href="javascript:;" class="a-icon text-success" ng-click="create(plan.id)">
								<i class="fa fa-pencil-square-o"></i>
							</a>
						</td>

						<td class="td-button text-center">
							<a href="javascript:;" class="a-icon text-danger" ng-click="remove(plan.id)" ng-if="plan.users_count == 0">
								<i class="fa fa-trash"></i>
							</a>

							<i class="fa fa-question-circle-o help-icon" uib-tooltip-template="'removeTooltip.html'" tooltip-placement="left-top" aria-hidden="true" ng-if="plan.users_count > 0"></i>
						</td>
					</tr>
				</tbody>
			</table>
		</section>
	</div>
</div>

<script type="text/ng-template" id="ModalPlansCreate.html">
	<form name="form" method="post" novalidate="novalidate">
		<div class="modal-header">
			<h4 class="modal-title">{{ __("Create Plan") }}</h4>
		</div>

		<div class="modal-body">
			<div class="row">
				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label>{{ __("Name") }}</label>
						<input type="text" name="name" class="form-control" ng-model="plan.name" required="required" />
					</div>

					<div class="form-group">
						<label>{{ __("Amount") }}</label>
						<div class="row">
							<div class="col-xs-6">
								<div class="input-group">
									<input type="number" name="amount" class="form-control" ng-model="plan.amount" ng-readonly="plan.id" required="required" />
									<span class="input-group-addon">$</span>
								</div>
							</div>

							<div class="col-xs-6">
								<select class="form-control" ng-disabled="plan.id" ng-model="plan.interval">
									<option value="day">{{ __("per Day") }}</option>
									<option value="week">{{ __("per Week") }}</option>
									<option value="month">{{ __("per Month") }}</option>
									<option value="year">{{ __("per Year") }}</option>
								</select>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label>{{ __("Trial Period (days)") }}</label>
						<input type="text" class="form-control" ng-model="plan.trial" />
					</div>
				</div>

				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label>{{ __("Texts for Reviews (per week)") }}</label>
						<input type="text" class="form-control" ng-model="plan.reviews" placeholder="Use 99999 value for unlimited" />
					</div>

					<div class="form-group">
						<label>{{ __("Texts for TM (per week)") }}</label>
						<input type="text" class="form-control" ng-model="plan.tms" placeholder="Use 99999 value for unlimited" />
					</div>

					<div class="form-group">
						<label>{{ __("Emails (per week)") }}</label>
						<input type="text" class="form-control" ng-model="plan.emails" placeholder="Use 99999 value for unlimited" />
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

<script type="text/ng-template" id="removeTooltip.html">
	<span>{{ __('You can\'t remove a plan with active users on it. First change a plan for those users or remove them') }}</span>
</script>