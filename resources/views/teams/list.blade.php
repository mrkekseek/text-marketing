<div class="page page-table" data-ng-controller="TeamsCtrl" data-ng-init="initAdmin()">
	<h2>
		<div class="pull-right">
    		<button type="button" class="btn btn-primary" ng-click="create()"><i class="fa fa-plus-circle"></i><span class="hidden-xs"> {{ __('Create New Team') }}</span></button>
    	</div>

		{{ __('Teams') }}
	</h2>

	<div class="content-loader" ng-show=" ! request_finish">
		<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
	</div>

	<div ng-show="request_finish">
		<div uib-alert class="alert-info" ng-show=" ! list.length">
			{{ __("You haven't any team yet.") }}
			<a href="javascript:;" ng-click="create()">{{ __("Create New Team") }}</a> {{ __("now") }}
		</div>

		<section class="panel panel-default table-dynamic table-responsive" ng-show="list.length">
			<table class="table table-bordered table-striped table-middle">
				<thead>
					<tr>
						<th>
							<div class="th">
								{{ __('Name') }}
							</div>
						</th>
						
						<th class="text-center">
							{{ __('Company name in Text status') }}
						</th>

						<th class="text-center">
							{{ __('Group') }}
						</th>

						<th class="text-center">
							{{ __('Text Marketing') }}
						</th>
						
						<th class="td-count">
							{{ __('Number of Users') }}
						</th>

	                    <th class="td-button">
						</th>

						<th class="td-button">
						</th>
	                </tr>
				</thead>

				<tbody>
					<tr ng-repeat="team in list">
						<td>
							@{{ team.name}}
						</td>

						<td class="text-center">
							@{{ team.status }}
						</td>
						
						<td class="text-center">
							@{{ team.groups.name }}
						</td>
						
						<td class="text-center">
							<span ng-if="! team.teams_marketing_name">{{ _('Disabled') }}</span>
							@{{ team.teams_marketing_name }}
						</td>
						
						<td class="td-count">
							@{{ team.users_count }}
						</td>

						<td class="td-button">
							<a href="javascript:;" class="a-icon text-primary" ng-click="create(team.id)">
								<i class="fa fa-cog"></i>
							</a>
						</td>

						<td class="td-button">
							<a href="javascript:;" class="a-icon text-danger" ng-click="remove(team.id)">
								<i class="fa fa-trash"></i>
							</a>
						</td>
					</tr>
				</tbody>
			</table>
		</section>
	</div>
</div>

<script type="text/ng-template" id="TeamsCreate.html">
	<form name="form" method="post" novalidate="novalidate">
	    <div class="modal-header">
	        <h4 class="modal-title" ng-show=" ! team.teams_id">{{ __("Create New Team") }}</h4>
	        <h4 class="modal-title" ng-show="team.teams_id">{{ __("Edit Team") }}</h4>
	    </div>

	    <div class="modal-body">
	    	<div class="form-group">
				<label>{{ __("Group") }}</label>
	    		<select class="form-control" ng-model="team.groups_id">
	    			<option value="0">{{ __('Select a Group...') }}</option>
	    			<option ng-repeat="group in groups" value="@{{ group.id }}">@{{ group.name }}</option>
	    		</select>
	    	</div>

	    	<div class="form-group">
		    	<label>{{ __("Name") }}</label>
				<input type="text" class="form-control" name="name" ng-model="team.name" required="required" />
	    	</div>

	    	<div class="form-group">
	    		<label>{{ __("Text Marketing Level") }}</label>
	    		<select class="form-control" ng-model="team.levels_id">
	    			<option value="0">{{ __('Disabled') }}</option>
	    			<option ng-repeat="level in levels" value="@{{ level.id }}">@{{ level.name }} (@{{ level.texts > 1000 ? "Unlimited" : level.texts }} {{ __("text/month") }})</option>
	    		</select>
    		</div>
		</div>
		
	    <div class="modal-footer">
			<button type="submit" class="btn btn-primary" ng-click="save()">{{ __('Save') }}</button>
			<button type="button" class="btn btn-default" ng-click="cancel()">{{ __('Cancel') }}</button>
	    </div>
	</form>
</script>