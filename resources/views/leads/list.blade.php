<div class="page page-table" data-ng-controller="LeadsCtrl" data-ng-init="init()">
	<h2>
		{{ __('Leads') }}
	</h2>

	<div class="content-loader" ng-show=" ! request_finish">
		<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
	</div>

	<div ng-show="request_finish">
		<div class="form-group row">
            <div class="col-sm-3">
				<input type="text" placeholder="User..." typeahead-on-select="get()" ng-model="filter.user" typeahead-min-length="0" uib-typeahead="user as (user.firstname + ' ' + user.lastname) for user in users | filter:$viewValue | limitTo:10 | orderBy:'firstname'" class="form-control" />
			</div>
            
			<div class="col-sm-3">
				<div class="input-group">
					<input type="text" class="form-control" ng-change="get()" uib-datepicker-popup="{{ 'dd-MMMM-yyyy' }}" ng-focus="openDate()" ng-model="filter.date" is-open="date.opened" datepicker-options="dateOptions" ng-required="true" datepicker-append-to-body="true" close-text="Close" />
					<span class="input-group-btn">
						<button type="button" class="btn btn-default" ng-click="openDate()"><i class="glyphicon glyphicon-calendar"></i></button>
					</span>
				</div>
			</div>

			<div class="col-sm-3" ng-show="list.length">
				<input type="text" class="form-control" ng-model="quickSearch" placeholder="{{ __('Quick Search...') }}" />
			</div>
		</div>

		<div uib-alert class="alert-info" ng-show=" ! list.length">
			{{ __("There is no any leads.") }}
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
								{{ __('Cell #') }}
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Belongs To User') }}
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Exists') }} <i class="fa fa-question-circle" uib-tooltip="Lead already was in the database" tooltip-placement="bottom" tooltip-append-to-body="true"></i>
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Saved') }} <i class="fa fa-question-circle" uib-tooltip="Lead was successfully saved  in to the database" tooltip-placement="bottom" tooltip-append-to-body="true"></i>
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Date of attachment') }}
							</div>
						</th>
						
						<!-- <th class="th-button">
						</th>

						<th class="th-button">
						</th>

						<th class="th-button">
						</th> -->
					</tr>
				</thead>

				<tbody>
					<tr ng-repeat="lead in list | filter : quickSearch">
						<td>
							@{{ lead.firstName }}
						</td>

						<td>
							@{{ lead.lastName }}
						</td>
						
						<td>
							@{{ lead.primary_phone }}
						</td>

						<td>
							@{{ lead.user.firstname + ' ' +  lead.user.lastname }}
						</td>
						
						<td class="text-center">
							<span class="check-span" ng-if="lead.exists == 1"><i class="fa fa-check"></i></span>
							<span class="times-span" ng-if="lead.exists == 0"><i class="fa fa-times"></i></span>
						</td>
						
						<td class="text-center">
							<span class="check-span" ng-if="lead.saved == 1"><i class="fa fa-check"></i></span>
							<span class="times-span" ng-if="lead.saved == 0"><i class="fa fa-times"></i></span>
						</td>
						
						<td>
							@{{ lead.created_at_string }}
						</td>
						
						<!-- <td class="td-button text-center">
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
						</td> -->
					</tr>
				</tbody>
			</table>
		</section>
	</div>
</div>