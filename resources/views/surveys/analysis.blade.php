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
						<div class="row">
							<div class="col-xs-12">
								<div class="form-group text-center">
									<h4 class="overall-title">{{ __('OVERALL') }}</h4>
									<div class="question-stars">
										<div class="question-stars-inner" style="width: @{{ analysis.rating * 100 / 5 }}%">
										</div>
										<img src="/img/stars.png" alt="">
									</div>
									<div class="question-score">
										@{{ analysis.rating }}
									</div>
									<a href="javascript:;" class="link-results" uib-popover-template="popover.templateUrl">@{{ analysis.responses }} {{ __('Response') }}@{{ analysis.responses > 1 ? 's' : '' }}</a>
									
									<script type="text/ng-template" id="popoverTemplate.html">
										<div class="popover-content">
											<table>
												<tbody>
													<tr ng-repeat="r in [5, 4, 3, 2, 1]">
														<td class="stars-cell">
															<i class="fa fa-star" ng-repeat="s in getStars(r) track by $index"></i>
														</td>
														<td class="results-cell">@{{ responses[r] }} {{ __('Responses') }}</td>
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
									<h3 class="panel-title">{{ __('Calendar') }}</h3>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-4">
											<div ng-model="calendarDate" uib-datepicker ng-change="getCalendar()" datepicker-options="calendarOptions">
												<div uib-daypicker ng-switch-when="day" tabindex="0">
												</div>
											</div>
										</div>
										<div class="col-sm-8">
											<div ng-show="! calendarResponses.length" class="alert alert-info text-center">
												<p>{{ __('Sorry, there is no data for this range') }}</p>
											</div>
											<uib-accordion ng-show="calendarResponses.length" close-others="true">
												<div class="question-box" ng-repeat="item in calendarResponses">
													<div uib-accordion-group class="panel-default">
														<uib-accordion-heading>
															<span>@{{ item.created_at | date: 'MMMM d' }}<span class="text-lowercase">@{{ getSuffix(item.created_at | date: 'd') }}</span> @{{ item.created_at | date: 'h:mm a' }}</span>
															<span class="pull-right">@{{ item.value.toFixed(1) }}</span>
														</uib-accordion-heading>
														<div>
															<div class="question-answer-item" ng-class="{'border-bottom': item.seances.length > 1}" ng-repeat="seance in item.seances track by $index">
																<div class="row">
																	<div class="col-sm-3">
																		<b>@{{ seance.clients.firstname; seance.clients.lastname}}</b>
																		<span ng-show="! seance.clients.firstname && ! seance.clients.firstname">{{ __('Anonymous') }}</span>
																	</div>
																	<div class="col-sm-3">
																		<span ng-show="seance.value" class="stars-cell">
																			<i class="fa fa-star" ng-repeat="s in getStars(seance.value) track by $index"></i>
																		</span>
																		<span ng-show="! seance.value">
																			{{ __('N/A') }}
																		</span>
																	</div>
																	<div class="col-sm-3">
																		<span ng-show="seance.comments" class="small-italic prev-title" ng-click="seance.showComments = ! seance.showComments">{{ __('Click to see comments') }}</span>
																	</div>
																	<div class="col-sm-3">
																		<span class="pull-right">
																		  @{{ seance.completed | date: 'MMMM d' }}@{{ getSuffix(seance.completed | date: 'd') }} @{{ seance.completed | date: 'h:mm a' }}
																		</span>
																	</div>
																</div>
																<div ng-show="seance.showComments">
																	@{{ seance.comments }}
																</div>
															</div>
														</div>
													</div>
												</div>
											</uib-accordion>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div id="written_comments" class="panel panel-default panel-analysis">
							<div class="panel-heading">
								<h3 class="panel-title">
									<b>{{ __('Written Comments') }}</b>
								</h3>
							</div>
							<div class="panel-body rates">
								<div class="row">
									<div class="col-xs-12">
										<table width="100%" class="table table-striped">
											<thead>	
												<tr>
													<th class="text-center">{{ __('Date') }}</th>
													<th class="text-center">{{ __('Name') }}</th>
													<th class="text-center">{{ __('Stars') }}</th>
													<th class="text-center">{{ __('Reason') }}</th>
												</tr>
											</thead>
											<tbody>
												<tr ng-repeat="seance in analysis.comments track by $index" ng-show="seance.comments">
													<td>@{{ seance.completed | date: 'MMMM d' }}@{{ getSuffix(seance.completed | date: 'd') }} @{{ seance.completed | date: 'h:mm a' }}</td>
													<td>@{{ seance.clients.firstname; seance.clients.lastname }}</td>
													<td>
														<span ng-show="seance.value" class="stars-cell">
															<i class="fa fa-star" ng-repeat="s in getStars(seance.value) track by $index"></i>
														</span>
														<span ng-show="! seance.value">{{ __('N/A') }}</span>
													</td>
													<td>@{{ seance.comments }}</td>
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
	</div>
</div>