<aside id="nav-container" class="nav-container bg-dark nav-vertical nav-fixed sidebar">
    <div class="nav-wrapper">
        <ul id="nav" class="nav">
            <li data-ng-repeat="page in pages" data-ng-class="{'active': menuActive(page)}">
                <a href="@{{ page.file == '' ? 'javascript:;' : ('/' + (page.main == 0 ? (page.folder + '/' + page.file + '/') : '')) }}" data-ng-click="changePage(page);">
                    <i class="@{{ page.icon }}"></i>
                    <span>@{{ page.name }}</span>
                </a>

                <ul class="sub-menu" data-ng-class="{'opened': open[page.code]}">
                    <li data-ng-repeat="sub in page.pages" data-ng-class="{'active': menuActive(sub)}">
                        <a href="@{{ '/' + sub.folder + '/' + sub.file + '/' }}">
                            <i class="ti-angle-right"></i>
                            <span>@{{ sub.name }}</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</aside>