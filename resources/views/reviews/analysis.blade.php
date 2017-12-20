<div class="page page-table ng-scope" ng-controller="MarketingSettingsCtrl">
	<h2>
		Reviews Analysis		
		<i class="fa fa-question-circle-o help-icon" uib-tooltip="This is the Analysis page for your Online Reviews. We pull your reviews from Google, Yelp and Facebook and post them here. If you have additional teammates on your account, we connect your reviews to the teammate who brought in that review, so you can incentivize your team." tooltip-placement="right" aria-hidden="true"></i>
	</h2>

	<div class="panel panel-default">
		<div class="panel-body">
			<div class="text-center">
				<div class="form-group">
					<div class="btn-group timeframe">
						<a href="javascript:void(0);" ng-click="toggle_date('today')" ng-class="{'active': analysis.timeframe == 'today'}" class="btn btn-default btn-primary active">
							Today	
						</a>
						<a href="javascript:void(0);" ng-click="toggle_date('week')" ng-class="{'active': analysis.timeframe == 'week'}" class="btn btn-default btn-primary">
							Week	
						</a>
						<a href="javascript:void(0);" ng-click="toggle_date('month')" ng-class="{'active': analysis.timeframe == 'month'}" class="btn btn-default btn-primary">
						Month						</a>
						<a href="javascript:void(0);" ng-click="toggle_date('year')" ng-class="{'active': analysis.timeframe == 'year'}" class="btn btn-default btn-primary">
						Year						</a>
						<a href="javascript:void(0);" ng-click="toggle_date('custom')" ng-class="{'active': open_period}" class="btn btn-default btn-primary">
						Custom						</a>
					</div>
				</div>
				<div class="form-group ng-hide">
					<div class="row">
						<div class="col-md-3 col-md-offset-3">
							<div class="input-group">
								<input type="text" class="form-control ng-pristine ng-untouched ng-isolate-scope ng-empty ng-invalid ng-invalid-date" ng-change="get()" uib-datepicker-popup="" ng-model="from" is-open="from_switch.opened" placeholder="Date from"><div uib-datepicker-popup-wrap="" ng-model="date" ng-change="dateSelection(date)" template-url="/uib/template/datepickerPopup/popup.html" class="ng-pristine ng-untouched ng-valid ng-scope ng-not-empty">
								</div>
								<span class="input-group-btn">
									<button type="button" class="btn btn-default" ng-click="custom_from()"><i class="glyphicon glyphicon-calendar"></i></button>
								</span>
							</div>
						</div>
						<div class="col-md-3">
							<div class="input-group">
								<input type="text" class="form-control ng-pristine ng-untouched ng-isolate-scope ng-empty ng-invalid ng-invalid-date" ng-change="get()" uib-datepicker-popup="" ng-model="to" is-open="to_switch.opened" placeholder="Date to"><div uib-datepicker-popup-wrap="" ng-model="date" ng-change="dateSelection(date)" template-url="/uib/template/datepickerPopup/popup.html" class="ng-pristine ng-untouched ng-valid ng-scope ng-not-empty"><!-- ngIf: isOpen -->
								</div>
								<span class="input-group-btn">
									<button type="button" class="btn btn-default" ng-click="custom_to()"><i class="glyphicon glyphicon-calendar"></i></button>
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="btn-group sort" data-id="sort" role="group">
						<a href="javascript:void(0);" ng-click="toggle_order('wtb')" ng-class="{'active': analysis.order == 'wtb'}" class="btn btn-default btn-primary">Worst to Best</a>
						<a href="javascript:void(0);" ng-click="toggle_order('btw')" ng-class="{'active': analysis.order == 'btw'}" class="btn btn-default btn-primary">Best to Worst</a>
						<a href="javascript:void(0);" ng-click="toggle_order('newest')" ng-class="{'active': analysis.order == 'newest'}" class="btn btn-default btn-primary active">Newest</a>
					</div>
				</div>

				<div class="form-group">
					<a href="/reviews/corporate/" class="btn btn-primary">Corporate</a>
				</div>
				<div class="row">
					<select ng-show="false" class="form-control providers-list ng-pristine ng-untouched ng-valid ng-not-empty ng-hide active" ng-class="{'active': user.teams_leader == 1}" ng-model="analysis.users_id" ng-change="toggle_users(analysis.users_id)">
						<option value="all" selected="selected">All Users</option>
						<!-- ngRepeat: user in users --><option ng-repeat="user in users" value="24" class="ng-binding ng-scope" style="">Test Tester</option><!-- end ngRepeat: user in users --><option ng-repeat="user in users" value="14" class="ng-binding ng-scope">Tester Developer</option><!-- end ngRepeat: user in users -->
					</select>

					<select ng-show="constants.project != 'ContractorReviewer' &amp;&amp; constants.project != 'ContractorTexter' &amp;&amp; constants.project != 'ReviewMyRehab'" class="form-control providers-list ng-pristine ng-untouched ng-valid ng-not-empty ng-hide active" ng-class="{'active': user.teams_leader == 1}" ng-model="analysis.providers_id" ng-change="toggle_providers(analysis.providers_id)">
						<option value="all" selected="selected">All Employees</option>
						<!-- ngRepeat: provider in providers -->
					</select>
				</div>

				<div class="overall-section">
					<h4 class="overall-title">OVERALL</h4>
					<div class="question-stars">
						<div class="question-stars-inner">
						</div>
						<img src="/img/stars.png" alt="">
					</div>
					<div class="question-score ng-binding">
						0.0
					</div>
				</div>

				<div class="row">
					<!-- ngRepeat: (i, item) in list track by $index -->
				</div>

				<div class="most-reviews">
					<div class="panel panel-default" ng-show="(most_providers.length &amp;&amp; user.teams_leader == 1) || (constants.project == 'ContractorTexter' || constants.project == 'ContractorReviewer' || constants.project == 'ReviewMyRehab')">
						<div class="panel-heading">
							<span>Employees bringing the most 5 Star reviews</span>
						</div>
						<div class="panel-body">
							<div class="form-group">
								<div class="btn-group timeframe">
									<a href="javascript:void(0);" ng-click="toggle_providers_date('today')" ng-class="{'active': providers_analysis.timeframe == 'today'}" class="btn btn-default btn-primary active">
										Today	
									</a>
									<a href="javascript:void(0);" ng-click="toggle_providers_date('week')" ng-class="{'active': providers_analysis.timeframe == 'week'}" class="btn btn-default btn-primary">
										Week	
									</a>
									<a href="javascript:void(0);" ng-click="toggle_providers_date('month')" ng-class="{'active': providers_analysis.timeframe == 'month'}" class="btn btn-default btn-primary">
									Month									</a>
									<a href="javascript:void(0);" ng-click="toggle_providers_date('year')" ng-class="{'active': providers_analysis.timeframe == 'year'}" class="btn btn-default btn-primary">
									Year									</a>
									<a href="javascript:void(0);" ng-click="toggle_providers_date('custom')" ng-class="{'active': open_providers_period}" class="btn btn-default btn-primary">
									Custom									</a>
								</div>
							</div>

							<div class="form-group ng-hide" ng-show="open_providers_period">
								<div class="row">
									<div class="col-md-3 col-md-offset-3">
										<div class="input-group">
											<input type="text" class="form-control ng-pristine ng-untouched ng-isolate-scope ng-empty ng-invalid ng-invalid-date" ng-change="get_most_providers()" uib-datepicker-popup="" ng-model="providers_from" is-open="providers_from_switch.opened" placeholder="Date from"><div uib-datepicker-popup-wrap="" ng-model="date" ng-change="dateSelection(date)" template-url="/uib/template/datepickerPopup/popup.html" class="ng-pristine ng-untouched ng-valid ng-scope ng-not-empty"><!-- ngIf: isOpen -->
											</div>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default" ng-click="custom_providers_from()"><i class="glyphicon glyphicon-calendar"></i></button>
											</span>
										</div>
									</div>
									<div class="col-md-3">
										<div class="input-group">
											<input type="text" class="form-control ng-pristine ng-untouched ng-isolate-scope ng-empty ng-invalid ng-invalid-date" ng-change="get_most_providers()" uib-datepicker-popup="" ng-model="providers_to" is-open="providers_to_switch.opened" placeholder="Date to"><div uib-datepicker-popup-wrap="" ng-model="date" ng-change="dateSelection(date)" template-url="/uib/template/datepickerPopup/popup.html" class="ng-pristine ng-untouched ng-valid ng-scope ng-not-empty"><!-- ngIf: isOpen -->
											</div>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default" ng-click="custom_providers_to()"><i class="glyphicon glyphicon-calendar"></i></button>
											</span>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="btn-group sort" data-id="sort" role="group">
									<a href="javascript:void(0);" ng-click="toggle_providers_order('wtb')" ng-class="{'active': providers_analysis.order == 'wtb'}" class="btn btn-default btn-primary">Worst to Best</a>
									<a href="javascript:void(0);" ng-click="toggle_providers_order('btw')" ng-class="{'active': providers_analysis.order == 'btw'}" class="btn btn-default btn-primary active">Best to Worst</a>
								</div>
							</div>
							<div class="alert alert-info" ng-show="constants.project == 'ContractorTexter' || constants.project == 'ContractorReviewer' || constants.project == 'ReviewMyRehab'">
								<span>This Works When You have Additional Employees</span>
							</div>
							<div class="row">
								<!-- ngRepeat: employee in most_providers -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

