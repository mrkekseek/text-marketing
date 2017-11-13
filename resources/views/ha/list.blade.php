<div class="page page-table">
	<h2>
		<div class="pull-right">
    		<button type="button" class="btn btn-primary" ng-click="create()"><i class="fa fa-plus-circle"></i><span class="hidden-xs"> {{ __('Create New Link') }}</span></button>
    	</div>

		{{ __('HomeAdvisor Links') }}
	</h2>

	<div class="content-loader" ng-show=" ! request_finish">
		<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
	</div>

	<div ng-show="request_finish">
		<div uib-alert class="alert-info" ng-show=" ! list.length">
			{{ __("You haven't any links yet.") }}
			<a href="javascript:;" ng-click="create()">{{ __("Create New Link") }}</a> {{ __("now") }}
		</div>

		<section class="panel panel-default table-dynamic table-responsive " ng-show="list.length">
			<table class="table table-bordered table-striped table-middle">
				<thead>
					<tr>
						<th>
							<div class="th">
								{{ __('Required Team') }}
							</div>
						</th>

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
								{{ __('Phone') }}
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Success String') }}
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Sign Up Link') }}
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
					<tr ng-repeat="user in list">
						<td>
							@{{ "test" }}
						</td>

						<td>
							@{{ "test" }}
						</td>
						
						<td>
							@{{ "test" }}
						</td>
						
						<td>
							@{{ "test" }}
						</td>

						<td>
							@{{ "test" }}
						</td>

						<td>
							@{{ "test" }}
						</td>

						<td class="td-button text-center">
							<a href="javascript:;" class="a-icon text-success" ng-click="create(link.links_id)">
								<i class="fa fa-pencil-square-o"></i>
							</a>
						</td>

						<td class="td-button text-center">
							<a href="javascript:;" class="a-icon text-danger" ng-click="remove(user.id)">
								<i class="fa fa-trash"></i>
							</a>
						</td>
						<td class="td-button text-center">
							<button ng-show="! link.user_signup.links_code" class="btn btn-default" uib-tooltip="Not signup yet">{{ __("Send") }}</button>
							<button ng-show="link.user_signup.links_code" class="btn btn-primary" ng-click="send_modal(link.user_signup.links_code)">{{ __("Send") }}</button>
						</td>
					</tr>
				</tbody>
			</table>
		</section>
	</div>
</div>