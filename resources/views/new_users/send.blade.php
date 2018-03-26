<div class="page page-table" data-ng-controller="NewUsersCtrl" data-ng-init="init()">
	<h2>
		{{ __('New Users') }}
	</h2>

    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
						<div class="col-sm-12" id="new_users_texts">
							<div class="form-group">
								<label>{{ __('Thank You Text') }}</label>
								<div text-area ng-model="texts[0].text"></div>
                            </div>
                            
                            <div class="form-group">
								<label>{{ __('2 Days After Signup Text') }}</label>
								<div text-area ng-model="texts[1].text"></div>
                            </div>
                            
                            <div class="form-group">
								<label>{{ __('4 Days After Signup Text') }}</label>
								<div text-area ng-model="texts[2].text"></div>
							</div>
							
							<div class="form-group">
					    		<button type="button" class="btn btn-primary" ng-click="save()">{{ __('Save') }}</button>
				    		</div>
						</div>
					</div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>{{ __('Send texts to new users') }}</strong>
                </div>

                <div class="panel-body">
                    <div class="form-group">
                        <span class="upload-button-box">
                            <button type="button" class="btn btn-sm btn-default">
                                <i class="fa fa-list"></i> {{ __("Choose File") }}
                            </button>
                            <input ng-disabled="uploading.file > 0" onchange="angular.element(this).scope().uploadFile(event.target.files[0])" multiple="multiple" accept="csv" type="file" />
                        </span>
                    </div>

                    <div class="form-group">
                        <span>@{{ file.name }}</span>
                        <i ng-show="file.url" ng-click="removeFile()" class="fa fa-times pointer" aria-hidden="true"></i>
                        <i ng-show="request" class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-primary" ng-click="send()">{{ __('Send') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>