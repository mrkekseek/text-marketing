<div class="page page-table ng-scope" ng-controller="MarketingSettingsCtrl">
	<h2>
		Review Sites		<i ng-show="constants.project == 'ContractorReviewer' || constants.project == 'ContractorTexter' || constants.project == 'ReviewMyRehab'" class="fa fa-question-circle-o help-icon" uib-tooltip-template="'thankYouTooltip.html'" tooltip-placement="right-top" aria-hidden="true"></i>
	</h2>

	<div class="row">
		<div class="col-md-9 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<form name="form" class="ng-pristine ng-valid">
						<div class="divider"></div>
						<div class="form-horizontal ng-scope">
							<div class="form-group">
								<label class="col-sm-3 control-label"><span class="pull-left ng-binding"><i class="fa fa-yelp reviews-icons" aria-hidden="true"></i> Yelp</span></label>
								<div class="col-sm-6">
									<div ng-class="{'input-group': url.name == 'Facebook'}">
										<input name="Yelp" type="text" class="form-control ng-pristine ng-untouched ng-valid ng-empty" ng-model="user['users_' + (url.name).toLowerCase()]">
										<span class="input-group-btn ng-hide" ng-show="url.name == 'Facebook'">
											<button type="button" class="btn btn-default" ng-click="social_login(url.name);" ng-class="{'btn-success disabled': user_logged}">
												<span ng-show="!user_logged" class="">Login</span>
												<span ng-show="user_logged" class="ng-hide">Logged</span>
											</button>
										</span>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="ui-switch ui-switch-success ui-switch-sm">
										<input type="checkbox" name="active-Yelp" ng-click="change_auto_review()" ng-model="user['users_' + (url.name).toLowerCase() + '_btn']" ng-true-value="'1'" ng-false-value="'0'" class="ng-pristine ng-untouched ng-valid ng-empty">
										<i></i>
									</label>
									<span class="team-leader ng-scope" ng-if="user['users_' + (url.name).toLowerCase() + '_btn'] == 0"></span>
									<a href="" ng-click="get_review(url.name)" ng-show="user.users_debug_mode == 1 &amp;&amp; user['users_' + (url.name).toLowerCase() + ((url.name == 'Google') ? '_place' : '') + '_id']" class="btn btn-primary pull-right btn-yelp ng-hide" style="width: 90px;">Yelp</a>
								</div>
							</div>
							<a id="redirectClick" style="display: none" href="#"></a>
							<div class="divider divider-dashed divider-lg pull-in"></div>
						</div>
						<div class="form-horizontal ng-scope" >
							<div class="form-group">
								<label class="col-sm-3 control-label"><span class="pull-left ng-binding"><i class="fa fa-google reviews-icons" aria-hidden="true"></i> Google</span></label>
								<div class="col-sm-6">
									<div ng-class="{'input-group': url.name == 'Facebook'}">
										<input name="Google" type="text" class="form-control ng-pristine ng-untouched ng-valid ng-empty" ng-model="user['users_' + (url.name).toLowerCase()]">
										<span class="input-group-btn ng-hide" ng-show="url.name == 'Facebook'">
											<button type="button" class="btn btn-default" ng-click="social_login(url.name);" ng-class="{'btn-success disabled': user_logged}">
												<span ng-show="!user_logged" class="">Login</span>
												<span ng-show="user_logged" class="ng-hide">Logged</span>
											</button>
										</span>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="ui-switch ui-switch-success ui-switch-sm">
										<input type="checkbox" name="active-Google" ng-click="change_auto_review()" ng-model="user['users_' + (url.name).toLowerCase() + '_btn']" ng-true-value="'1'" ng-false-value="'0'" class="ng-pristine ng-untouched ng-valid ng-empty">
										<i></i>
									</label><span class="team-leader ng-scope"></span>
									<a href="" ng-click="get_review(url.name)" ng-show="user.users_debug_mode == 1 &amp;&amp; user['users_' + (url.name).toLowerCase() + ((url.name == 'Google') ? '_place' : '') + '_id']" class="btn btn-primary pull-right btn-google ng-hide" style="width: 90px;">Google</a>
								</div>
							</div>
							<a id="redirectClick" style="display: none" href="#"></a>
							<div class="divider divider-dashed divider-lg pull-in"></div>
						</div>

						<div class="form-horizontal ng-scope">
							<div class="form-group">
								<label class="col-sm-3 control-label"><span class="pull-left ng-binding"><i class="fa fa-facebook reviews-icons" aria-hidden="true"></i> Facebook</span></label>
								<div class="col-sm-6">
									<div ng-class="{'input-group': url.name == 'Facebook'}" class="input-group">
										<input name="Facebook" type="text" class="form-control ng-pristine ng-untouched ng-valid ng-empty" >
										<span class="input-group-btn" >
											<button type="button" class="btn btn-default" ng-click="social_login(url.name);" >
												<span class="">Login</span>
												<span ng-show="user_logged" class="ng-hide">Logged</span>
											</button>
										</span>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="ui-switch ui-switch-success ui-switch-sm">
										<input type="checkbox" name="active-Facebook" ng-click="change_auto_review()" ng-model="user['users_' + (url.name).toLowerCase() + '_btn']" ng-true-value="'1'" ng-false-value="'0'" class="ng-pristine ng-untouched ng-valid ng-empty">
										<i></i>
									</label>
									<span class="team-leader ng-scope" ng-if="user['users_' + (url.name).toLowerCase() + '_btn'] == 0"></span>
									<a href="" ng-click="get_review(url.name)" ng-show="user.users_debug_mode == 1 &amp;&amp; user['users_' + (url.name).toLowerCase() + ((url.name == 'Google') ? '_place' : '') + '_id']" class="btn btn-primary pull-right btn-facebook ng-hide" style="width: 90px;">Facebook</a>
								</div>
							</div>
							<a id="redirectClick" style="display: none" href="#"></a>
							<div class="divider divider-dashed divider-lg pull-in"></div>
						</div><!-- end ngRepeat: url in urls track by $index -->
						<div class="form-group">
							<button type="button" class="btn btn-primary" ng-click="save_urls();">Save Pages</button>
							<i ng-show="constants.project == 'ContractorReviewer' || constants.project == 'ContractorTexter' || constants.project == 'ReviewMyRehab'" class="fa fa-question-circle-o help-icon-review" uib-tooltip="If only Google is Active, your 5 star reviewers will be redirected straight to Google Reviews. If Facebook or Yelp is Active, 5 star reviewers will be redirected to a Page where they can click and go to those. Note: Reviewer needs the Facebook/Yelp app to access those." tooltip-placement="right" aria-hidden="true"></i>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>