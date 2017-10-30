<aside id="nav-container" class="nav-container bg-dark nav-vertical nav-fixed sidebar">
    <div class="nav-wrapper">
        <ul id="nav" class="nav">
            <li data-ng-repeat="page in pages" data-ng-class="{'active': checkMenuActive(page)}" ng-hide="(user.users_send_type == 0 && page.pages_code == 'messages-messages') || ((page.pages_code == 'surveys-surveys' || page.pages_code == 'new-online-review' || page.pages_code == 'messages-messages') && user.plans_id == 'home-advisor-contractortexter')">
                <a href="@{{ page.pages_file == '' ? 'javascript:;' : ('/' + (page.pages_main == 0 ? (page.pages_folder + '/' + page.pages_file + '/') : '')) }}" ng-click="changePage(page);">
                    <i class="@{{ page.pages_icon }}"></i>
                    <span>@{{ page.pages_name }}</span>
                </a>

                <ul class="sub-menu" data-ng-class="{'opened': open[page.pages_code] == 1}">
                    <li data-ng-repeat="sub in page.pages" data-ng-class="{'active': checkMenuActive(sub)}" ng-hide="sub.pages_file == 'settings-teams' && user.teams_leader == '0'">
                        <a href="@{{ '/' + sub.pages_folder + '/' + sub.pages_file + '/' }}">
                            <i class="ti-angle-right"></i>
                            <span>@{{ sub.pages_name }}</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</aside>