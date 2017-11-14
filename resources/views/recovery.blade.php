<!DOCTYPE html>
<html data-ng-app="app" data-ng-controller="RecoveryCtrl">
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
							<form name="form" method="post" novalidate="novalidate">
								<fieldset>
									<div class="form-group">
										<div class="input-group input-group-first">
											<span class="input-group-addon">
												<span class="fa fa-envelope-o"></span>
											</span>
											<input type="email" name="email" class="form-control input-lg" placeholder="{{ __('Email') }}" ng-model="recovery.email" required="required" />
										</div>
									</div>
									<div class="btn-log-in">
										<div class="form-group">
											<button type="submit" class="btn btn-primary btn-lg btn-block text-center" ng-click="send()">
											{{ __('Send New Password') }}</button>
										</div>
									</div>
									<section class="additional-info">
										<a href="/">
											<i class="fa fa-user" aria-hidden="true"></i>
											{{ __('Sign In') }}
										</a>

										<a href="/support" target="_self" class="pull-right">
											<i class="fa fa-life-ring" aria-hidden="true"></i>
											{{ __('Support') }}
										</a>
									</section>
								</fieldset>
							</form>
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
	<script src="/js/recovery.js"></script>
	<script src="/js/factories.js"></script>
</body>
</html>