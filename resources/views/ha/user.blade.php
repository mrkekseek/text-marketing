<div class="page page-table" data-ng-controller="HomeAdvisorCtrl" ng-init="init()">
	<h2>
		{{ __('HomeAdvisor') }}	
		<i class="fa fa-question-circle-o help-icon" uib-tooltip="To get started, click the 'Activate HomeAdvisor' button. We will then speak to your HomeAdvisor rep to get you connected and we will alert you when we are done. Then you can customize the text you want your leads to receive. We recommend putting in the link to your booking site and your number and letting them know that they can reply by text as well. This gives the lead 3 ways to engage. On the right side of the page you will see a list of all of your leads. Leads who click the link will have a green check next to their name, while a blue check signifies that they texted a reply." tooltip-placement="right-top" aria-hidden="true"></i>
	</h2>

	<div class="row">
		<div class="col-sm-12 col-md-6">
			<div class="panel panel-default">
				<div class="panel-body">
					<div uib-alert class="alert-info" ng-show=" ! ha.send_request">
						{{ __("To get started, click the 'Activate HomeAdvisor' button. We will then speak to your HomeAdvisor rep to get you connected and we will alert you when we are done. Then you can customize the text you want your leads to receive. We recommend putting in the link to your booking site and your number and letting them know that they can reply by text as well. This gives the lead 3 ways to engage. On the right side of the page you will see a list of all of your leads. Leads who click the link will have a green check next to their name, while a blue check signifies that they texted a reply.") }}
					</div>

					<div uib-alert class="alert-info" ng-show="ha.send_request && ! list.length">
						{{ __("Your request was sent to HomeAdvisor. We will inform you when it will be processed") }}
					</div>

					<div uib-alert class="alert-success" ng-show="list.length">
						{{ __('Your request was approved by HomeAdvisor') }}
					</div>

					<button type="button" class="btn btn-primary" ng-show=" ! ha.send_request" ng-click="activate()">{{ __('Activate HomeAdvisor') }}</button>
					
					<div ng-show="ha.send_request">
						<div class="form-group">
							<label class="ui-switch ui-switch-success ui-switch-sm pull-right">
								<input id="enable" type="checkbox" ng-model="ha.active" ng-click="enable()" ng-true-value="1" ng-false-value="0" />
								<i></i>
							</label>

							<label for="enable">
								{{ __('Enable text to Lead') }}
							</label>
						</div>

						<div class="form-group">
							<label>{{ __('Company Name') }}</label>
							<i class="fa fa-question-circle-o" uib-tooltip-template="'companyTooltip.html'" tooltip-placement="right" aria-hidden="true"></i>
							<div class="input-group">
								<input type="text" class="form-control" maxlength="32" ng-model="user.company_name" ng-change="companyChange()" placeholder="{{ __('Company Name') }}" />
								<span class="input-group-addon bg-success" ng-show="user.company_status == 'verified' && ! companyChanged">{{ __('Verified') }}</span>
								<span class="input-group-addon bg-warning" ng-show="user.company_status == 'pending' && ! companyChanged">{{ __('Pending') }}</span>
								<span class="input-group-addon bg-danger" ng-show="user.company_status == 'denied' && ! companyChanged">{{ __('Denied') }}</span>
								<span class="input-group-btn" ng-show="user.company_status == '' || companyChanged">
									<button class="btn btn-default" ng-click="companySave()">{{ __('Save') }}</button>
								</span>
							</div>
						</div>

						<div uib-alert class="alert-info" ng-show="user.company_status != 'verified' || companyChanged">
							{{ __('To send texts you should save Company Name and wait untill it will be verified. It may takes 15 minutes') }}
						</div>

						<form name="form_ha" novalidate="novalidate" ng-show="user.company_status == 'verified' && ! companyChanged">
							<div class="form-group">
								<label>{{ __('Instant Text') }}</label>
								<char-set ng-model="ha.text" unique-id="'ha'" max-firstname="maxChars('firstname')" max-lastname="maxChars('lastname')" company="user.company_name" btn-firstname="true" btn-lastname="true" btn-hapage="true" btn-shortlink="true" lms="true"></char-set>
							</div>

							<div class="form-group">
								<label>{{ __('Follow-Up Text') }}</label>
								<i class="fa fa-question-circle-o" uib-tooltip="Follow-Up Text goes out an hour after the Instant Text, if the Lead does not click on your link or does not text you back." tooltip-placement="right" aria-hidden="true"></i>
								<input class="form-control" type="text" disabled="disabled" ng-value="getFollowUpText()" />
							</div>

							<label>Upload Logo</label>
							<div class="form-group">
								<span class="upload-button-box">
									<button type="button" class="btn btn-sm btn-default">
										<i class="fa fa-picture-o"></i> {{ __("Choose File") }}
									</button>
									<input onchange="angular.element(this).scope().uploadFile(event.target.files[0])" accept="image/jpeg,image/png,image/gif,image/bmp,video/avi,video/mp4,video/quicktime,video/x-ms-wmv" type="file" />
								</span>

								<span class="upload-tooltip" uib-tooltip="{{ __('Image size limit is 500 KB; supported image file types include .JPG, .PNG, .GIF (non-animated), .BMP Video size limit is 3 MB; supported video file types include .AVI, .MP4, .WMV, and .MOV') }}">
									<i class="fa fa-question-circle"></i> {{ __('Upload details') }}
								</span>
							</div>

							<div class="form-group">
								<img ng-show="file.url" src="@{{ file.url }}" class="preview-mms" />
								<i ng-show="file.url" ng-click="removeMMS()" class="fa fa-times mms-remove" aria-hidden="true"></i>
								<i ng-show="request" class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
							</div>

							<div class="row">
								<div class="col-sm-6 col-xs-12">
									<div class="form-group">
										<label>{{ __('My number for alerts') }}</label>
										<i class="fa fa-question-circle-o" uib-tooltip="If the Lead clicks your link or texts you back, the platform will send a text to this number alerting you." tooltip-placement="right" aria-hidden="true"></i>
										<input type="text" name="phone" class="form-control" ng-model="user.view_phone" required="required" />
									</div>

									<label>{{ __('Additional phones') }}</label>
									<span class="fa fa-question-circle-o" uib-tooltip="You can add additional numbers that will be alerted." tooltip-placement="right"></span>
									<div class="form-group" ng-repeat="input in inputs track by $index">
										<div class="input-group">
											<input type="text" name="phone_@{{$index}}" class="form-control" ng-model="inputs[$index]" placeholder="{{ __('Enter phone here...') }}" />
											<span class="input-group-btn" ng-show="$index == inputs.length - 1">
												<button class="btn btn-default" type="button" ng-click="add();">
													<i class="fa fa-plus" aria-hidden="true"></i>
												</button>
											</span>

											<span class="input-group-btn" ng-show="$index < inputs.length - 1">
												<button class="btn btn-default" type="button" ng-click="remove($index);">
													<i class="fa fa-minus" aria-hidden="true"></i>
												</button>
											</span>
										</div>
									</div>
								</div>

								<div class="col-sm-6 col-xs-12">
									<label>{{ __('Emails for alerts') }}</label>
									<div class="form-group" ng-repeat="email in emails track by $index">
										<div class="input-group">
											<input type="text" name="email_@{{$index}}" class="form-control" ng-model="emails[$index]" placeholder="{{ __('Enter email here...') }}" />
											<span class="input-group-btn" ng-show="$index == emails.length - 1">
												<button class="btn btn-default" type="button" ng-click="emailsAdd();">
													<i class="fa fa-plus" aria-hidden="true"></i>
												</button>
											</span>

											<span class="input-group-btn" ng-show="$index < emails.length - 1">
												<button class="btn btn-default" type="button" ng-click="emailsRemove($index);">
													<i class="fa fa-minus" aria-hidden="true"></i>
												</button>
											</span>
										</div>
									</div>
								</div>
							</div>

							<label>{{ __('Upload Job Pics') }}</label>
							<i class="fa fa-question-circle-o" uib-tooltip="Here you can upload up to 5 pictures of jobs you've done. A link will then be added to the text which the Lead can click to see the pics." tooltip-placement="right" aria-hidden="true"></i>
							<div class="form-group">
								<span class="upload-button-box">
									<button type="button" class="btn btn-sm btn-default">
										<i class="fa fa-picture-o"></i> {{ __("Choose File") }}
									</button>
									<input ng-disabled="uploading.pictures > 0" onchange="angular.element(this).scope().uploadPictures(event.target.files)" multiple="multiple" accept="image/jpeg,image/png,image/gif,image/bmp" type="file" />
								</span>

								<span ng-show="uploading.pictures == 0" class="upload-tooltip" uib-tooltip="{{ __('You can upload up to 5 images. Image size limit is 2 MB; supported image file types include .JPG, .PNG, .GIF (non-animated), .BMP') }}">
									<i class="fa fa-question-circle"></i> {{ __('Upload details') }}
								</span>

								<span ng-show="uploading.pictures > 0" class="upload-tooltip">
									<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Uploading @{{ uploading.pictures }} file@{{ uploading.pictures == 1 ? '' : 's' }}. Please, wait...
								</span>

								&nbsp;&nbsp;&nbsp;&nbsp;

								<a href="/ha-job/@{{ user.id }}" target="_blank" class="btn btn-default btn-sm">
									<i class="fa fa-image"></i>
									See Your Pictures
								</a>
							</div>

							<div class="form-group">
								<div class="images-preview" ng-repeat="picture in pictures">
									<img src="@{{ picture.url }}" alt="" />
									<div class="removeIcon" ng-click="removePicture($index)" >
										<i class="fa fa-times" aria-hidden="true"></i>
									</div>
								</div>
							</div>

							<hr />

							<div class="form-group">
								<button class="btn btn-primary" type="submit" ng-click="save()">{{ __('Save') }}</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-12 col-md-6">
			<?php
				/*$leads = [
					[
						'code' => '15dZhxUL1w7ok',
						'user_id' => 6,
						'team_id' => 9,
						'firstname' => 'Victoria',
						'lastname' => 'Afram',
						'view_phone' => '4434721519',
						'phone' => '4434721519',
						'emails' => 'victoria.afram24@gmail.com',
						'created_at' => '2018-02-16 03:33:54',
					], [
						'code' => '15yENchpOxbho',
						'user_id' => 71,
						'team_id' => 66,
						'firstname' => 'Amor',
						'lastname' => 'Kamimura',
						'view_phone' => '2403303043',
						'phone' => '2403303043',
						'emails' => 'reignalyn@yahoo.com',
						'created_at' => '2018-02-16 03:18:46',
					], [
						'code' => '151ZKYXmIaMjU',
						'user_id' => 26,
						'team_id' => 32,
						'firstname' => 'Kyra',
						'lastname' => 'Williams',
						'view_phone' => '3016749209',
						'phone' => '3016749209',
						'emails' => 'kyrasimms@aol.com',
						'created_at' => '2018-02-16 01:03:57',
					], [
						'code' => '15lR0Wlosavys',
						'user_id' => 28,
						'team_id' => 34,
						'firstname' => 'Tami',
						'lastname' => 'Ban',
						'view_phone' => '5714422793',
						'phone' => '5714422793',
						'emails' => 'happeetooth@gmail.com',
						'created_at' => '2018-02-16 00:55:18',
					], [
						'code' => '15SomGKNZUDGo',
						'user_id' => 62,
						'team_id' => 59,
						'firstname' => 'Sara',
						'lastname' => 'Soleimani',
						'view_phone' => '9495054697',
						'phone' => '9495054697',
						'emails' => 'sar.soleimani@gmail.com',
						'created_at' => '2018-02-15 22:36:03',
					], [
						'code' => '151ZKYXmIaMjU',
						'user_id' => 26,
						'team_id' => 32,
						'firstname' => 'Jaclyn',
						'lastname' => 'Howell',
						'view_phone' => '4439981144',
						'phone' => '4439981144',
						'emails' => 'jac.howell87@gmail.com',
						'created_at' => '2018-02-15 22:28:45',
					], [
						'code' => '15JUiL_reC29o',
						'user_id' => 4,
						'team_id' => 5,
						'firstname' => 'Milen',
						'lastname' => 'Collazo',
						'view_phone' => '8138842451',
						'phone' => '8138842451',
						'emails' => 'milic2006@gmail.com',
						'created_at' => '2018-02-15 22:25:53',
					], [
						'code' => '1556ndcNBONR6',
						'user_id' => 100,
						'team_id' => 86,
						'firstname' => 'Jarred',
						'lastname' => 'Holloway',
						'view_phone' => '2147040633',
						'phone' => '2147040633',
						'emails' => 'jarredholloway@gmail.com',
						'created_at' => '2018-02-15 22:19:44',
					], [
						'code' => '1597G_kCc_bPU',
						'user_id' => 42,
						'team_id' => 49,
						'firstname' => 'Julie',
						'lastname' => 'Georgeski',
						'view_phone' => '8587660164',
						'phone' => '8587660164',
						'emails' => 'jgeorgeski@yahoo.com',
						'created_at' => '2018-02-15 20:52:02',
					], [
						'code' => '15lR0Wlosavys',
						'user_id' => 28,
						'team_id' => 34,
						'firstname' => 'Carla',
						'lastname' => 'Williams',
						'view_phone' => '7035853130',
						'phone' => '7035853130',
						'emails' => 'carlawilliams@remax.net',
						'created_at' => '2018-02-15 20:41:50',
					], [
						'code' => '1587bk84Xmm4g',
						'user_id' => 77,
						'team_id' => 72,
						'firstname' => 'Amy',
						'lastname' => 'Phillips',
						'view_phone' => '6108881468',
						'phone' => '6108881468',
						'emails' => 'shrtblondie1@gmail.com',
						'created_at' => '2018-02-15 20:32:08',
					], [
						'code' => '15Go71R3STntU',
						'user_id' => 32,
						'team_id' => 39,
						'firstname' => 'Cynthia',
						'lastname' => 'Smith',
						'view_phone' => '7033147893',
						'phone' => '7033147893',
						'emails' => 'gsmith4841@att.net',
						'created_at' => '2018-02-15 20:25:29',
					], [
						'code' => '15_NsUfR_JbWQ',
						'user_id' => 104,
						'team_id' => 90,
						'firstname' => 'Emmanuel',
						'lastname' => 'Cobian',
						'view_phone' => '2018382044',
						'phone' => '2018382044',
						'emails' => 'cobian11@aol.com',
						'created_at' => '2018-02-15 20:24:49',
					], [
						'code' => '15oIQvUgLW4Tg',
						'user_id' => 108,
						'team_id' => 93,
						'firstname' => 'Lee Ann',
						'lastname' => 'Barronton',
						'view_phone' => '6787791315',
						'phone' => '6787791315',
						'emails' => 'ken.leeann@outlook.com',
						'created_at' => '2018-02-15 19:01:57',
					], [
						'code' => '15exkUncw957U',
						'user_id' => 75,
						'team_id' => 70,
						'firstname' => 'Ernie',
						'lastname' => 'Ruby',
						'view_phone' => '8474217860',
						'phone' => '8474217860',
						'emails' => 'eruby@sbcglobal.net',
						'created_at' => '2018-02-15 18:50:31',
					], [
						'code' => '15oIQvUgLW4Tg',
						'user_id' => 108,
						'team_id' => 93,
						'firstname' => 'Carol',
						'lastname' => 'Gorgonne',
						'view_phone' => '4043545922',
						'phone' => '4043545922',
						'emails' => 'cgorgonne@gmail.com',
						'created_at' => '2018-02-15 18:48:28',
					], [
						'code' => '15SomGKNZUDGo',
						'user_id' => 62,
						'team_id' => 59,
						'firstname' => 'Synthia',
						'lastname' => 'Molina',
						'view_phone' => '8134048442',
						'phone' => '8134048442',
						'emails' => 'synthia.molina@centraliq.com',
						'created_at' => '2018-02-15 18:35:31',
					], [
						'code' => '15rWaYgIfU7EM',
						'user_id' => 105,
						'team_id' => 91,
						'firstname' => 'Ann',
						'lastname' => 'Hughes',
						'view_phone' => '8162941518',
						'phone' => '8162941518',
						'emails' => 'nwmo132@gmail.com',
						'created_at' => '2018-02-15 18:19:02',
					], [
						'code' => '1556ndcNBONR6',
						'user_id' => 100,
						'team_id' => 86,
						'firstname' => 'herbert',
						'lastname' => 'maduro',
						'view_phone' => '7135022859',
						'phone' => '7135022859',
						'emails' => 'amkoservices@comcast.net',
						'created_at' => '2018-02-15 18:17:27',
					], [
						'code' => '15Go71R3STntU',
						'user_id' => 32,
						'team_id' => 39,
						'firstname' => 'Charles',
						'lastname' => 'Smith',
						'view_phone' => '2105879040',
						'phone' => '2105879040',
						'emails' => 'chasmit56@hotmail.com',
						'created_at' => '2018-02-15 17:51:19',
					], [
						'code' => '15SomGKNZUDGo',
						'user_id' => 62,
						'team_id' => 59,
						'firstname' => 'Brigitte',
						'lastname' => 'Fisk',
						'view_phone' => '9492953555',
						'phone' => '9492953555',
						'emails' => 'seniorcaremanager@yahoo.com',
						'created_at' => '2018-02-15 17:50:34',
					], [
						'code' => '15hfSC2zGWJ4M',
						'user_id' => 106,
						'team_id' => 92,
						'firstname' => 'Rhett',
						'lastname' => 'Taylor',
						'view_phone' => '4694184382',
						'phone' => '4694184382',
						'emails' => 'aviationeverything@gmail.com',
						'created_at' => '2018-02-15 17:49:08',
					], [
						'code' => '15FP1W1B2aIGY',
						'user_id' => 101,
						'team_id' => 87,
						'firstname' => 'Michael',
						'lastname' => 'Kennedy',
						'view_phone' => '3215056126',
						'phone' => '3215056126',
						'emails' => 'merrittislandmike@mac.com',
						'created_at' => '2018-02-15 17:34:24',
					], [
						'code' => '15_gA3RuPElqo',
						'user_id' => 70,
						'team_id' => 65,
						'firstname' => 'Richard',
						'lastname' => 'Jones',
						'view_phone' => '6789836842',
						'phone' => '6789836842',
						'emails' => 'richard.jones@jfbc.org',
						'created_at' => '2018-02-15 17:13:46',
					], [
						'code' => '15YQ_3S1PR2HE',
						'user_id' => 91,
						'team_id' => 81,
						'firstname' => 'James',
						'lastname' => 'Perry',
						'view_phone' => '7577784965',
						'phone' => '7577784965',
						'emails' => 'jp38army@gmail.com',
						'created_at' => '2018-02-15 17:01:17',
					], [
						'code' => '156l_DyqML0j6',
						'user_id' => 117,
						'team_id' => 102,
						'firstname' => 'James',
						'lastname' => 'Ackerman',
						'view_phone' => '3212311462',
						'phone' => '3212311462',
						'emails' => 'jim@jimackerman.net',
						'created_at' => '2018-02-15 16:58:37',
					], [
						'code' => '156l_DyqML0j6',
						'user_id' => 117,
						'team_id' => 102,
						'firstname' => 'Michele',
						'lastname' => 'Bozzacco',
						'view_phone' => '4074011030',
						'phone' => '4074011030',
						'emails' => 'bozzacco52@aol.com',
						'created_at' => '2018-02-15 16:57:56',
					], [
						'code' => '15mEtjIhtJdP6',
						'user_id' => 34,
						'team_id' => 41,
						'firstname' => 'citron',
						'lastname' => 'dassi',
						'view_phone' => '9147148349',
						'phone' => '9147148349',
						'emails' => 'dassicitron@gmail.com',
						'created_at' => '2018-02-15 16:57:22',
					], [
						'code' => '15JUiL_reC29o',
						'user_id' => 4,
						'team_id' => 5,
						'firstname' => 'Cynthia',
						'lastname' => 'Gardner',
						'view_phone' => '8139748335',
						'phone' => '8139748335',
						'emails' => 'rouse@usf.edu',
						'created_at' => '2018-02-15 16:48:08',
					], [
						'code' => '15_gA3RuPElqo',
						'user_id' => 70,
						'team_id' => 65,
						'firstname' => 'Ron',
						'lastname' => 'Ahlstedt',
						'view_phone' => '4046805042',
						'phone' => '4046805042',
						'emails' => 'ronahls@gmail.com',
						'created_at' => '2018-02-15 16:40:16',
					], [
						'code' => '15oIQvUgLW4Tg',
						'user_id' => 108,
						'team_id' => 93,
						'firstname' => 'Eileen',
						'lastname' => 'Rarick',
						'view_phone' => '7708468555',
						'phone' => '7708468555',
						'emails' => 'emrarick@gmail.com',
						'created_at' => '2018-02-15 16:34:15',
					], [
						'code' => '15oIQvUgLW4Tg',
						'user_id' => 108,
						'team_id' => 93,
						'firstname' => 'Carrie',
						'lastname' => 'Harris',
						'view_phone' => '4047252695',
						'phone' => '4047252695',
						'emails' => 'spindogrick@outlook.com',
						'created_at' => '2018-02-15 16:26:54',
					], [
						'code' => '1597G_kCc_bPU',
						'user_id' => 42,
						'team_id' => 49,
						'firstname' => 'BART',
						'lastname' => 'DOCTOR',
						'view_phone' => '7603249086',
						'phone' => '7603249086',
						'emails' => 'bdoctor@dc.rr.com',
						'created_at' => '2018-02-15 16:14:38',
					], [
						'code' => '1556ndcNBONR6',
						'user_id' => 100,
						'team_id' => 86,
						'firstname' => 'Bernice',
						'lastname' => 'White',
						'view_phone' => '2817488602',
						'phone' => '2817488602',
						'emails' => 'loveye1another@wt.net',
						'created_at' => '2018-02-15 15:46:40',
					], [
						'code' => '156QYv592n84E',
						'user_id' => 20,
						'team_id' => 25,
						'firstname' => 'Alexander',
						'lastname' => 'Herd',
						'view_phone' => '7048434224',
						'phone' => '7048434224',
						'emails' => 'sharrismurphy@yahoo.com',
						'created_at' => '2018-02-15 15:09:16',
					], [
						'code' => '15exkUncw957U',
						'user_id' => 75,
						'team_id' => 70,
						'firstname' => 'Tim',
						'lastname' => 'Schuller',
						'view_phone' => '4149160423',
						'phone' => '4149160423',
						'emails' => 'tkschuller@gmail.com',
						'created_at' => '2018-02-15 14:41:00',
					], [
						'code' => '151ZKYXmIaMjU',
						'user_id' => 26,
						'team_id' => 32,
						'firstname' => 'George',
						'lastname' => 'Skypeck',
						'view_phone' => '3016339375',
						'phone' => '3016339375',
						'emails' => 'georgeskypeck@verizon.net',
						'created_at' => '2018-02-15 14:36:22',
					], [
						'code' => '1556ndcNBONR6',
						'user_id' => 100,
						'team_id' => 86,
						'firstname' => 'REBECCA',
						'lastname' => 'HART',
						'view_phone' => '8324146010',
						'phone' => '8324146010',
						'emails' => 'rxhart@icloud.com',
						'created_at' => '2018-02-15 14:27:59',
					], [
						'code' => '15YQ_3S1PR2HE',
						'user_id' => 91,
						'team_id' => 81,
						'firstname' => 'Jimmie',
						'lastname' => 'Cleveland',
						'view_phone' => '4047071691',
						'phone' => '4047071691',
						'emails' => 'james231552@gmail.com',
						'created_at' => '2018-02-15 14:25:44',
					], [
						'code' => '15rWaYgIfU7EM',
						'user_id' => 105,
						'team_id' => 91,
						'firstname' => 'Gary',
						'lastname' => 'Dvorak',
						'view_phone' => '5154026599',
						'phone' => '5154026599',
						'emails' => 'dvorak.gary@principal.com',
						'created_at' => '2018-02-15 14:21:19',
					], [
						'code' => '156l_DyqML0j6',
						'user_id' => 117,
						'team_id' => 102,
						'firstname' => 'Kimberly',
						'lastname' => 'Black',
						'view_phone' => '9084431272',
						'phone' => '9084431272',
						'emails' => 'kimberly.a.black@gmail.com',
						'created_at' => '2018-02-15 14:18:22',
					], [
						'code' => '15SomGKNZUDGo',
						'user_id' => 62,
						'team_id' => 59,
						'firstname' => 'Judy',
						'lastname' => 'Carter',
						'view_phone' => '9492352029',
						'phone' => '9492352029',
						'emails' => 'judyjcarter@gmail.com',
						'created_at' => '2018-02-15 14:16:50',
					], [
						'code' => '153SedjXr8Sow',
						'user_id' => 116,
						'team_id' => 101,
						'firstname' => 'beth',
						'lastname' => 'cooper',
						'view_phone' => '4783610436',
						'phone' => '4783610436',
						'emails' => 'wright.beth@navicenthealth.org',
						'created_at' => '2018-02-15 14:15:08',
					], [
						'code' => '1556ndcNBONR6',
						'user_id' => 100,
						'team_id' => 86,
						'firstname' => 'Kenneth',
						'lastname' => 'Carmouth',
						'view_phone' => '7132015584',
						'phone' => '7132015584',
						'emails' => 'kwmouche@aol.com',
						'created_at' => '2018-02-15 14:14:47',
					], [
						'code' => '151ZKYXmIaMjU',
						'user_id' => 26,
						'team_id' => 32,
						'firstname' => 'Carl',
						'lastname' => 'Cousins',
						'view_phone' => '4105536515',
						'phone' => '4105536515',
						'emails' => 'contactmindysjanitorial@yahoo.com',
						'created_at' => '2018-02-15 14:13:04',
					], [
						'code' => '15Ir2BbsBOutw',
						'user_id' => 115,
						'team_id' => 100,
						'firstname' => 'David',
						'lastname' => 'Louk',
						'view_phone' => '4799271743',
						'phone' => '4799271743',
						'emails' => 'davidlouk@cs.homeadvisor.com',
						'created_at' => '2018-02-15 14:11:32',
					], [
						'code' => '15oIQvUgLW4Tg',
						'user_id' => 108,
						'team_id' => 93,
						'firstname' => 'Vicki',
						'lastname' => 'Rodriguez',
						'view_phone' => '4042187333',
						'phone' => '4042187333',
						'emails' => 'vrod1107@bellsouth.net',
						'created_at' => '2018-02-15 14:10:07',
					], [
						'code' => '151ZKYXmIaMjU',
						'user_id' => 26,
						'team_id' => 32,
						'firstname' => 'Donna',
						'lastname' => 'Webb',
						'view_phone' => '2405058289',
						'phone' => '2405058289',
						'emails' => 'donnabartlettdobbin@gmail.com',
						'created_at' => '2018-02-15 14:09:28',
					], [
						'code' => '15oIQvUgLW4Tg',
						'user_id' => 108,
						'team_id' => 93,
						'firstname' => 'Alex',
						'lastname' => 'Rusanov',
						'view_phone' => '7702968756',
						'phone' => '7702968756',
						'emails' => 'playtennis87@gmail.com',
						'created_at' => '2018-02-15 14:09:06',
					], [
						'code' => '15ClkS6Kshzy2',
						'user_id' => 93,
						'team_id' => 83,
						'firstname' => 'Dan',
						'lastname' => 'welch',
						'view_phone' => '6512618212',
						'phone' => '6512618212',
						'emails' => 'dwelch23@yahoo.com',
						'created_at' => '2018-02-15 14:06:07',
					], [
						'code' => '15oIQvUgLW4Tg',
						'user_id' => 108,
						'team_id' => 93,
						'firstname' => 'Tracy',
						'lastname' => 'Hoffman',
						'view_phone' => '7706633896',
						'phone' => '7706633896',
						'emails' => 'tracyfliph@aol.com',
						'created_at' => '2018-02-15 14:03:37',
					], [
						'code' => '15FP1W1B2aIGY',
						'user_id' => 101,
						'team_id' => 87,
						'firstname' => 'Kevin',
						'lastname' => 'Kearns',
						'view_phone' => '3219175247',
						'phone' => '3219175247',
						'emails' => 'kkearns1@cfl.rr.com',
						'created_at' => '2018-02-15 13:48:32',
					], [
						'code' => '15FP1W1B2aIGY',
						'user_id' => 101,
						'team_id' => 87,
						'firstname' => 'Kevin',
						'lastname' => 'Kearns',
						'view_phone' => '3219175247',
						'phone' => '3219175247',
						'emails' => 'kkearns1@cfl.rr.com',
						'created_at' => '2018-02-15 13:48:32',
					], [
						'code' => '15exkUncw957U',
						'user_id' => 75,
						'team_id' => 70,
						'firstname' => 'Michael',
						'lastname' => 'Bennett',
						'view_phone' => '3122863859',
						'phone' => '3122863859',
						'emails' => 'mikebennett94@gmail.com',
						'created_at' => '2018-02-15 13:25:05',
					], [
						'code' => '15JnebD4ojwoE',
						'user_id' => 3,
						'team_id' => 4,
						'firstname' => 'Lin',
						'lastname' => 'Mitchell',
						'view_phone' => '9198127074',
						'phone' => '9198127074',
						'emails' => 'lin.mitchell@mindspring.com',
						'created_at' => '2018-02-15 13:21:09',
					], [
						'code' => '15yENchpOxbho',
						'user_id' => 71,
						'team_id' => 66,
						'firstname' => 'Andrea',
						'lastname' => 'Negrete',
						'view_phone' => '7757810647',
						'phone' => '7757810647',
						'emails' => 'anegrete628@yahoo.com',
						'created_at' => '2018-02-15 13:05:34',
					], [
						'code' => '15JUiL_reC29o',
						'user_id' => 4,
						'team_id' => 5,
						'firstname' => 'Debbie',
						'lastname' => 'Gaston',
						'view_phone' => '8132100952',
						'phone' => '8132100952',
						'emails' => 'dgaston1@tampabay.rr.com',
						'created_at' => '2018-02-15 13:02:09',
					], [
						'code' => '15ClkS6Kshzy2',
						'user_id' => 93,
						'team_id' => 83,
						'firstname' => 'Nobody',
						'lastname' => 'Fake',
						'view_phone' => '6122233333',
						'phone' => '6122233333',
						'emails' => 'z3205517@mvrht.net',
						'created_at' => '2018-02-15 12:58:33',
					], [
						'code' => '15KyVBaO4PrhA',
						'user_id' => 64,
						'team_id' => 61,
						'firstname' => 'Ben',
						'lastname' => 'Flaim',
						'view_phone' => '3039813326',
						'phone' => '3039813326',
						'emails' => 'baflaim@gmail.com',
						'created_at' => '2018-02-15 12:58:01',
					], [
						'code' => '15ClkS6Kshzy2',
						'user_id' => 93,
						'team_id' => 83,
						'firstname' => 'Katharine',
						'lastname' => 'Carroll',
						'view_phone' => '6514297757',
						'phone' => '6514297757',
						'emails' => 'kcarroll4irish@q.com',
						'created_at' => '2018-02-15 12:55:39',
					], [
						'code' => '1597G_kCc_bPU',
						'user_id' => 42,
						'team_id' => 49,
						'firstname' => 'Jesse',
						'lastname' => 'Bagby',
						'view_phone' => '6302291300',
						'phone' => '6302291300',
						'emails' => 'jbgb02@gmail.com',
						'created_at' => '2018-02-15 12:39:33',
					], [
						'code' => '151ZKYXmIaMjU',
						'user_id' => 26,
						'team_id' => 32,
						'firstname' => 'Ron',
						'lastname' => 'Raborg',
						'view_phone' => '4108126866',
						'phone' => '4108126866',
						'emails' => 'rtraborg02@gmail.com',
						'created_at' => '2018-02-15 12:13:20',
					], [
						'code' => '15zIFfPvFwdbo',
						'user_id' => 41,
						'team_id' => 48,
						'firstname' => 'Kelsey',
						'lastname' => 'Pickens',
						'view_phone' => '2147975109',
						'phone' => '2147975109',
						'emails' => 'pickens.kelsey@yahoo.com',
						'created_at' => '2018-02-15 12:10:25',
					], [
						'code' => '15N_iPfyjK_bM',
						'user_id' => 38,
						'team_id' => 45,
						'firstname' => 'Paul',
						'lastname' => 'Welt',
						'view_phone' => '8453613175',
						'phone' => '8453613175',
						'emails' => 'paulywelt@yahoo.com',
						'created_at' => '2018-02-15 12:10:15',
					], [
						'code' => '15SKZq4gOlx__',
						'user_id' => 96,
						'team_id' => 85,
						'firstname' => 'JENNIFER',
						'lastname' => 'SMITH',
						'view_phone' => '3103832434',
						'phone' => '3103832434',
						'emails' => 'jensmithnyc@gmail.com',
						'created_at' => '2018-02-15 11:36:50',
					], [
						'code' => '15mEtjIhtJdP6',
						'user_id' => 34,
						'team_id' => 41,
						'firstname' => 'Marion',
						'lastname' => 'Hart',
						'view_phone' => '9144725984',
						'phone' => '9144725984',
						'emails' => 'marionhart36@gmail.com',
						'created_at' => '2018-02-15 11:32:15',
					], [
						'code' => '15exkUncw957U',
						'user_id' => 75,
						'team_id' => 70,
						'firstname' => 'Jackie',
						'lastname' => 'Neesan',
						'view_phone' => '2245349513',
						'phone' => '2245349513',
						'emails' => 'jazra7@gmail.com',
						'created_at' => '2018-02-15 11:11:32',
					]
				];
				print_r(json_encode($leads));*/
			?>
			<div class="panel panel-default" ng-show="list.length">
				<div class="panel-heading">
					<strong>{{ __('Leads') }}</strong>
				</div>
				<div class="panel-body leads">
					<div ng-repeat="item in list">
						<div class="leads-list">
							<div class="pull-left message-icon">
								<i ng-show="item.count == 0" class="fa fa-envelope-o" aria-hidden="true" uib-tooltip="{{ __('You haven\'t new message') }}"></i>
								<a ng-show="item.count > 0" href="/marketing/inbox/@{{item.id}}/" class="fa fa-envelope-o text-primary" aria-hidden="true" uib-tooltip="{{ __('You have new message(s)') }}"></a>
							</div>
							<div class="pull-left">
								<div>
									<strong>@{{ item.view_phone }}</strong>
									<span class="small-italic">(@{{ item.created_at_string}})</span>
								</div>
								<div>
									@{{ item.firstname }}
									@{{ item.lastname }}
									<i ng-show="item.inbox" class="fa fa-check-circle-o text-success" aria-hidden="true" uib-tooltip="Lead texted a reply"></i>
									<span> </span>
									<i ng-show="item.clicked" class="fa fa-check-circle-o text-info" aria-hidden="true" uib-tooltip="Link clicked"></i>
									<span> </span>
									<i ng-show="item.hapage" class="fa fa-check-circle-o text-danger" aria-hidden="true" uib-tooltip="Lead visited HA Page"></i>
								</div>
							</div>
						</div>
						<div class="divider divider-dashed divider-sm pull-in">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/ng-template" id="companyTooltip.html">
	<span>{{ __('Customize your Company Name at the beginning of your text, like in the image. The limit for the Company Name is 32 characters.') }}</span>
	<img src="/img/company_name_help.png" class="img-responsive" />
</script>