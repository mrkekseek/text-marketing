<div class="page page-table" data-ng-controller="LinksCtrl" data-ng-init="init()">
	<h2>
		<div class="search-bar pull-right">
			<input type="text" class="form-control" ng-model="quickSearch" placeholder="{{ __('Quick Search...') }}" />
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
								{{ __('Link for HA') }}
							</div>
						</th>

						<th>
							<div class="th">
								{{ __('Success String') }}
							</div>
						</th>

						<th class="th-button">
						</th>
					</tr>
				</thead>

				<tbody>
					<tr ng-repeat="link in list | filter : quickSearch">
						<td>
							@{{ link.user.firstname }}
						</td>
						
						<td>
							@{{ link.user.lastname }}
						</td>
						
						<td>
							@{{ link.user.phone }}
						</td>

						<td>
							@{{ link.url }}
						</td>

						<td>
							@{{ link.success }}
						</td>

						<td class="td-button text-center">
							<button ng-show="! link.user.homeadvisors.send_request" class="btn btn-default" uib-tooltip="Not activate yet">{{ __("Send") }}</button>
							<button ng-show="link.user.homeadvisors.send_request" class="btn btn-primary" ng-click="sendModal(link.code)">{{ __("Send") }}</button>
						</td>
					</tr>
				</tbody>
			</table>
		</section>
	</div>
</div>

<script type="text/ng-template" id="ModalLinksCreate.html">
	<form name="form" method="post" novalidate="novalidate">
		<div class="modal-header">
			<h4 class="modal-title" ng-show=" ! link.id">{{ __("Create New Link") }}</h4>
			<h4 class="modal-title" ng-show="link.id">{{ __("Edit Link") }}</h4>
		</div>

		<div class="modal-body">
			<div class="row">
				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label>{{ __("Required Team") }}</label>
						<select class="form-control" name="teams_id" ng-model="link.teams_id" required="required" ng-options="team.id as team.name for team in teams">
						</select>
					</div>

					<div class="form-group">
						<label>{{ __("First Name") }}</label>
						<input type="text" name="firstname" class="form-control" ng-model="link.firstname" required="required" />
					</div>

					<div class="form-group">
						<label>{{ __("Last Name") }}</label>
						<input type="text" name="lastname" class="form-control" ng-model="link.lastname" required="required" />
					</div>

					<div class="form-group">
						<label>{{ __("Phone") }}</label>
						<input type="text" name="phone" class="form-control" ng-model="link.phone" required="required" />
					</div>	
				</div>

				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label>{{ __("Code") }}</label>
						<input type="text" class="form-control" ng-model="link.links_code" disabled="disabled" />
					</div>

					<div class="form-group">
						<label>{{ __("Link for HA") }}</label>
						<input type="text" class="form-control" ng-model="link.link_for_ha" disabled="disabled" />
					</div>

					<div class="form-group">
						<label>{{ __("Success String") }}</label>
						<input type="text" class="form-control" ng-model="link.success_string" disabled="disabled" />
					</div>	
				</div>
			</div>
		</div>

		<div class="modal-footer">
			<button type="submit" class="btn btn-primary" ng-click="save()">@{{ button }}</button>
			<button type="button" class="btn btn-default" ng-click="cancel()">{{ __('Close') }}</button>
		</div>
	</form>
</script>

<script type="text/ng-template" id="SendModal.html">
	<form name="form" method="post" novalidate="novalidate">
	    <div class="modal-header">
	        <h4 class="modal-title">{{ __("Create request from HomeAdvisor") }}</h4>
	    </div>

	    <div class="modal-body">
	    	<div class="form-group">
				<label>{{ __("First Name") }}</label>
				<input name="firstname" type="text" class="form-control" ng-model="fake.firstname" required="required" />
			</div>
			<div class="form-group">
				<label>{{ __("Last Name") }}</label>
				<input type="text" class="form-control" ng-model="fake.lastname" />
			</div>
			<div class="form-group">
				<label>{{ __("Phone Number") }}</label>
				<input name="phone" type="text" class="form-control" ng-model="fake.phone" required="required" />
			</div>
		</div>

	    <div class="modal-footer">
			<button type="submit" class="btn btn-primary" ng-click="send()">{{ __('Send') }}</button>
			<button type="button" class="btn btn-default" ng-click="cancel()">{{ __('Close') }}</button>
	    </div>
	</form>
</script> 