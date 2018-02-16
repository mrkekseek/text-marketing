<!DOCTYPE html>
<html data-ng-app="app" data-ng-controller="PicturesCtrl" ng-init="init({{ $user or 0 }}, {{ $ha or 0 }}, {{ $pictures or 0 }})">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>{{ $user->company_name }}</title>

        <link rel="stylesheet" type="text/css" href="/css/libs/bootstrap.min.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="/css/libs/font-awesome.min.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="/css/style.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="/css/main.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="/css/app.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="/css/{{ config('app.name') }}.css" media="screen" />
    </head>

    <body id="app" class="app">
        <div class="container">
            <div class="pictures-header text-center">
                <h1>{{ $user->firstname.' '.$user->lastname }}'s Job Pics</h1>
                @if ( ! empty($ha->file))
                    <img src="/{{ $ha->file }}" alt="" />
                @endif
            </div>

            @if (count($pictures))
                <div class="pictures-box">
                    <div class="pictures-screen">
                        <div class="pictures-item" ng-repeat="picture in pictures" ng-class="{current: picture.state == 'current', next: picture.state == 'next', prev: picture.state == 'prev'}">
                            <img src="@{{ picture['url'] }}" alt="" />
                        </div>
                    </div>

                    @if (count($pictures) > 1)
                        <div class="pictures-arrow arrow-left" ng-click="prev()">
                            <i class="fa fa-chevron-left"></i>
                        </div>

                        <div class="pictures-arrow arrow-right" ng-click="next()">
                            <i class="fa fa-chevron-right"></i>
                        </div>
                    @endif
                </div>

                @if ( ! empty($link))
                    <p class="bottom-link-p">
                        Click to book - <a href="http://{{ $link }}" target="_blank">http://{{ $link }}</a>
                    </p>
                @endif
            @endif
        </div>         

        <script src="/js/libs/angular.js"></script>
        <script src="/js/libs/jquery.js"></script>
        <script src="/js/libs/ui.js"></script>
        <script src="/js/pictures.js"></script>
        <script src="/js/factories.js"></script>
    </body>
</html>