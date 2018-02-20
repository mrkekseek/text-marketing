<!DOCTYPE html>
<html data-ng-app="app" data-ng-controller="SignUpCtrl" ng-init="init('{{ in_array(strtolower($type), config('plans')) ? $type : 'error' }}')">
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
							<img src="/img/logo.jpg" />
						</a>
					</div>
				</div>
			</div>

			<div class="signin-body">
				<div class="container">
					<div class="row" ng-show="signUpPage == 'show'">
						<div class="col-md-12">
							<div class="form-container">
								<form class="form-horizontal" name="form" method="post" novalidate="novalidate">
									<fieldset>
										<div class="form-group">
											<div class="input-group input-group-first">
												<span class="input-group-addon">
													<span class="fa fa-envelope-o"></span>
												</span>
												<input type="email" name="email" class="form-control input-lg" placeholder="{{ __('Email') }}" ng-model="signUp.email" required="required" />
											</div>
										</div>

										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-lock"></span>
												</span>
												<input type="password" name="password" class="form-control input-lg" placeholder="{{ __('Password') }}" ng-model="signUp.password" required="required" />
											</div>
										</div>

										<div class="form-group">
											<div class="input-group input-group-first">
												<span class="input-group-addon">
													<span class="fa fa-user"></span>
												</span>
												<input type="text" name="firstname" class="form-control input-lg" placeholder="{{ __('First Name') }}" ng-model="signUp.firstname" required="required" />
											</div>
										</div>

										<div class="form-group">
											<div class="input-group input-group-first">
												<span class="input-group-addon">
													<span class="fa fa-user-o"></span>
												</span>
												<input type="text" name="lastname" class="form-control input-lg" placeholder="{{ __('Last Name') }}" ng-model="signUp.lastname" required="required" />
											</div>
										</div>

										<div class="form-group" ng-if="signUp.plans_id == 'home-advisor' ">
											<div class="input-group input-group-first">
												<span class="input-group-addon">
													<span class="fa fa-phone"></span>
												</span>
												<input type="text" name="view_phone" class="form-control input-lg" placeholder="{{ __('Your Cell (for testing)') }}" ng-model="signUp.view_phone" ng-required="signUp.plans_id == 'home-advisor'" />
											</div>
										</div>

										<div class="form-group" ng-if="signUp.plans_id == 'home-advisor' ">
											<div class="input-group input-group-first">
												<span class="input-group-addon">
													<span class="fa fa-home"></span>
												</span>
												<input type="text" name="rep" class="form-control input-lg" placeholder="{{ __('HomeAdvisor Account #') }}" ng-model="signUp.rep" ng-required="signUp.plans_id == 'home-advisor'" />
											</div>
										</div>

										<br />

										<div class="form-group" style="text-align: justify;">
											{{ __('By creating this account you agree with our') }}
											<a href="https://www.contractortexter.com/terms" target="_blank">{{ __('Terms') }}</a>
											{{ __('and') }}
											<a href="https://www.contractortexter.com/privacy" target="_blank">{{ __('Privacy') }}</a>
											{{ __('Policy') }}
										</div>

										<div class="btn-log-in">
											<div class="form-group">
												<button type="submit" class="btn btn-primary btn-lg btn-block text-center" ng-class="{'btn-load': request_sent}" ng-click="signup()">
													<span class="loading-text">{{ __('Sign Up') }}</span>
													<i class="fa fa-spinner fa-pulse fa-3x fa-fw loading-icon"></i>
												</button>
											</div>
										</div>
									</fieldset>
								</form>

								<section class="additional-info">
									<a href="/">
										<i class="fa fa-lock" aria-hidden="true"></i>
										{{ __('Log In') }}
									</a>

									<a href="/support" class="pull-right">
										<i class="fa fa-life-ring" aria-hidden="true"></i>
										{{ __('Support') }}
									</a>
								</section>
							</div>
						</div>
					</div>

					<div class="form-container text-center" ng-show="signUpPage == 'error'">
						<b>Sorry, but this URL is incorrect</b>
					</div>
				</div>
			</div>
		</div>

		<script src="/js/libs/angular.js"></script>
		<script src="/js/libs/jquery.js"></script>
		<script src="/js/libs/ui.js"></script>
		<script src="/js/signup.js"></script>
		<script src="/js/factories.js"></script>
	</body>
</html>