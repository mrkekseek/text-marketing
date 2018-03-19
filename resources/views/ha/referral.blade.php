<div class="page page-table" data-ng-controller="HomeAdvisorCtrl" ng-init="init()">
	<h2>
		{{ __('Want 3 Free Months? Refer 1 Friend') }}
	</h2>

	<div class="row">
		<div class="col-sm-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="referral_section ref-page">
                        <form name="form" novalidate="novalidate">
                            <div uib-alert class="alert-info">
                                1. Just enter your friend’s name + contact info<br />
								2. If they sign up, you get 3 free months<br />
								3. 3 Friends - 1 year free
                            </div>

                            <div class="form-group">
                                <label>{{ __('Friend’s Name:') }}</label>
                                <input type="text" name="name" class="form-control" ng-model="referral.name" placeholder="{{ __('Enter name here...') }}" required="required" />
                            </div>

                            <div class="form-group">
                                <label>{{ __('Number or Email:') }}</label>
                                <input type="text" name="contacts" class="form-control" ng-model="referral.contacts" placeholder="{{ __('Enter number or email here...') }}" required="required" />
                            </div>

                            <button class="btn btn-default" ng-click="registerReferral()">{{ __('Send') }}</button>
                        </form>
                    </div>
                </div>
            </div>
		</div>
	</div>
</div>