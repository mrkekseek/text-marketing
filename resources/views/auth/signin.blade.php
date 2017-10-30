<div class="page-signin" ng-controller="AuthCtrl">
    <div class="signin-header">
		<div class="container">
			<div class="wrap-logo text-center">
				<a href="/">
					<img src="/img/logo.jpg" alt="" />
				</a>
			</div>
		</div>
    </div>

    <div class="signin-body">
        <div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="form-container">
						<form class="form-horizontal" name="form" method="post" novalidate="novalidate">
							<fieldset>
								<div class="form-group">
									<div class="input-group input-group-first">
										<span class="input-group-addon">
											<span class="fa fa-envelope-o"></span>
										</span>
										<input type="email" name="email" class="form-control input-lg" placeholder="{{ __('Email') }}" ng-model="auth.email" required="required" />
									</div>
								</div>
								
								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon">
											<span class="fa fa-lock"></span>
										</span>
										<input type="password" name="password" class="form-control input-lg" placeholder="{{ __('Password') }}" ng-model="auth.password" required="required" />
									</div>
								</div>

								<div class="btn-log-in">
									<div class="form-group">
										<button type="submit" class="btn btn-primary btn-lg btn-block text-center" ng-class="{'btn-load': request_sent}" ng-click="signin()"><span class="loading-text">{{ __('Log in') }}</span><i class="fa fa-spinner fa-pulse fa-3x fa-fw loading-icon"></i></button>
									</div>
								</div>
							</fieldset>
						</form>
						
						<section class="additional-info">
							<a href="/pages/recovery">
								<i class="fa fa-lock" aria-hidden="true"></i>
								{{ __('Forgot your password?') }}
							</a>

                        	<a href="/pages/support" class="pull-right">
                        		<i class="fa fa-life-ring" aria-hidden="true"></i>
                        		{{ __('Support') }}
                    		</a>
						</section>
					</div>
				</div>
        	</div>
    	</div>
	</div>
</div>