<div class="page page-table" ng-controller="CorporateReviewCtrl">
	<h2>
		Corporate Reviews Analysis
	</h2>
	<div class="panel panel-default text-center">
		<div class="panel-body">
			<div class="alert alert-info text-center" ng-show="! list.length">
				<p>Sorry, there is no data for this range</p>
			</div>
			<div class="form-group">
				<div class="btn-group sort" data-id="sort" role="group">
					<a href="javascript:void(0);" class="btn btn-default btn-primary active">Worst to Best</a>
					<a href="javascript:void(0);" class="btn btn-default btn-primary">Best to Worst</a>
				</div>
			</div>
			<div class="form-group">
				<a href="/reviews/analysis/" class="btn btn-primary">Back to Reviews Analysis</a>
			</div>
			<div class="row" ng-show="list.length">
				<div class="col-sm-6 col-xs-12" ng-repeat="item in list">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="question-text">
								@{{ item.firstName + ' ' + item.lastName }}
							</div>
							<div class="question-stars">
								<div class="question-stars-inner"">
								</div>
								<img src="/img/stars.png" alt="stars">
							</div>
							<div class="question-score">
								5.0
							</div>
							<div>
								<a href="javascript:void(0);" class="link-results ng-binding" uib-popover-template="popover.templateUrl">
									3 Responses
								</a>
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
							<br>
							<div class="row">
								<div class="col-sm-12">
									<i class="fa fa-yelp reviews-analysis-icons" aria-hidden="true"></i>
									<strong >Yelp</strong>
									<div>
										<strong>5.0</strong>
									</div>
									<div>
										3 Responses
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