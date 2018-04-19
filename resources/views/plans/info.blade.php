<div class="page page-table" data-ng-controller="PlansCtrl" data-ng-init="initPlanPage()">
	<h2>
		{{ __('Plan Details') }}
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
                                        Credit or debit card
                                    </h5>
                                    
                                    <div id="card-element">
                                    </div>
                                    
                                    <div id="card-errors" role="alert"></div>
                                </div>

                                <button>Submit</button>
                                <a href="javascript:void(0);" class="btn btn-default" ng-show="stripe.stripe_id" ng-click="showCardDetails = ! showCardDetails">Cancel</a>
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
                                                        {{ __('Current plan') }}
                                                    </div>
                                                </th>

                                                <th>
                                                    <div class="th">
                                                        {{ __('Subscription status') }}
                                                    </div>
                                                </th>
                                                
                                                <th>
                                                    <div class="th">
                                                        {{ __('Cancel subscription') }}
                                                    </div>
                                                </th>

                                                <th>
                                                    <div class="th">
                                                        {{ __('Pause subscription') }}
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td>
                                                    @{{ stripe.plan_name ? stripe.plan_name : plan_name }}
                                                </td>
                                                
                                                <td>
                                                    @{{ stripe.status ? stripe.status : 'Not Active' }}
                                                </td>
                                                
                                                <td class="text-center">
                                                    <button class="btn btn-primary btn-danger" ng-class="{disabled: ! stripe.stripe_id}" ng-click="cancelSubscription()">Cancel</button>
                                                </td>
                                                
                                                <td class="text-center">
                                                    <button class="btn btn-primary btn-danger" ng-class="{disabled: ! stripe.stripe_id}" ng-click="pauseSubscription()">Pause</button>
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

<script type="text/ng-template" id="ModalPlansConfirm.html">
	<form name="form" method="post" novalidate="novalidate">
		<div class="modal-header">
			<h4 class="modal-title">{{ __("Confirmation") }}</h4>
		</div>

		<div class="modal-body">
			<div class="row">
				<div class="col-xs-12">
					<div class="form-group">
						<p>Are you sure you want to cancel this subscription?</p>
					</div>
				</div>
			</div>
		</div>

		<div class="modal-footer">
			<button type="submit" class="btn btn-primary" ng-click="agree()">{{ __('Yes') }}</button>
			<button type="button" class="btn btn-default" ng-click="cancel()">{{ __('No') }}</button>
		</div>
	</form>
</script>