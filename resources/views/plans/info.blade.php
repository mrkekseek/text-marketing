<div class="page page-table" data-ng-controller="PlansCtrl" data-ng-init="init()">
	<h2>
		{{ __('Payment Plans') }}
	</h2>
    
    <div class="row">
        <div class="col-sm-12 col-md-6 new_users">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
						<div class="col-sm-12" id="new_users_texts">
                            <form name="form">
                                <div class="card-content">
                                    <label>
                                        <input name="cardholder_firstname" class="field" ng-class="{'is-empty': !user.users_firstname}" ng-model="user.users_firstname" placeholder="Enter your First Name" required="required" />
                                        <span>
                                            <span>{{ __('First Name') }}</span>
                                        </span>
                                    </label>
                                    <label>
                                        <input name="cardholder_lastname" class="field" ng-class="{'is-empty': !user.users_lastname}" ng-model="user.users_lastname" placeholder="Enter your Last Name" required="required" />
                                        <span>
                                            <span>{{ __('Last Name') }}</span>
                                        </span>
                                    </label>
                                    <label>
                                        <div id="card-element" class="field">
                                        </div>
                                        <span>
                                            <span>{{ __('Credit or debit card') }}</span>
                                        </span>
                                    </label>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" ng-model="terms" />{{ __('By clicking the button below you agree to our ') }}<a href="" target="_blank">{{ __('terms') }}</a>
                                        </label>
                                    </div>
                                    <button type="button" class="btn btn-primary" ng-click="save_card()" ng-class="{'disabled': !terms}">
                                        {{ __('Submit Payment') }} 
                                        <span ng-show="user_plan"></span>
                                    </button>
                                </div>
                            </form>
                            <!-- <form name="form">
                                <h4>Payment Details</h4>

                                
                                
                                <div class="form-group">
                                    <input type="text" class="form-control" name="card" ng-model="plans.card" placeholder="{{ __('Credit or debit card') }}" ng-required="user.company_status != 'verified'" />
                                </div>

                                <h4>Choose a Plan</h4>

                                <div class="form-group">
                                    <select class="form-control" ng-model="plan">
                                        <option value="@{{ plan.id }}" ng-repeat="plan in list">@{{ plan.name + ' ' + '($'+ plan.amount + '/month)' }}</option>
                                    </select>
                                </div>
                                
                                <button type="button" class="btn btn-primary" ng-click="save_card()">
                                    {{ __('Submit') }} 
                                    <span ng-show="user_plan"></span>
                                </button>
                            </form> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/ng-template" id="removeTooltip.html">
	<span>{{ __('You can\'t remove a plan with active users on it. First change a plan for those users or remove them') }}</span>
</script>