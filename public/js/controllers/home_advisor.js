(function () {
    'use strict';

    angular.module('app').controller('HomeAdvisorCtrl', ['$rootScope', '$scope', '$timeout', '$http', '$location', 'request', 'langs', 'logger', 'validate', HomeAdvisorCtrl]);

    function HomeAdvisorCtrl($rootScope, $scope, $timeout, $http, $location, request, langs, logger, validate) {
        $scope.ha = {
            first_followup_delay: '60',
            second_followup_delay: '60'
        };
        $scope.inputs = [];
        $scope.emails = [];
        $scope.list = [];
        $scope.companyChanged = false;
        $scope.oldCompany = angular.copy($scope.user.company_name);
        $scope.file = {};
        $scope.request = false;
        $scope.settings = {};
        $scope.pictures = [];
        $scope.uploading = {
            pictures: 0
        };
        $scope.followup_hours = [
            {
                text: 15,
                value: 15
            }, {
                text: 30,
                value: 30
            }
        ];
        $scope.referral = {};

        $scope.init = function() {
            $scope.get();
            $scope.getPictures();
            $scope.getLeads();
            $scope.generateHours();
        };

        $scope.get = function() {
            request.send('/homeadvisor', {}, function (data) {
                if (data) {
                    $scope.ha = data;

                    if ($scope.ha.first_followup_delay != '0' && $scope.ha.second_followup_delay != '0') {
                        $scope.ha.first_followup_delay = $scope.ha.first_followup_delay.toString();
                        $scope.ha.second_followup_delay = $scope.ha.second_followup_delay.toString();
                    }

                    if ($scope.ha.additional_phones) {
                        $scope.inputs = $scope.ha.additional_phones.split(',');
                    }
                    
                    if ($scope.inputs.length <= 1) {
                        $scope.inputs.push('');
                    }

                    if ($scope.ha.emails) {
                        $scope.emails = $scope.ha.emails.split(',');
                    }
                    $scope.emails.push('');

                    if ($scope.ha.file) {
                        $scope.file.url = $location.protocol() + '://' + $location.host() + '/' + $scope.ha.file;
                    }
                }
            }, 'get');
        };

        $scope.getPictures = function () {
            request.send('/pictures', {}, function (data) {
                if (data) {
                    $scope.pictures = data;
                }
            }, 'get');
        };

        $scope.getLeads = function() {
            request.send('/clients/leads', {}, function (data) {
                $scope.list = data;
                for (var k in $scope.list) {
                    $scope.list[k].count = 0;

                    for (var j in $scope.list[k].dialogs) {
                        $scope.list[k].count += $scope.list[k].dialogs[j].new;
                        if ($scope.list[k].dialogs[j].my == 0) {
                            $scope.list[k].inbox = true;
                        }
                    }
                }
            }, 'get');
        };
        
        $scope.getVonageLeads = function() {
            request.send('/clients/vonage', {}, function (data) {
                $scope.list = data;
                for (var k in $scope.list) {
                    $scope.list[k].count = 0;

                    for (var j in $scope.list[k].dialogs) {
                        $scope.list[k].count += $scope.list[k].dialogs[j].new;
                        if ($scope.list[k].dialogs[j].my == 0) {
                            $scope.list[k].inbox = true;
                        }
                    }
                }
            }, 'get');
        };

        $scope.getSuffix = function(day) {
            switch (day) {
                case '1': return 'st';
                case '2': return 'nd';
                case '3': return 'rd';
                default: return  'th';
            }
        };

        $scope.activate = function() {
            request.send('/homeadvisor/activate' + ($scope.ha.id ? '/' + $scope.ha.id : ''), {}, function (data) {
                $scope.ha.send_request = true;
            }, ($scope.ha.id ? 'post' : 'put'));
        };

        $scope.registerReferral = function() {
            var error = 1;
            
            error *= validate.check($scope.form.name, 'Name');
            error *= validate.check($scope.form.contacts, 'Number or Email');

            if (error) {
                request.send('/homeadvisor/referral', $scope.referral, function (data) {
                    $scope.referral.name = '';
                    $scope.referral.contacts = '';
                }, 'post');
            }
        }

        $scope.add = function() {
            if ($scope.inputs.length <= 1) {
                $scope.inputs.push('');
            } else {
                logger.log('You can add only 2 additional phone numbers');
            }
        };

        $scope.remove = function(index) {
            $scope.inputs.splice(index, 1);
        };

        $scope.emailsAdd = function () {
            $scope.emails.push('');
        };

        $scope.emailsRemove = function (index) {
            $scope.emails.splice(index, 1);
        };

        $scope.save = function(plan) {
            var error = 1;
            if ($scope.ha.text == '') {
                logger.logError(langs.get('SMS Text can\'t be blank'));
                error = 0;
            }

            if (plan == 'vonage' && $scope.user.company_status != 'verified') {
                $scope.companySave();
            }

            if ($scope.user.company_name == '') {
                logger.logError(langs.get('Company Name is required'));
                error = 0;
            } else {
                if (($scope.user.company_status != 'verified' || $scope.companyChanged) && plan != 'vonage') {
                    logger.logError(langs.get('Company Name must be verified'));
                    error = 0;
                }
            }

            error *= validate.phone($scope.form_ha.phone, 'Number for alerts');

            var inputs = [];
            for (var k in $scope.inputs) {
                if ($scope.inputs[k] != '') {
                    error *= validate.phone($scope.form_ha['phone_' + k], 'Additional phone');
                    inputs.push($scope.inputs[k]);
                }
            }

            var emails = [];
            for (var k in $scope.emails) {
                if ($scope.emails[k] != '') {
                    error *= validate.check($scope.form_ha['email_' + k], 'Email');
                    emails.push($scope.emails[k]);
                }
            }

            if (error) {
                $scope.ha.additional_phones = inputs.join(',');
                $scope.ha.emails = emails.join(',');
                request.send('/homeadvisor' + ($scope.ha.id ? '/' + $scope.ha.id : ''), { 'ha': $scope.ha, 'user': $scope.user, 'pictures': $scope.pictures}, function() {
                    $scope.getPictures();
                }, ($scope.ha.id ? 'post' : 'put'));
            }
        };
        
        $scope.saveBeforeActivation = function() {
            var error = 1;
            if ($scope.user.company_name == '') {
                logger.logError(langs.get('Company Name is required'));
                error = 0;
            }

            error *= validate.check($scope.form_ha.website, 'Website');
            error *= validate.phone($scope.form_ha.office_phone, 'Office Number');
            error *= validate.phone($scope.form_ha.phone, 'Number for alerts');

            var inputs = [];
            for (var k in $scope.inputs) {
                if ($scope.inputs[k] != '') {
                    error *= validate.phone($scope.form_ha['phone_' + k], 'Additional phone');
                    inputs.push($scope.inputs[k]);
                }
            }

            if (error) {
                $scope.ha.additional_phones = inputs.join(',');
                request.send('/homeadvisor' + ($scope.ha.id ? '/' + $scope.ha.id : ''), { 'ha': $scope.ha, 'user': $scope.user, 'pictures': $scope.pictures}, function() {
                    $scope.getPictures();
                    $scope.companySave();
                }, ($scope.ha.id ? 'post' : 'put'));
            }
        };

        $scope.enable = function () {
            request.send('/homeadvisor/enable/' + $scope.ha.id, {}, false, 'put');
        };
        
        $scope.disableFollowup = function (id) {
            request.send('/clients/disableFollowup/' + id, {}, false, 'put');
        };

        $scope.maxChars = function (field) {
            var max = 0;
            for (var k in $scope.list) {
                max = Math.max(max, $scope.list[k][field].length);
            }
            return max;
        };

        $scope.uploadFile = function(file) {
            var size = file.size / 1024;
            if (size > 500) {
                logger.logError(langs.get('Image size limit is 500 KB'));
                return;
            }

            $scope.file.name = file.name;
            var fd = new FormData();
            fd.append('file', file);

            $http.post('/api/v1/upload/file', fd, {
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined}
            }).then(function(response) {
                $scope.ha.file = response.data.data;
                $scope.file.url = $location.protocol() + '://' + $location.host() + '/' + response.data.data;
                $scope.request = false;
            });
        };

        $scope.uploadPictures = function (files) {
            var fd = new FormData();
            var check = true;
            for (var k in files) {
                var size = files[k].size / 1024;
                if (size > 2048) {
                    logger.logError(langs.get(files[k].name + ' is too large. Image size limit is 500 KB'));
                    check = false;
                } else {
                    fd.append('file' + k, files[k]);
                }
            }

            if (($scope.pictures.length + files.length) > 5) {
                logger.logError(langs.get('Max number of images is 5'));
                return;
            } 

            if (check && files.length) {
                $scope.uploading['pictures'] = files.length;
                $http.post('/api/v1/upload/fileS3', fd, {
                    transformRequest: angular.identity,
                    headers: {
                        'Content-Type': undefined
                    }
                }).then(function (response) {
                    $scope.uploading['pictures'] = 0;
                    var data = JSON.parse(response.data.data);
                    for (var k in data) {
                        $scope.pictures.push({
                            url: data[k]
                        });
                    }
                });
            }
        };

        $scope.removePicture = function(index) {
            var removed = $scope.pictures.splice(index, 1);
            request.send('/pictures/remove', { 'picture': removed[0] }, false, 'post');
        }

        $scope.removeMMS = function() {
            $scope.ha.file = $scope.file.url = '';
        };

        $scope.companyChange = function () {
            $scope.companyChanged = false;
            if ($scope.oldCompany != $scope.user.company_name) {
                $scope.companyChanged = true;
            }
        };

        $scope.companySave = function () {
            request.send('/users/company', { 'company': $scope.user.company_name }, function (data) {
                if (data) {
                    $scope.user.company_status = data.status;
                    $scope.companyChanged = false;
                    $scope.checkCompany();
                }
            }, 'put');
        };

        $scope.checkCompany = function () {
            $timeout.cancel($scope.timer);
            if ($scope.user.company_status == 'pending') {
                $scope.timer = $timeout(function () {
                    request.send('/users/status', {}, function (data) {
                        if (data) {
                            $scope.user.company_status = data.status;
                        }
                        $scope.checkCompany();
                    }, 'get');
                }, 5000);
            }
        };

        $scope.generateHours = function () {
            for (var i = 1; i <= 24; i++) {
                $scope.followup_obj = {};
                $scope.followup_obj.text = i;
                $scope.followup_obj.value = i * 60;
                $scope.followup_hours.push($scope.followup_obj);
            }
        };

        $scope.getHourText = function (amount) {
            switch (amount) {
                case 15:
                    return 'minutes after Instant Text';
                    break;
                case 30:
                    return 'minutes after Instant Text';
                    break;
                case 60:
                    return 'hour after Instant Text';
                    break;
                default:
                    return 'hours after Instant Text';
                    break;
            }
        };
    };
})();

;