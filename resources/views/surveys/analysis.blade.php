<div class="page page-table" data-ng-controller="ReviewsAnalysisCtrl" data-ng-init="init()">
	<div class="row">
		<div class="col-sm-12 col-md-12">
			<h2>
				<span>{{ __('Analysis') }}</span>
				<i class="fa fa-question-circle-o help-icon" uib-tooltip="This is the Analysis page for the Star Rating Question. You can filter by date and see the Written Comments (for responses less than 5 stars). The Response Rate is the % of clients who answered the Star Rating Question. The Social Rate is the % that gave you 5 stars and also left an online review." tooltip-placement="right" aria-hidden="true"></i>
			</h2>
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="text-center">
						<h4>
							<a href="#real" id="real" class="link-header">{{ __('Real-Time') }}</a>&nbsp;•&nbsp;
							<a href="#calendar" class="link-header">{{ __('Calendar') }}</a>&nbsp;•&nbsp;
							<a href="#written_comments" class="link-header">{{ __('Written Comments') }}</a>&nbsp;•&nbsp;
							<a href="#response_rate" class="link-header">{{ __('Response Rate') }}</a>
						</h4>

						<div class="row">
							<div class="col-xs-12">
								<div class="form-group text-center">
									<h4 class="overall-title">{{ __('OVERALL') }}</h4>
									<div class="question-stars">
										<div class="question-stars-inner">
										</div>
										<img src="/img/stars.png" alt="">
									</div>
									<div class="question-score">
										0
									</div>
									<a href="javascript:;" class="link-results" uib-popover-template="popover.templateUrl"> {{ __('Response') }}</a>
									
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