(function () {
    'use strict';

    angular.module('app').controller('ReviewsAnalysisCtrl', ['$scope', 'request', 'langs', ReviewsAnalysisCtrl]);

    function ReviewsAnalysisCtrl($scope, request, langs) {
        $scope.analysis = {'seances': []};
        $scope.calendarResponses = [];
        $scope.popover = {
            templateUrl: 'popoverTemplate.html'
        };
        $scope.calendarDate = new Date();
        $scope.responses = {5: 0, 4: 0, 3: 0, 2: 0, 1: 0};

        $scope.calendarOptions = {
            'customClass': getDayClass
        };

        function getDayClass(data) {
            var date = data.date;
            for (var k in $scope.analysis.seances) {
                var seanceDate = new Date($scope.analysis.seances[k].completed);
                if (seanceDate.getDate() == date.getDate() && seanceDate.getMonth() == date.getMonth() && seanceDate.getFullYear() == date.getFullYear()) {
                    return 'markup';
                }
            }
        };

        $scope.init = function() {
            $scope.get();
            $scope.getCalendar();
        };

        $scope.get = function() {
            request.send('/analysis', {}, function (data) {
                if (data) {
                    var count = 0;
                    var rating = 0;
                    for (var k in data) {
                        count += data[k].seances_count;
                        for (var j in data[k].seances) {
                            $scope.analysis.seances.push(data[k].seances[j]);
                            for (var i in data[k].seances[j].answers) {
                                $scope.responses[data[k].seances[j].answers[i].value * 1]++;
                                rating += data[k].seances[j].answers[i].value * 1;
                            }
                        }
                    }
                    $scope.analysis.responses = count;
                    $scope.analysis.rating = rating / count;
                    $scope.calendarDate = new Date();
                }
            }, 'get');
        };

        $scope.getCalendar = function() {
            $scope.calendarResponses = [];
            var date = $scope.calendarDate;
            var time = {
                'date': date.getDate(),
                'month': date.getMonth() + 1,
                'year': date.getFullYear()
            };
             
            request.send('/analysis/calendar', {'date': time}, function (data) {
                if (data) {
                    for (var k in data) {
                        for (var j in data[k].seances) {
                            for (var i in data[k].seances[j].answers) {
                                if (data[k].seances[j].answers[i].value * 1) {
                                    data[k].seances[j].value = data[k].seances[j].answers[i].value * 1;
                                }
                            }
                            $scope.calendarResponses.push(data[k].seances[j]);
                        } 
                    }
                }
            });
        };

        $scope.getStars = function(num) {
            return new Array(num);
        }
    };
})();

;