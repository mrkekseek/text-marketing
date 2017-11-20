<div class="page page-table ng-scope" data-ng-controller="MarketingContactsCtrl" >
	<div class="row">
		<div class="col-md-4 col-sm-12 hidden-xs">
			<h2>
				All Contacts
				<i uib-tooltip="This is where you create Contact Lists to send texts to. You can enter Contacts manually one at a time or upload a CSV. The first row of the CSV needs to be the 'First Name', the second row needs to be the 'Last Name' and the third row needs to be their Phone Number." tooltip-placement="right" aria-hidden="true">
				</i>
			</h2>
			<div class="panel panel-default">
				<div class="panel-body">
					<!-- ngRepeat: phone in master_phones -->
				</div>
			</div>
		</div>
		<div class="col-md-8 col-sm-12">
			<h2>
				<div class="pull-right">
					<button type="button" class="btn btn-default" ng-click="create()"><i class="fa fa-plus-circle"></i><span class="hidden-xs"> Create New List</span></button>
				</div>
				Contact Lists
			</h2>
			<div>
				<section class="panel panel-default table-dynamic">
					<div class="panel-body">
						<div class="content-loader ng-hide" ng-show=" request_finish" style="">
							<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
						</div>
						<div ng-show="newList" class="alert-info ng-isolate-scope alert" role="alert">
							<div>
								You don't have any list yet. <a href="javascript:;" ng-click="create()" class="ng-scope">Create your first list</a> right now.
							</div>
						</div>
						<div class="row-name" ng-show="!newList">
							<span  class="ng-binding ng-hide"></span>
							<div class="row edit-main-container">
								<div class="col-sm-12 col-md-8 col-lg-9">
									<input class="form-control ng-pristine ng-valid ng-empty ng-touched" type="text" placeholder="List Name" ng-model="item.lists_name" focus-me="item.editable">
								</div>
								<div class="col-sm-12 col-md-4 col-lg-3">
									<div class="btn-group btn-group-justified">
										<div class="btn-group">
											<button type="button" class="btn btn-default" ng-click="cancel()">Cancel</button>
										</div>
										<div class="btn-group">
											<button type="button" class="btn btn-primary" ng-click="save()">Save</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
</div>