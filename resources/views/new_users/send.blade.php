<div class="page page-table" data-ng-controller="NewUsersCtrl" data-ng-init="init()">
	<h2>
		{{ __('New Users') }}
	</h2>

    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
						<div class="col-sm-12" id="new_users_texts">
							<div class="form-group">
								<label>{{ __('Thank You Text') }}</label>
								<div text-area ng-model="texts.thankyou_text"></div>
                            </div>
                            
                            <div class="form-group">
								<label>{{ __('2 Days After Signup Text') }}</label>
								<div text-area ng-model="texts.twodays_text"></div>
                            </div>
                            
                            <div class="form-group">
								<label>{{ __('4 Days After Signup Text') }}</label>
								<div text-area ng-model="texts.fourdays_text"></div>
							</div>
							
							<div class="form-group">
					    		<button type="button" class="btn btn-primary" ng-click="save();">{{ __('Save') }}</button>
				    		</div>
						</div>
					</div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6">
            <div class="panel panel-default" ng-show="list.length">
                <div class="panel-heading">
                    <strong>{{ __('Send texts to new users') }}</strong>
                </div>

                <div class="panel-body leads">
                    
                </div>
            </div>
        </div>
    </div>
</div>