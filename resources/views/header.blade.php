<script type="text/ng-template" id="ModalEmail.html">
    <form name="form" method="post" novalidate="novalidate">
        <div class="modal-header">
            <h4 class="modal-title">{{ __("Send Email") }}</h4>
        </div>

        <div class="modal-body">
            <div class="form-group">
                <label>{{ __("Subject") }}</label>
                <input type="text" name="subject_email" class="form-control" ng-model="subject_email" required="required" />
            </div>

            <div class="form-group">
                <label>{{ __("Text") }}</label>
                <textarea name="text_email" class="form-control" ng-model="text_email" required="required"></textarea>
            </div>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary" ng-click="send()">{{ __('Send') }}</button>
            <button type="button" class="btn btn-default" ng-click="cancel()">{{ __('Cancel') }}</button>
        </div>
    </form>
</script>

<header id="header" class="header-container header-fixed bg-white" id="header">
    <div class="top-header clearfix">
        <div class="logo">
            <a href="/">
                <img src="/img/logo.jpg" class="img-responsive" />
            </a>
        </div>

        <div class="top-nav">
            <ul class="nav-left list-unstyled menu-xs">
                <li>
                    <a href="javascript:;" data-ng-click="toggleAside()">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </a>
                </li>
            </ul>

            <ul class="nav-right pull-right list-unstyled">
                <li>
                    <a href="javascript:;" class="dropdown-toggle" ng-click="modal_email()">
                        <i class="fa fa-envelope-o" uib-tooltip="Email us" tooltip-placement="left"></i>
                    </a>
                </li>

                <li class="dropdown" uib-dropdown>
                    <a href="/pages/tutorial/" class="dropdown-toggle">
                        <i class="fa fa-question-circle-o" ng-class="{'text-primary': new_inbox.count}" uib-tooltip="Help" tooltip-placement="left"></i>
                    </a>
                </li>

                <li class="dropdown text-normal nav-profile" uib-dropdown>
                    <a href="javascript:;" class="dropdown-toggle users-top-avatar" uib-dropdown-toggle>
                        <img data-ng-show="user.users_avatar && user.users_avatar != ''" src="@{{ user.users_avatar }}" alt="@{{ user.users_name }}" class="img-circle img30_30" />
                        <i class="fa fa-user-circle" aria-hidden="true" data-ng-show="! user.users_avatar || user.users_avatar == ''"></i>
                        <span class="hidden-xs">
                            <span>@{{ user.firstname }}</span>
                            <span>@{{ user.lastname }}</span>
                        </span>
                    </a>

                    <ul class="dropdown-menu with-arrow pull-right" uib-dropdown-menu>
                        <li>
                            <a href="/users/profile">
                                <i class="ti-user"></i>
                                <span>{{ __('My Profile') }}</span>
                            </a>
                        </li>

                        <li>
                            <a href="javascript:;" data-ng-click="signout()">
                                <i class="ti-export"></i>
                                <span>{{ __('Sign out') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</header>