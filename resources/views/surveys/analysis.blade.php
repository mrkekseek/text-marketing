<div class="page page-table ng-scope" data-ng-controller="ReviewsAnalysisCtrl">
	<div class="row">
		<div class="col-sm-12 col-md-12">
			<h2>
				<span>Analysis</span>
				<i class="fa fa-question-circle-o help-icon" uib-tooltip="This is the Analysis page for the Star Rating Question. You can filter by date and see the Written Comments (for responses less than 5 stars). The Response Rate is the % of clients who answered the Star Rating Question. The Social Rate is the % that gave you 5 stars and also left an online review." tooltip-placement="right" aria-hidden="true"></i>
			</h2>
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="text-center">
						<h4>
							<a href="#real" id="real" class="link-header">Real-Time</a>
							&nbsp;•&nbsp;
							<a ng-show="constants.project != 'EngagedER'" href="#calendar" class="link-header">Calendar</a>
							<span ng-show="constants.project != 'EngagedER'" class="">&nbsp;•&nbsp;</span>
							<a href="#written_comments" "="" class="link-header" du-smooth-scroll="" du-scrollspy="">Written Comments</a>
							&nbsp;•&nbsp;
							<a ng-show="(constants.project == 'FoodTexter' || constants.project == 'GymTexter') &amp;&amp; team.teams_providers == '1'" href="#providers" class="link-header ng-binding ng-hide" du-smooth-scroll="" du-scrollspy="">Employee</a>
							<span ng-show="(constants.project == 'FoodTexter' || constants.project == 'GymTexter') &amp;&amp; team.teams_providers == '1'" class="ng-hide">&nbsp;•&nbsp;</span>
							<a href="#response_rate" class="link-header" data-move="#rate">Response Rate</a>
						</h4>

						<div class="form-group">
							<div class="btn-group timeframe" data-id="timeframe" role="group">
								<a href="javascript:void(0);" ng-click="toggle_date('today')" ng-class="{'active': analysis.timeframe == 'today'}" data-value="today" class="btn btn-default btn-primary" style="">Today</a>
								<a href="javascript:void(0);" ng-click="toggle_date('week')" ng-class="{'active': analysis.timeframe == 'week'}" data-value="week" class="btn btn-default btn-primary">Week</a>
								<a href="javascript:void(0);" ng-click="toggle_date('month')" ng-class="{'active': analysis.timeframe == 'month'}" data-value="month" class="btn btn-default btn-primary" style="">Month</a>
								<a href="javascript:void(0);" ng-click="toggle_date('year')" ng-class="{'active': analysis.timeframe == 'year'}" data-value="year" class="btn btn-default btn-primary">Year</a>
								<a href="javascript:void(0);" ng-click="toggle_date('custom')" ng-class="{'active': open_period}" data-value="custom" class="btn btn-default btn-primary">Custom</a>
							</div>
						</div>

						<div class="form-group ng-hide" ng-show="open_period">
							<div class="row">
								<div class="col-md-3 col-md-offset-3">
									<div class="input-group">
										<input type="text" class="form-control ng-pristine ng-untouched ng-isolate-scope ng-empty ng-invalid ng-invalid-date" ng-change="get_analysis()" uib-datepicker-popup="" ng-model="from" is-open="from.opened" placeholder="Date from"><div uib-datepicker-popup-wrap="" ng-model="date" ng-change="dateSelection(date)" template-url="/uib/template/datepickerPopup/popup.html" class="ng-pristine ng-untouched ng-valid ng-scope ng-not-empty"><!-- ngIf: isOpen -->
										</div>
										<span class="input-group-btn">
											<button type="button" class="btn btn-default" ng-click="custom_from()"><i class="glyphicon glyphicon-calendar"></i></button>
										</span>
									</div>
								</div>
								<div class="col-md-3">
									<div class="input-group">
										<input type="text" class="form-control ng-pristine ng-untouched ng-isolate-scope ng-empty ng-invalid ng-invalid-date" ng-change="get_analysis()" uib-datepicker-popup="" ng-model="to" is-open="to.opened" placeholder="Date to">
										<div uib-datepicker-popup-wrap="" ng-model="date" ng-change="dateSelection(date)" template-url="/uib/template/datepickerPopup/popup.html" class="ng-pristine ng-untouched ng-valid ng-scope ng-not-empty">
										</div>
										<span class="input-group-btn">
											<button type="button" class="btn btn-default" ng-click="custom_to()"><i class="glyphicon glyphicon-calendar"></i></button>
										</span>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group ng-hide" >
							<div class="btn-group sort" data-id="sort" role="group">
								<a href="javascript:void(0);" ng-click="toggle_sort('wtb')" data-value="wtb" ng-class="{'active': analysis.sort == 'wtb'}" class="btn btn-default btn-primary active">Worst to Best</a>
								<a href="javascript:void(0);" ng-click="toggle_sort('btw')" data-value="btw" ng-class="{'active': analysis.sort == 'btw'}" class="btn btn-default btn-primary">Best to Worst</a>
							</div>
						</div>

						<div class="form-group more-space text-center">
							<button type="button" class="btn btn-primary" ng-click="benchmark()" ng-class="{'active': analysis.benchmark == 1}" ng-show="analysis.teams_id == 0" data-id="benchmark" style="">Benchmark</button>
							<button ng-show="false" ng-click="users_show()" type="button" class="btn btn-primary ng-hide" ng-class="{'active': analysis.users_show == '1'}">Users</button>
							<a href="/analysis/corporate/" class="btn btn-primary ng-hide" ng-show="user.teams_leader == 1 &amp;&amp; team.groups_id != 0">Corporate</a>
							<button ng-show="team.teams_diagnosis == 1" type="button" class="btn btn-primary ng-hide" ng-class="{'active': analysis.diagnosis_show == '1'}" data-target="diagnosis">Diagnosis</button>
							<button ng-show="team.teams_providers == 1 &amp;&amp; providers.length" ng-click="providers_show()" type="button" class="btn btn-primary ng-binding ng-hide" ng-class="{'active': analysis.providers_show == '1'}">Employee</button>
						</div>

						<div class="row">
							<div ng-class="{'col-xs-12': analysis.benchmark == 0, 'col-xs-6': analysis.benchmark == 1}">
								<div class="form-group text-center">
									<h4 class="overall-title " ng-show="constants.project != 'ContractorReviewer' &amp;&amp; constants.project != 'ContractorTexter' &amp;&amp; constants.project != 'ReviewMyRehab'">OVERALL</h4>
									<div class="question-stars">
										<div class="question-stars-inner">
										</div>
										<img src="/img/stars.png" alt="">
									</div>
									<div class="question-score ng-binding">
										0
									</div>
									<a href="javascript:void(0);" class="link-results ng-binding" uib-popover-template="popover.templateUrl">0 Response</a>
									
									<script type="text/ng-template" id="popoverTemplate.html">
										<div class="popover-content">
											<table>
												<tbody>
													<tr ng-repeat="r in [5, 4, 3, 2, 1]">
														<td class="stars-cell">
															<i class="fa fa-star"></i>
														</td>
														<td class="results-cell"> Responses</td>
													</tr>
												</tbody>
											</table>
										</div>
									</script>
								</div>
							</div>
						</div>

						<div id="calendar">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">Calendar</h3>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-4">
											<div class="ng-pristine ng-untouched ng-valid ng-isolate-scope uib-datepicker ng-not-empty ng-valid-date-disabled" ng-model="calendar_date" uib-datepicker >
												<div class="uib-daypicker ng-scope" uib-daypicker ng-switch-when="day" tabindex="0">
													
												</div>
											</div>
										</div>
										<div class="col-sm-8">
											<div class="alert alert-info text-center" ng-show="! responses.length">
												<p>Sorry, there is no data for this range</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div id="written_comments" class="panel panel-default panel-analysis">
							<div class="panel-heading">
								<h3 class="panel-title">
									<b>Written Comments</b>
								</h3>
							</div>
							<div class="panel-body rates">
								<div class="row">
									<div class="col-md-6 col-xs-12 form-group ng-hide">
										<table width="100%" class="table-striped">
											<tbody><tr>
												<th colspan="4" class="text-center comments-stars">
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
												</th>
											</tr>
											<tr>
												<th class="text-center">Date</th>
												<th class="text-center">Name</th>
												<th class="text-center">Reason</th>
											</tr>
										</tbody></table>
									</div>

									<div class="col-xs-12 form-group col-md-6">
										<table width="100%" class="table table-striped ng-scope">
											<thead>	
												<tr>
													<th class="text-center">Date</th>
													<th class="text-center">Name</th>
													<th class="text-center">Stars</th>
													<th class="text-center">Reason</th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>

						<div id="response_rate" class="panel panel-default panel-analysis">
							<div class="panel-heading">
								<h3 class="panel-title">
									<b>Response Rate</b>
								</h3>
							</div>
							<div class="panel-body table-responsive rates">
								<table class="table table-striped">
									<thead>
										<tr>
											<th class="text-center">Sent Surveys</th>
											<th class="text-center">Completed Surveys</th>
											<th class="text-center">Response Rate</th>
											<th class="text-center">Uncompleted Text Surveys
												<i class="fa fa-question-circle text-primary" aria-hidden="true" uib-tooltip="Percentage of Client who got text and clicked link but did not fill out survey"></i>
											</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<b class="ng-binding">0</b>
											</td>
											<td>
												<b class="ng-binding">0</b>
											</td>
											<td>
												<b class="ng-binding">0 %</b>
											</td>
											<td>
												<b class="ng-binding">0 %</b>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>

						<div class="panel panel-default panel-social">
							<div class="panel-heading">
								<h3 class="panel-title"><b>Social Rate</b></h3>
							</div>
							<div class="panel-body table-responsive rates">
								<table class="table table-striped">
									<thead>
										<tr>
											<th class="text-center">Shown the review sites
												<i class="fa fa-question-circle text-primary" aria-hidden="true" uib-tooltip="Amount of Clients that were shown the Thank You page with the Review Sites"></i>
											</th>
											<th class="text-center">Clicked on review sites
												<i class="fa fa-question-circle text-primary" aria-hidden="true" uib-tooltip="Percentage of Clients clicked on one of the review sites"></i>
											</th>
											<th class="text-center">Yelp</th>
											<th class="text-center">Google</th>
											<th class="text-center">Facebook</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><b class="ng-binding">0</b></td>
											<td><b class="ng-binding">0 % (0)</b></td>
											<td><b class="ng-binding">0 % (0)</b></td>
											<td><b class="ng-binding">0 % (0)</b></td>
											<td><b class="ng-binding">0 % (0)</b></td>
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