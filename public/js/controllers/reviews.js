(function () {
    'use strict';

    angular.module('app').controller('ReviewsAnalysisCtrl', ['$scope', 'request', 'langs', ReviewsAnalysisCtrl]);

    function ReviewsAnalysisCtrl($scope, request, langs) {
        $scope.analysis = {'seances': [], 'comments': []};
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
                if (seanceDate.getDate() == date.getDate() && 
                    seanceDate.getMonth() == date.getMonth() && 
                    seanceDate.getFullYear() == date.getFullYear()) {
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
                            data[k].seances[j].completed = $scope.createDate(data[k].seances[j].completed);
                            $scope.analysis.seances.push(data[k].seances[j]);
                            for (var i in data[k].seances[j].answers) {
                                if (data[k].seances[j].answers[i].value * 1) {
                                    $scope.responses[data[k].seances[j].answers[i].value * 1]++;
                                    rating += data[k].seances[j].answers[i].value * 1;
                                    data[k].seances[j].value = data[k].seances[j].answers[i].value * 1;
                                } else if (data[k].seances[j].answers[i].value != 0) {
                                    
                                    data[k].seances[j].comments = data[k].seances[j].answers[i].value;
                                }
                            }
                            $scope.analysis.comments.push(data[k].seances[j]);
                        }
                    }
                    console.log($scope.analysis.comments);
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
                        data[k].created_at = $scope.getUserDate(data[k].created_at);
                        var reviewRating = 0;
                        var reviewCount = 0;
                        for (var j in data[k].seances) {
                            reviewCount++;
                            data[k].seances[j].completed = $scope.createDate(data[k].seances[j].completed);
                            for (var i in data[k].seances[j].answers) {
                                if (data[k].seances[j].answers[i].value * 1 || data[k].seances[j].answers[i].value == 0) {
                                    data[k].seances[j].value = data[k].seances[j].answers[i].value * 1;
                                    reviewRating += data[k].seances[j].value;
                                } else {
                                    data[k].seances[j].comments = data[k].seances[j].answers[i].value;
                                }
                            }
                        }
                        data[k].value = reviewRating / reviewCount;
                        $scope.calendarResponses.push(data[k]);
                    }
                }
            });
        };

        $scope.getSuffix = function(num) {
            var res = '';
            if (num) {
                num = num.toString();
                switch(num.slice(num.length - 1)) {
                    case '1': res = 'st'; break;
                    case '2': res = 'nd'; break;
                    case '3': res = 'rd'; break;
                    default: res = 'th'; break;
                }
            }
            return res;
        };

        $scope.getUserDate = function(date) {
            date = new Date(date);
            date = date.getTime();
            date = date - (1000 * 60 * 60 * $scope.user.offset);
            return new Date(date);
        };

        $scope.createDate = function(string) {
            string = string.replace(' ', 'T');
            return new Date(string);
        };

        $scope.getStars = function(num) {
            return new Array(num);
        }
    };
})();

;

(function () {
    'use strict';

    angular.module('app').controller('ReviewsSettingsCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', 'logger', 'validate', ReviewsSettingsCtrl]);

    function ReviewsSettingsCtrl($rootScope, $scope, $uibModal, request, langs, logger, validate) {
        $scope.inputs = [];
        $scope.oldInput = {};

        $scope.init = function() {
            $scope.get();
        };

        $scope.get = function () {
            request.send('/urls', {}, function (data) {
                if (data) {
                    $scope.inputs = data;
                    $scope.inputs.push({'editable': true});
                }
            }, 'get');
        };

        $scope.save = function (input) {
            var error = 1;
            error *= validate.check($scope.form.name, 'Name');
            error *= validate.check($scope.form.url, 'Url');

            if (error) {
                request.send('/urls/' + (input.id ? input.id : ''), input, function (data) {
                    if (data) {
                        if ( ! input.id) {
                            $scope.inputs.pop();
                            $scope.inputs.push(data);
                            $scope.inputs.push({ 'editable': true });
                        } else {
                            input.editable = false;
                        }
                    }
                }, (input.id ? 'post' : 'put'));
            }
        };

        $scope.active = function(input) {
            if (input.id) {
                request.send('/urls/' + input.id, input, false);
            }
        };

        $scope.edit = function(input) {
            $scope.oldInput = angular.copy(input);
            input.editable = true
        }

        $scope.cancel = function(key) {
            $scope.inputs[key] = $scope.oldInput;
            $scope.inputs[key].editable = false;
        };

        $scope.remove = function(input, key) {
            if (confirm(langs.get('Do you realy want to remove this Review Site?'))) {
                request.send('/urls/' + input.id, {}, function (data) {
                    $scope.inputs.splice(key, 1);
                }, 'delete');
            }
        };
    };
})();