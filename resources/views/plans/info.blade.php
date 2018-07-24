<div class="page page-table" data-ng-controller="PlansCtrl" data-ng-init="initPlanPage()">
	<h2>
		{{ __('Billing Details') }}
	</h2>

    <div class="row">
        <div class="col-sm-12 col-md-6 plans">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="loader_wrapper">
                        <div class="content-loader" ng-show="! request_finish">
                            <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                        </div>
                    </div>

                    <div class="row">
						<div class="col-sm-12">
                            <form id="payment-form" ng-show="showCardDetails && request_finish">
                                <div class="form-row">
                                    <h5>
                                        Credit or Debit Card
                                    </h5>

                                    <div class="card-element">
                                    </div>

                                    <div id="card-errors" role="alert"></div>
                                </div>

                                <button>Submit</button>
                                <a href="javascript:void(0);" class="btn btn-default" ng-show="stripe.stripe_id" ng-click="showCardDetails = ! showCardDetails">Cancel</a>

                                <div class="subscription_info_block">
                                    <h5>
                                        Free 30 day trial | Month to month billing | No annual contracts | Cancel anytime, no fee.
                                    </h5>

                                    <a href="https://calendly.com/contractortexter" class="btn btn-default" target="_blank">Schedule a Call with Us</a>
                                </div>
                            </form>

                            <div class="plan_details">
                                <div class="card_details" ng-show="stripe.stripe_id && ! showCardDetails && request_finish">
                                    <h5>
                                        Credit or Debit Card
                                    </h5>

                                    <div class="form-group pull-left">
                                        <i class="fa fa-cc-visa" ng-if="stripe.card_brand == 'Visa'"></i>
                                        <i class="fa fa-cc-mastercard" ng-if="stripe.card_brand == 'MasterCard'"></i>
                                        <i class="fa fa-cc-amex" ng-if="stripe.card_brand == 'American Express'"></i>
                                        <i class="fa fa-cc-discover" ng-if="stripe.card_brand == 'Discover'"></i>
                                        <i class="fa fa-cc-jcb" ng-if="stripe.card_brand == 'JCB'"></i>
                                        <i class="fa fa-cc-diners-club" ng-if="stripe.card_brand == 'Diners Club'"></i>
                                        <i class="fa fa-credit-card" ng-if="stripe.card_brand == 'UnionPay'"></i>
                                        <span>****@{{ stripe.card_last_four}}</span>
                                    </div>

                                    <button class="btn btn-primary btn-danger pull-right" ng-click="showCardDetails = ! showCardDetails">Change</button>
                                </div>

                                <div class="cancel_subscription" ng-show="request_finish">
                                    <h5>
                                        Your Plan
                                    </h5>

                                    <table class="table table-bordered table-striped table-middle table-phones">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="th">
                                                        {{ __('Current Plan') }}
                                                    </div>
                                                </th>

                                                <th>
                                                    <div class="th">
                                                        {{ __('Subscription Status') }}
                                                    </div>
                                                </th>

                                                <th ng-if="stripe.plan_name != 'Free'">
                                                    <div class="th">
                                                        {{ __('Cancel Subscription') }}
                                                    </div>
                                                </th>

                                                <th ng-if="stripe.plan_name == 'Free'">
                                                    <div class="th">
                                                        {{ __('Reactivate to Paid Plan') }}
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td>
                                                    @{{ plan_name }}
                                                </td>

                                                <td>
                                                    @{{ stripe.status ? stripe.status : 'Not Active' }}
                                                </td>

                                                <td class="text-center">
                                                    <button class="btn btn-primary btn-danger" ng-if="stripe.plan_name != 'Free'" ng-class="{disabled: ! stripe.stripe_id}" ng-click="cancelSubscription()">Cancel</button>
                                                    <button class="btn btn-primary btn-danger" ng-if="stripe.plan_name == 'Free'" ng-click="reactivate()">Reactivate</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/ng-template" id="ModalCancelPlansConfirm.html">
	<form name="form" method="post" novalidate="novalidate">
		<div class="modal-header">
			<h4 class="modal-title">{{ __("Cancel subscription") }}</h4>
		</div>

		<div class="modal-body">
			<div class="row">
                <div class="content-loader" ng-show="! request_finish">
                    <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                </div>

				<div ng-show=" ! showCancelReason && request_finish && plan.plan_name != 'Pre Appointment Text'">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group text-center">
                            <h4>Downgrade to Free plan</h4>
                            <p>The free plan sends texts to just 5 leads a month.</p>
                            <button class="btn btn-primary" ng-click="downgrade()">{{ __('Downgrade to Free') }}</button>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group text-center">
                            <h4>Total cancellation</h4>
                            <p>Are you sure you want to cancel this subscription?</p>
                            <button class="btn btn-primary" ng-click="showCancelReason = ! showCancelReason">{{ __('Cancel Subscription') }}</button>
                        </div>
                    </div>
                </div>

                <div ng-show="(showCancelReason && request_finish) || (plan.plan_name == 'Pre Appointment Text' && request_finish)">
                    <div class="col-sm-12 text-center">
                        <h4>Why do you want to unsubscribe?</h4>
                        <div class="form-group">
                            <textarea name="reason" class="form-control" ng-model="plan.reason" maxlength="191"></textarea>
                        </div>
                        <button class="btn btn-primary" ng-class="{disabled: ! plan.reason}" ng-click="unsubscribe()">{{ __('Submit and Unsubscribe') }}</button>
                    </div>
                </div>
			</div>
		</div>

		<div class="modal-footer">
			<button type="button" class="btn btn-default" ng-click="cancel()">{{ __('Close') }}</button>
		</div>
	</form>
</script>