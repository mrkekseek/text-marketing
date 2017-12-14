<div class="page page-table ng-scope" data-ng-controller="UsersCtrl">
	<h2>Profile</h2>

	<div class="row">
		<div class="col-sm-6 col-xs-12">
			<form name="form" novalidate="novalidate">
				<div class="panel panel-default">
					<div class="panel-heading">
						<span>Main Info</span>
					</div>

					<div class="panel-body">
						<div class="form-group">
							<label>First Name</label>
							<input type="text" name="firstname" ng-model="user.firstname" class="form-control" required="required" />
						</div>

						<div class="form-group">
							<label>Last Name</label>
							<input type="text" name="lastname" ng-model="user.lastname" class="form-control" />
						</div>

						<div class="form-group">
							<label>Email</label>
							<input type="email" name="email" ng-model="user.email" class="form-control" required="required" />
						</div>

						<div class="form-group">
							<label>Phone</label>
							<input type="text" name="phone" ng-model="user.view_phone" class="form-control" />
						</div>

						<div class="text-right">
							<button type="submit" class="btn btn-primary" ng-click="profile();">Save Profile</button>
						</div>
					</div>
				</div>
			</form>
		</div>

		<div class="col-sm-6 col-xs-12">
			<form name="form_password" novalidate="novalidate">
				<div class="panel panel-default">
					<div class="panel-heading">
						<span>Change Password</span>
					</div>

					<div class="panel-body">
						<div class="form-group">
							<label>Current Password</label>
							<input type="password" name="old_password" ng-model="pass.old_password" class="form-control" required="required" />
						</div>

						<div class="form-group">
							<label>New Password</label>
							<input type="password" name="password" ng-model="pass.password" class="form-control" required="required" />
						</div>

						<div class="form-group">
							<label>Password Confirmation</label>
							<input type="password" name="password_confirmation" ng-model="pass.password_confirmation" class="form-control" required="required" />
						</div>

						<div class="text-right">
							<button type="submit" class="btn btn-primary" ng-click="password();">Change Password</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>