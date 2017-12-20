<!DOCTYPE html>
<html data-ng-app="app" data-ng-controller="PlansCtrl">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>{{ config('app.name') }}</title>

        <link rel="stylesheet" type="text/css" href="/css/libs/bootstrap.min.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="/css/libs/font-awesome.min.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="/css/style.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="/css/main.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="/css/app.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="/css/{{ config('app.name') }}.css" media="screen" />
    </head>

    <body id="app" class="app">
        <div class="page-signin">
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
											<div id="card-element" class="field is-empty">
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
											{{ __('Sign up ') }} 
											<span ng-show="user_plan"></span>
										</button>
									</div>
								</form>
                            </div>
                            <div>
								<button type="button" class="btn btn-default btn-block" ng-click="signout()">{{ __('Login to another account') }}</button>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="/js/libs/angular.js"></script>
        <script src="/js/libs/jquery.js"></script>
        <script src="/js/libs/ui.js"></script>
        <script src="/js/libs/ng-file-upload.min.js"></script>
        <script src="/js/plans.js"></script>
        <script src="/js/factories.js"></script>
    </body>
</html>