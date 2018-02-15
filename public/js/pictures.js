(function () {
    'use strict';

    angular.module('app', ['ngRoute', 'ngSanitize', 'ui.bootstrap']);
})();

(function () {
    'use strict';

    angular.module('app').controller('PicturesCtrl', ['$rootScope', '$scope', '$window', '$timeout', '$location', 'request', 'validate', 'langs', PicturesCtrl]);

    function PicturesCtrl($rootScope, $scope, $window, $timeout, $location, request, validate, langs) {
        $scope.user = {};
        $scope.ha = {};
        $scope.pictures = {};

        $scope.init = function (user, ha, pictures) {
            $scope.user = user;
            $scope.ha = ha;
            $scope.pictures = $scope.initPictures(pictures);
        };

        $scope.initPictures = function(pictures) {
            if (pictures.length == 2) {
                pictures[2] = angular.copy(pictures[0]);
                pictures[3] = angular.copy(pictures[1]);
            }

            for (var k in pictures) {
                if (k == 0) {
                    pictures[k].state = 'current';
                } else if (k == 1) {
                    pictures[k].state = 'next';
                } else if (k == pictures.length - 1) {
                    pictures[k].state = 'prev';
                }
            }
            return pictures;
        };

        $scope.next = function() {
            var indexes = {
                prev: false,
                current: false,
                next: false
            };

            var next_check = false;
            for (var k in $scope.pictures) {
                if (next_check) {
                    next_check = false;
                    indexes.next = k;
                }

                if ($scope.pictures[k].state == 'current') {
                    indexes.prev = k;
                }

                if ($scope.pictures[k].state == 'next') {
                    indexes.current = k;
                    next_check = true;
                    if (k == ($scope.pictures.length - 1)) {
                        indexes.next = "0";
                    }
                }
            }

            for (var k in $scope.pictures) {
                if (k == indexes.prev) {
                    $scope.pictures[k].state = 'prev';
                } else if (k == indexes.current) {
                    $scope.pictures[k].state = 'current';
                } else if (k == indexes.next) {
                    $scope.pictures[k].state = 'next';
                } else {
                    $scope.pictures[k].state = '';
                }
            }
        };

        $scope.prev = function () {
            var indexes = {
                prev: 0,
                current: 0,
                next: 0
            };

            var prev_check = false;
            for (var k in $scope.pictures) {
                if ($scope.pictures[k].state == 'current') {
                    indexes.next = k;
                }

                if ($scope.pictures[k].state == 'prev') {
                    indexes.current = k;
                    prev_check = true;
                }

                if (prev_check) {
                    prev_check = false;
                    if (k == 0) {
                        indexes.prev = ($scope.pictures.length - 1);
                    } else {
                        indexes.prev = k - 1;
                    }
                }
            }
            
            for (var k in $scope.pictures) {
                if (k == indexes.next) {
                    $scope.pictures[k].state = 'next';
                } else if (k == indexes.prev) {
                    $scope.pictures[k].state = 'prev';
                } else if (k == indexes.current) {
                    $scope.pictures[k].state = 'current';
                } else {
                    $scope.pictures[k].state = '';
                }
            }
        };
    };
})();

;