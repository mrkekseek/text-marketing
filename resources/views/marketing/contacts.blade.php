<div class="page page-table" data-ng-controller="MarketingContactsCtrl" data-ng-init="init()">
	<div class="row">
		<div class="col-md-4 col-sm-12 hidden-xs">
			<h2>
				{{ __('All Contacts') }}
				<span class="fa fa-question-circle-o help-icon" uib-tooltip="This is where you create Contact Lists to send texts to. You can enter Contacts manually one at a time or upload a CSV. The first row of the CSV needs to be the 'First Name', the second row needs to be the 'Last Name' and the third row needs to be their Phone Number." tooltip-placement="right" aria-hidden="true">
				</span>
			</h2>
			<div class="panel panel-default">
				<div class="panel-body">
					<div ng-repeat="item in list" >
						<strong >
							@{{item.view_phone}}
						</strong>
						<span class="small-italic" ng-show="item.source">
							(Added <span ng-show="phone.source !='Manually'">from </span>@{{item.source}})
						</span>
						<div ng-show="phone.phones_firstname != '' || phone.phones_lastname != ''">
							@{{ item.firstname + ' ' + item.lastname}}
						</div>
						<div class="divider divider-md divider-dashed">
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-8 col-sm-12">
			<h2>
				<div class="pull-right">
					<button type="button" class="btn btn-default" ng-click="create()"><i class="fa fa-plus-circle"></i><span class="hidden-xs"> Create New List</span></button>
				</div>
				{{ __('Contact Lists') }}
			</h2>

			<section class="panel panel-default table-dynamic">
				<div class="panel-body">
						<div class="alert-info alert" ng-show=" ! listsList.length" role="alert">
							<div >
								{{ __("You don't have any list yet.") }}<a href="javascript:;" ng-click="create()">{{ __('Create your first list') }}</a> {{ __('right now.') }}
							</div>
						</div>
						<div ng-show="listsList.length">
							<div ng-repeat="item in listsList track by $index">
								<div class="item-panel" ng-class="{'active': selectedList.id == item.id}">
									<div class="action-div list-actions" ng-click="choose($index)">
									</div>
									<div class="row-name">
										<span ng-show=" ! item.editable" >@{{ item.name }}</span>
										<div class="row edit-main-container" ng-show="item.editable">
											<div class="col-sm-12 col-md-8 col-lg-9">
												<input class="form-control" type="text" placeholder="List Name" ng-model="item.name" required="required">
											</div>
											<div class="col-sm-12 col-md-4 col-lg-3">
												<div class="btn-group btn-group-justified">
													<div class="btn-group">
														<button type="button" class="btn btn-default" ng-click="cancel($index, item)">{{ __('Cancel') }}</button>
													</div>
													<div class="btn-group">
														<button type="button" class="btn btn-primary" ng-click="saveList($index)">{{ __('Save') }}</button>
													</div>
												</div>
											</div>
										</div>
										<a href="javascript:;" ng-show="!item.editable" class="a-icon text-success" ng-click="edit($index)"><i class="fa fa-pencil"></i></a>
										<a href="javascript:;" ng-show="!item.editable" class="a-icon text-danger" ng-click="remove(item.id, $index)"><i class="fa fa-trash"></i></a>
										<span ng-show="!item.editable" class="small-italic a-icon">{{ __('Click to see numbers') }}</span>
									</div>

									<div ng-show="selectedList.id == item.id">
										<button type="button" class="btn btn-default" ng-click="createClient($index)"><i class="fa fa-plus-circle"></i>{{ __(' Add Number Manually') }}</button>
										<span class="dropable-phones-outer">
											<button type="button" class="btn btn-default" ng-click="showPhonesBox = ! showPhonesBox"><i class="fa fa-list-ul"></i> {{ __('Choose from Saved Numbers') }}</button>
											<div ng-show="showPhonesBox" class="dropable-phones">
												<div ng-show="list.length">
													<div class="search-group">
														<i class="fa fa-search search-icon" aria-hidden="true"></i>
														<input ng-model="search.$" class="form-control" type="text" placeholder="Search from list...">
													</div>
													<div class="dropable-phones-inner">
														<div ng-repeat="number in list | filter: search">
															<div class="divider divider-xs divider-dashed"></div>
															<a href="javascript:;" class="selecting-phones" ng-class="{'active': number.selected}" ng-click="number.selected = !number.selected">
																<i class="fa fa-circle-o" ng-class="number.selected ? 'fa-check-circle-o' : 'fa-circle-o'"></i>
																<strong class="ng-binding">@{{ number.phone}}</strong> @{{ ' ' + number.firstname + ' ' + number.lastname }}
															</a>

														</div>
													</div>
													<div class="pull-right">
														<div class="btn-group btn-group-justified">
															<div class="btn-group">
																<button type="button" class="btn btn-default" ng-click="showPhonesBox = ! showPhonesBox">{{ __('Cancel') }}</button>
															</div>
															<div class="btn-group">
																<button type="button" class="btn btn-primary" ng-click="saveSelectedPhones($index); showPhonesBox = false">{{ __('Add') }}</button>
															</div>
														</div>
													</div>
												</div>	
												<div uib-alert class="alert-info alert" ng-show="! list.length" role="alert">
												<div>
													{{ __("You haven't any phones yet..") }}
												</div>
											</div>
										</div>
									</span>

									<button type="button" class="btn btn-default" ng-click="openImport()"><i class="fa fa-upload"></i> Import from CSV file</button>
									
									<div ng-show="item.clients.length" >
										<div ng-repeat="(i, client) in item.clients" >
											<div class="item-panel panel-child" ng-class="{'active': client.editable}">
												<div class="row-name">
													<span ng-show=" ! client.editable">
														<i class="phone-icon fa fa-phone"></i> 
														@{{ client.view_phone + ' ' + client.firstname + ' ' + client.lastname }}
													</span>
													<div class="row edit-child-container" ng-show="client.editable">
														<div class="col-sm-12 col-md-8">
															<div class="row">
																<div class="col-sm-12 col-md-6">
																	<div class="form-group search-group">
																		<i class="fa fa-phone search-icon" aria-hidden="true"></i>
																		<input class="form-control" type="text" placeholder="Phone Number" ng-model="client.phone">
																	</div>
																</div>
																<div class="col-sm-12 col-md-6">
																	<div class="form-group search-group">
																		<i class="fa fa-envelope-o search-icon" aria-hidden="true"></i>
																		<input class="form-control" type="text" placeholder="Email" ng-model="client.email">
																	</div>
																</div>
																<div class="col-sm-12 col-md-6">
																	<div class="form-group search-group">
																		<i class="fa fa-user search-icon" aria-hidden="true"></i>
																		<input ng-model="client.firstname" class="form-control" type="text" placeholder="First Name">
																	</div>
																</div>
																<div class="col-sm-12 col-md-6">
																	<div class="form-group search-group">
																		<i class="fa fa-user-o search-icon" aria-hidden="true"></i>
																		<input class="form-control" type="text" placeholder="Last Name" ng-model="client.lastname">
																	</div>
																</div>
															</div>
														</div>
														<div class="col-sm-12 col-md-4">
															<div class="form-group">
																<div class="btn-group btn-group-justified">
																	<div class="btn-group">
																		<button type="button" class="btn btn-default" ng-click="cancelClient(client, $index)">{{ __('Cancel') }}</button>
																	</div>
																	<div class="btn-group">
																		<button type="button" class="btn btn-primary" ng-click="saveClient(i, $index, client)">{{ __('Save') }}</button>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<a href="javascript:;" ng-show="! client.editable" class="a-icon text-success" ng-click="editClient(client)"><i class="fa fa-pencil"></i></a>
													<a href="javascript:;" ng-show="! client.editable" class="a-icon text-danger" ng-click="remove_phone(phone.phones_id, phone.lists_id)"><i class="fa fa-trash"></i></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div ng-show="$index < (listsList.length - 1)" class="divider divider-dashed divider-sm pull-in">
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>

<script type="text/ng-template" id="ImportFile.html">
	<form name="form" method="post" novalidate="novalidate">
		<div class="modal-header">
			<h4 class="modal-title">Import Numbers from CSV file</h4>
		</div>

		<div class="modal-body">
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-sm-3 control-label">CSV File</label>
					<div class="col-sm-9">
						<span class="upload-button-box">
							<button type="button" class="btn btn-sm btn-default"><i class="fa fa-picture-o"></i> Choose File</button>
							<input custom-on-change="upload_csv" type="file" accept=".csv">
						</span>
						<div ng-show="csv.upload_csv != '' || upload_progress" class="upload-name-box">
							<div class="upload-file-name"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="modal-footer">
			<button type="submit" class="btn btn-primary " ng-click="save()" ng-if=" ! view">Import</button>
			<button type="button" class="btn btn-default " ng-click="cancel()" ng-if=" ! view">Cancel</button>
		</div>
	</form>
</script>