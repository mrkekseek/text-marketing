<div class="page page-table" data-ng-controller="ReportsCtrl" data-ng-init="init()">
	<h2>
		{{ __('Texts Reports') }}
	</h2>

	<div class="content-loader" ng-show=" ! request_finish">
		<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
	</div>

	<div ng-show="request_finish">
        <div class="form-group row">
            <div class="col-sm-3">
                <select class="form-control" ng-model="filter.type" ng-change="get()">
                    <option value="">{{ __('Any type') }}</option>
                    <option value="dialog">{{ __('Leads/Inbox') }}</option>
                    <option value="alert">{{ __('Alerts') }}</option>
                    <option value="review">{{ __('Reviews') }}</option>
                    <option value="message">{{ __('Messages (old)') }}</option>
                </select>
            </div>

			<div class="col-sm-3">
				<input type="text" placeholder="Phone Number..." ng-change="get()" typeahead-on-select="get()" ng-model="filter.phone" typeahead-min-length="0" uib-typeahead="phone for phone in phones | filter:$viewValue | limitTo:10" class="form-control" />
			</div>

			<div class="col-sm-3">
				<input type="text" placeholder="User..." ng-change="get()" typeahead-on-select="get()" ng-model="filter.user" typeahead-min-length="0" uib-typeahead="user as (user.firstname + ' ' + user.lastname) for user in usersList | filter:$viewValue | limitTo:10 | orderBy:'firstname'" class="form-control" />
			</div>

			<div class="col-sm-3">
				<div class="input-group">
					<input type="text" class="form-control" ng-change="get()" uib-datepicker-popup="{{ 'dd-MMMM-yyyy' }}" ng-focus="openDate()" ng-model="filter.date" is-open="date.opened" datepicker-options="dateOptions" ng-required="true" close-text="Close" />
					<span class="input-group-btn">
						<button type="button" class="btn btn-default" ng-click="openDate()"><i class="glyphicon glyphicon-calendar"></i></button>
					</span>
				</div>
			</div>
        </div>

		<div uib-alert class="alert-info" ng-show=" ! list.length">
			{{ __("Nothing was found using filter settings") }}
		</div>

		<section class="table-dynamic table-responsive " ng-show="list.length">
			<div ng-repeat="message in list">
				<table class="table table-bordered table-striped table-middle">
					<tbody>
						<tr>
							<td class="report-type">
								<span class="label label-primary" ng-show="message.type == 'dialog'">{{ __('Leads / Inbox') }}</span>
								<span class="label label-warning" ng-show="message.type == 'alert'">{{ __('Alert') }}</span>
								<span class="label label-info" ng-show="message.type == 'review'">{{ __('Review') }}</span>
								<span class="label label-default" ng-show="message.type == 'message'">{{ __('Message') }}</span>
							</td>

							<td class="report-date">
								<b>@{{ message.created_at }}</b>
							</td>

							<td class="report-company">
								@{{ message.company }}
							</td>

							<td class="report-icon">
								<span ng-show="message.message != ''" tooltip-placement="@{{ 'top' }}" uib-tooltip="@{{ message.message }}"><i class="fa fa-exclamation-circle text-danger"></i></span>
							</td>

							<td class="report-message">
								@{{ message.text }}
							</td>

							<td class="report-attach">
								<a href="@{{ message.attachment }}" ng-show="message.attachment != '0'" target="_blank">{{ __('Attachment') }}</a>
							</td>
						</tr>

						<tr ng-repeat="client in message.receivers">
							<td class="report-type">
								@{{ client.phone }}
							</td>

							<td class="report-date">
								<span class="label label-warning" ng-show="client.landline">{{ __('Landline') }}</span>
							</td>

							<td class="report-company">
								@{{ client.firstname + ' ' + client.lastname }}
							</td>

							<td class="report-icon">
								<span ng-show="client.finish == '0'" tooltip-placement="@{{ 'top' }}" uib-tooltip="{{ __('Still In Pending') }}"><i class="fa fa-clock-o text-warning"></i></span>
								<span ng-show="client.finish == '1' && client.success == '0'" tooltip-placement="@{{ 'top' }}" uib-tooltip="@{{ client.message }}"><i class="fa fa-exclamation-circle text-danger"></i></span>
								<span ng-show="client.finish == '1' && client.success == '1'" tooltip-placement="@{{ 'top' }}" uib-tooltip="@{{ client.message }}"><i class="fa fa-check-circle text-success"></i></span>
							</td>

							<td class="report-message">
								<span ng-show="client.parent_id != '0'">[Next Part]</span>
								@{{ client.text }}
							</td>

							<td class="report-attach">
								<a href="javascript:;" ng-click="trumpiaModal(client)" ng-show="client.request_id != ''">{{ __('Trumpia') }}</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</section>
	</div>
</div>

<script type="text/ng-template" id="TrumpiaModal.html">
	<form name="form" method="post" novalidate="novalidate">
		<div class="modal-header">
			<h4 class="modal-title">{{ __("Trumpia Response") }}</h4>
		</div>

		<div class="modal-body">
			<div class="form-group">
				<label>Request ID</label>
				<input type="text" readonly="readonly" class="form-control" value="@{{ trumpia.request_id }}" />
			</div>

			<div class="form-group">
				<label>Response</label>
				<textarea readonly="readonly" class="form-control" rows="5">@{{ trumpia.response }}</textarea>
			</div>

			<div class="form-group">
				<label>Push Notification</label>
				<textarea readonly="readonly" class="form-control" rows="7">@{{ trumpia.push }}</textarea>
			</div>
		</div>

		<div class="modal-footer">
			<button type="button" class="btn btn-default" ng-click="cancel()">{{ __('Close') }}</button>
		</div>
	</form>
</script>