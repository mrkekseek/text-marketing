<div class="page page-table ng-scope" data-ng-controller="MarketingContactsCtrl" >
	<div class="row">
		<div class="col-md-4 col-sm-12 hidden-xs">
			<h2>
				All Contacts
				<span class="fa fa-question-circle-o help-icon" uib-tooltip="This is where you create Contact Lists to send texts to. You can enter Contacts manually one at a time or upload a CSV. The first row of the CSV needs to be the 'First Name', the second row needs to be the 'Last Name' and the third row needs to be their Phone Number." tooltip-placement="right" aria-hidden="true">
				</span>
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
						<div class="content-loader ng-hide" ng-show="false">
							<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
						</div>
						<div class="alert-info ng-isolate-scope alert ng-hide" ng-show=" ! contactList.length && requestFinish" role="alert">
							<div >
								You don't have any list yet. <a href="javascript:;" ng-click="create()" class="ng-scope">Create your first list</a> right now.
							</div>
						</div>

						<div ng-show="contactList.length">
							<div ng-repeat="item in contactList" ng-init="itemIndex = $index" class="ng-scope">
								<div class="item-panel" ng-class="{'active': selected == $index}">
									<div class="action-div list-actions" ng-click="choose($index)">
									</div>
									
									<div class="row-name">
										<span ng-show="!item.editable" class="ng-binding">@{{ item.listName }}</span>
										<div class="row edit-main-container" ng-show="item.editable">
											<div class="col-sm-12 col-md-8 col-lg-9">
												<input class="form-control ng-pristine ng-untouched ng-valid ng-not-empty" type="text" placeholder="List Name" ng-model="item.listName" autofocus="autofocus">
											</div>
											<div class="col-sm-12 col-md-4 col-lg-3">
												<div class="btn-group btn-group-justified">
													<div class="btn-group">
														<button type="button" class="btn btn-default" ng-click="cancel($index)">Cancel</button>
													</div>
													<div class="btn-group">
														<button type="button" class="btn btn-primary" ng-click="save(item)">Save</button>
													</div>
												</div>
											</div>
										</div>
										<a href="javascript:;" ng-show="!item.editable" class="a-icon text-success" ng-click="edit($index)"><i class="fa fa-pencil"></i></a>
										<a href="javascript:;" ng-show="!item.editable" class="a-icon text-danger" ng-click="remove($index)"><i class="fa fa-trash"></i></a>
										<span ng-show="!item.editable" class="small-italic a-icon" style="">Click to see numbers</span>
									</div>

									<div ng-show="selected == $index">
										<button type="button" class="btn btn-default child-button" ng-click="create_phone($index)"><i class="fa fa-plus-circle"></i> Add Number Manually</button>
										<span class="dropable-phones-outer">
											<button type="button" class="btn btn-default child-button" ng-click="open_numbers_box();"><i class="fa fa-list-ul"></i> Choose from Saved Numbers</button>
										</span>
										<button type="button" class="btn btn-default child-button" ng-click="open_import($index)"><i class="fa fa-upload"></i> Import from CSV file</button>
										<div ng-show="item.phones.length > 0" class="ng-scope">
											<div ng-repeat="phone in item.phones" >
												<div class="item-panel panel-child" ng-class="{'active': phone.editable}">
													<div class="row-name">
														<span ng-show="!phone.editable" class="ng-binding" style=""><i class="phone-icon fa fa-phone"></i> 
															@{{ phone.number + ' ' + phone.firstName + ' ' + phone.lastName + ' ' + phone.editable}}
														</span>
														<div class="row edit-child-container" ng-show="phone.editable">
															<div class="col-sm-12 col-md-8">
																<div class="row">
																	<div class="col-sm-12 col-md-6">
																		<div class="form-group search-group">
																			<i class="fa fa-phone search-icon" aria-hidden="true"></i>
																			<input class="form-control ng-pristine ng-untouched ng-valid ng-not-empty" type="text" placeholder="Phone Number" ng-model="phone.number" focus-me="phone.editable">
																		</div>
																	</div>
																	<div class="col-sm-12 col-md-6">
																		<div class="form-group search-group">
																			<i class="fa fa-birthday-cake search-icon" aria-hidden="true"></i>
																			<div class="input-group">
																				<input type="text" class="form-control ng-pristine ng-untouched ng-valid ng-isolate-scope ng-not-empty ng-valid-date" uib-datepicker-popup="yyyy-MM-dd" ng-model="phone.birthDay" is-open="opened" datepicker-append-to-body="true" close-text="Close">
																				<span class="input-group-btn">
																					<button type="button" class="btn btn-default" ng-click="opened = true"><i class="glyphicon glyphicon-calendar"></i></button>
																				</span>
																			</div>
																		</div>
																	</div>
																	<div class="col-sm-12 col-md-6">
																		<div class="form-group search-group">
																			<i class="fa fa-user search-icon" aria-hidden="true"></i>
																			<input ng-model="phone.firstName" class="form-control ng-pristine ng-untouched ng-valid ng-not-empty" type="text" placeholder="First Name">
																		</div>
																	</div>
																	<div class="col-sm-12 col-md-6">
																		<div class="form-group search-group">
																			<i class="fa fa-user-o search-icon" aria-hidden="true"></i>
																			<input class="form-control ng-pristine ng-untouched ng-valid ng-not-empty" type="text" placeholder="Last Name" ng-model="phone.lastName">
																		</div>
																	</div>
																</div>  <!-- phone-info -->
															</div>
															<div class="col-sm-12 col-md-4">
																<div class="form-group">
																	<div class="btn-group btn-group-justified">
																		<div class="btn-group">
																			<button type="button" class="btn btn-default" ng-click="cancelPhone(itemIndex, $index)">Cancel</button>
																		</div>
																		<div class="btn-group">
																			<button type="button" class="btn btn-primary" ng-click="save_phone(phone)">Save</button>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<a href="javascript:;" ng-show="! phone.editable" class="a-icon text-success" ng-click="edit_phone(itemIndex, $index)"><i class="fa fa-pencil"></i></a>
														<a href="javascript:;" ng-show="! phone.editable" class="a-icon text-danger" ng-click="remove_phone(phone.phones_id, phone.lists_id)"><i class="fa fa-trash"></i></a>
													</div>
													
												</div>
											</div>
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