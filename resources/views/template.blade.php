<!DOCTYPE html>
<html data-ng-app="app" data-ng-controller="AppCtrl" data-ng-init="init({{ auth()->user() }})">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>{{ config('app.name') }}</title>

        <link rel="stylesheet" type="text/css" href="/css/libs/bootstrap.min.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="/css/libs/font-awesome.min.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="/css/style.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="/css/main.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="/css/app.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="/css/{{ config('app.name') }}.css" media="screen" />
    </head>

    <body id="app" class="app">

        @include('header')

        <div class="main-container" data-ng-class="{'on-canvas': show_aside == 1}">
            @include('menu')

            <div id="content" class="content-container">
                <section data-ng-view class="view-container">
                </section>
            </div>

            <footer class="app-footer">
                <span>Copyright {{ date('Y') }}</span>
                <span class="pull-right">{{ config('app.name') }}</span>
            </footer>
        </div>

        <script type="text/ng-template" id="ModalFirstTime.html">
            <form name="form" method="post" novalidate="novalidate">
                <div class="modal-header">
                    <h4 class="modal-title">{{ config('app.name') }}</h4>
                </div>

                <div class="modal-body">
                    <span>{{ __("Welcome!") }}</span><br />
                    <span>{{ __("A couple things before we start.") }}</span><br /><br />
                    <ol>
                        <li>
                            <span>{{ __("In order to legally text clients, we need their written consent. You need to add the following line in the paperwork they sign at the appointment, which says: ‘I consent to be texted at the number I’ve provided regarding my appointment’. Once you add that, you are good to go!") }}</span>
                        </li>
                        <li>
                            <span>{{ __("Please go to the Online Reviews Settings page and enter your online review sites.") }}</span>
                        </li>
                    </ol>
                    <span>{{ __("If you have any questions, click the Email Icon at the top of the page to email us - thanks!") }}</span>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" ng-click="close()">{{ __('Close') }}</button>
                </div>
            </form>
        </script>

        <script src="/js/libs/angular.js"></script>
        <script src="/js/libs/jquery.js"></script>
        <script src="/js/libs/ui.js"></script>
        <script src="/js/libs/ng-file-upload.min.js"></script>
        <script src="/js/app.js"></script>
        <script src="/js/factories.js"></script>
        <script src="/js/controllers/auth.js"></script>
        <script src="/js/controllers/users.js"></script>
        <script src="/js/controllers/teams.js"></script>
        <script src="/js/controllers/plans.js"></script>
        <script src="/js/controllers/links.js"></script>
        <script src="/js/controllers/alerts.js"></script>
        <script src="/js/controllers/text_marketing.js"></script>
        <script src="/js/controllers/marketing_send.js"></script>
        <script src="/js/controllers/marketing_outbox.js"></script>
        <script src="/js/controllers/home_advisor.js"></script>
        <script src="/js/controllers/reviews.js"></script>
        <script src="/js/controllers/surveys.js"></script>
        <script src="/js/controllers/surveys_partners.js"></script>
        <script src="/js/directives/charset.js"></script>
        <script src="/js/directives/upload.js"></script>
        <script src="/js/directives/scroll_bottom.js"></script>
    </body>
</html>