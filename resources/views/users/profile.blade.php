<div class="page page-table ng-scope" data-ng-controller="UsersCtrl" data-ng-init="get()">
	<h2>
		Profile	</h2>

	<div class="row">
		<div class="col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<span>Main Info</span>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<label>First Name</label>
						<input type="text" name="firstname" data-field="users_firstname" ng-model="user.users_firstname" class="form-control ng-pristine ng-untouched ng-valid ng-not-empty ng-valid-required" required="required">
					</div>
					<div class="form-group">
						<label>Last Name</label>
						<input type="text" name="lastname" data-field="users_lastname" ng-model="user.users_lastname" class="form-control ng-pristine ng-untouched ng-valid ng-not-empty">
					</div>
					<div class="form-group">
				    	<label>Email</label>
						<input type="email" class="form-control ng-pristine ng-untouched ng-valid ng-not-empty ng-valid-email ng-valid-required" name="users_email" ng-model="user.users_email" required="required">
			    	</div>
					<div class="form-group">
				    	<label>Phone</label>
						<input type="text" class="form-control ng-pristine ng-untouched ng-valid ng-empty" ng-model="user.users_phone">
			    	</div>
			    	
			    	<div class="form-group" ng-show="constants.project != 'ContractorReviewer'">
			    		<label class="ui-switch ui-switch-success ui-switch-sm">
							<input type="checkbox" ng-model="user.teams_leader" class="ng-pristine ng-untouched ng-valid ng-empty">
							<i></i>
						</label>
						<strong class="team-leader"> Team Leader</strong>
					</div>
					
					<div class="form-group" ng-show="plan" style="">
						<label>Your plan: </label>
						<span class="ng-binding">TEXT</span>
						<span class="small-italic ng-binding">(54$ / month)</span>
						<a href="" ng-click="cancel_plan()"> unsubscribe</a>
					</div>
					<div class="text-right">
						<button type="submit" class="btn btn-primary" ng-click="save();">Save Profile</button>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<form name="form" class="ng-pristine ng-invalid ng-invalid-required">
				<div class="panel panel-default">
					<div class="panel-heading">
						<span>Change Password</span>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<label>Current Password</label>
							<input type="password" name="old_password" value="" ng-model="old_password" class="form-control ng-pristine ng-untouched ng-empty ng-invalid ng-invalid-required" required="required">
						</div>

						<div class="form-group">
							<label>New Password</label>
							<input type="password" name="new_password" value="" ng-model="new_password" class="form-control ng-pristine ng-untouched ng-empty ng-invalid ng-invalid-required" required="required">
						</div>

						<div class="form-group">
							<label>Confirm Password</label>
							<input type="password" name="confirm_password" value="" ng-model="confirm_password" class="form-control ng-pristine ng-untouched ng-empty ng-invalid ng-invalid-required" required="required">
						</div>

						<div class="text-right">
							<button type="submit" class="btn btn-primary" ng-click="change_password();">Change Password</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>