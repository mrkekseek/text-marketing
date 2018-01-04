(function () {
    'use strict';

    angular.module('app').controller('MarketingInboxCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', '$location', 'logger', MarketingInboxCtrl]);

    function MarketingInboxCtrl($rootScope, $scope, $uibModal, request, langs, $location, logger) {
        $scope.dialogs = [];
        $scope.messages = [];
        $scope.activeClient = {};
        $scope.messages_text = '';
        $scope.sent = false;
        $scope.inputs = [''];

    	$scope.init = function() {
            $scope.get();
        };

        $scope.addInput = function() {
            $scope.inputs.push('');
        };

        $scope.removeInput = function(index) {
            $scope.inputs.splice(index, 1);
        };

        $scope.get = function() {
            request.send('/dialogs', {}, function (data) {
                $scope.dialogs = data;
                console.log($scope.dialogs);
                if ($scope.dialogs.length) {
                    var temp = $location.path().split('/');
                    if (temp[3]) {
                        $scope.activeClient.id = temp[3];
                    } else if ($scope.dialogs.length) {
                        $scope.activeClient.id = $scope.dialogs[0].clients.id;
                    }
                    $scope.setClient($scope.activeClient);
                }

            }, 'get');
        };

        $scope.setClient = function(client) {
            $location.path('/marketing/inbox/' + client.id, false);
            $scope.activeClient.id = client.id;
            request.send('/dialogs/' + client.id, {}, function (data) {
                $scope.messages = data;
                console.log($scope.messages);
                for (var k in $scope.messages) {
                    var date = new Date($scope.messages[k].created_at);
                    $scope.messages[k].created_at = date;
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

        $scope.maxChars = function() {
            return 500 - ' Txt STOP to OptOut'.length - ($scope.user.company_status == 'verified' ? $scope.user.company_name.length + 2 : 0);
        };

        $scope.maxOneText = function() {
            return 140 - ' Txt STOP to OptOut'.length - ($scope.user.company_status == 'verified' ? $scope.user.company_name.length + 2 : 0);
        };

        $scope.charsCount = function(text) {
            if (text) {
                return text.length;
            }
            return 0;
        };

        $scope.send = function() {
            if ( ! $scope.messages_text) {
                logger.logError(langs.get('Text is empty.'));
                return;
            }
            
            var date = new Date();
            var post_mas = {
                'text': $scope.messages_text,
                'time': {
                    'hour': date.getHours()
                }
            };

            $scope.sent = true;
            request.send('/dialogs/create/' + $scope.activeClient.id, post_mas, function (data) {
                if (data) {
                    $scope.messages.push(data);
                    $scope.messages_text = '';
                }
                $scope.sent = false;
            }, 'put');
        };

        $scope.saveSettings = function() {
            request.send('/users/' + $scope.user.id, post_mas, function (data) {
                
            });
        };
    };
})();

;

(function () {
    'use strict';

    angular.module('app').controller('MarketingSettingsCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', '$location', 'logger', MarketingSettingsCtrl]);

    function MarketingSettingsCtrl($rootScope, $scope, $uibModal, request, langs, $location, logger) {
        $scope.inputs = [''];

        $scope.init = function() {
            if ($scope.user.additional_phones) {
                var temp = $scope.user.additional_phones.split(',');
                $scope.inputs = [];
                for (var k in temp) {
                    $scope.inputs.push(temp[k]);
                }
            }
        };

        $scope.addInput = function() {
            $scope.inputs.push('');
        };

        $scope.removeInput = function(index) {
            $scope.inputs.splice(index, 1);
        };

        $scope.saveSettings = function() {
            var post_mas = {
                'company_name': $scope.user.company_name,
                'additional_phones': $scope.inputs
            };
            request.send('/users/saveSettings', post_mas, function (data) {
                
            }, 'post');
        };
    };
})();

;

(function () {
    'use strict';

    angular.module('app').controller('MarketingContactsCtrl', ['$rootScope', '$scope', '$uibModal', '$filter', 'request', 'langs', 'validate', 'logger', MarketingContactsCtrl]);

    function MarketingContactsCtrl($rootScope, $scope, $uibModal, $filter, request, langs, validate, logger) {
        $scope.list = [];
        $scope.listsList = [];
        $scope.selectedList = {};
        $scope.originList = {};
        $scope.originClient = {};

        $scope.init = function() {
            $scope.get();
            $scope.getList();
        };

        $scope.get = function() {
            request.send('/clients', {}, function (data) {
                $scope.list = data;
            }, 'get');
        };

        $scope.getList = function() {
            request.send('/lists', {}, function (data) {
                $scope.listsList = data;
            }, 'get');
        };

        $scope.cancel = function(index, list) {
            if (! list.id) {
                $scope.listsList.splice(index, 1);
            } else {
                $scope.listsList[index] = $scope.originList;
            }
        };

        $scope.saveList = function(index) {
            if ( ! $scope.listsList[index].name) {
                logger.logError('List name is required');
                return;
            }

            $scope.listsList[index].editable = false;
            request.send('/lists/' + ( ! $scope.listsList[index].id ? 'save' : $scope.listsList[index].id), $scope.listsList[index], function (data) {
                $scope.listsList[index].id = data;
            }, (! $scope.listsList[index].id ? 'put' : 'post'));
        };

        $scope.remove = function(id, index) {
            if (confirm(langs.get('Do you realy want to remove this list?'))) {
                request.send('/lists/' + id, {}, function (data) {
                    $scope.listsList.splice(index, 1);   
                }, 'delete');
            }
        };

        $scope.create = function() {
            $scope.listsList.unshift({
                'editable': true,
                'clients': []
            });
        };

        $scope.choose = function(index) {
            if ($scope.selectedList.id != $scope.listsList[index].id) {
                $scope.selectedList = $scope.listsList[index];
                return;
            }
            $scope.selectedList = {};
        };

        $scope.edit = function(index) {
            $scope.originList = angular.copy($scope.listsList[index]);
            $scope.listsList[index].editable = true;
        };

        $scope.saveClient = function(clientIndex, index, client) {
            var error = 1;
            if ( ! client.phone) {
                logger.logError('Phone number is required');
                error = 0;
            }

            if ( ! client.firstname) {
                logger.logError('Name is required');
                error = 0;
            }
            
            if (error) {
                $scope.listsList[index].clients[clientIndex].lists_id = $scope.listsList[index].id;
                $scope.listsList[index].clients[clientIndex].view_phone = $scope.listsList[index].clients[clientIndex].phone;

                request.send('/clients/' + ( ! $scope.listsList[index].clients[clientIndex].id ? 'createForList/' + $scope.listsList[index].id : 'updateForList/' + $scope.listsList[index].id + '/' + $scope.listsList[index].clients[clientIndex].id), $scope.listsList[index].clients[clientIndex], function (data) {
                    if (data) {
                        $scope.activeEditable = $scope.listsList[index].clients[clientIndex].editable = false;
                        $scope.listsList[index].clients[clientIndex].id = data;
                        $scope.get();
                    }
                }, ( ! $scope.listsList[index].clients[clientIndex].id ? 'put' : 'post'));
            }
        };

        $scope.createClient = function(index) {
            if ( ! $scope.activeEditable) {
                $scope.listsList[index].clients.unshift({
                    'editable': true,
                    'phone': '',
                    'view_phone': '',
                    'email': '',
                    'firstname': '',
                    'lastname': '',
                    'source': 'Manually'
                });
                $scope.activeEditable = true;
            }
        };

        $scope.removeClient = function(client, index, clientIndex) {
            request.send('/clients/removeFromList/' + $scope.listsList[index].id + '/' + $scope.listsList[index].clients[clientIndex].id, {}, function (data) {
                $scope.listsList[index].clients.splice(clientIndex, 1);
            }, 'delete');
        };

        $scope.cancelClient = function(i, index) {
            if ( ! $scope.originClient.id) {
                $scope.listsList[index].clients.shift();
            } else {
                $scope.listsList[index].clients[i] = angular.copy($scope.originClient);
                $scope.originClient = {};
            }
            $scope.activeEditable = false;
        };

        $scope.editClient = function(client) {
            $scope.activeEditable = true;
            $scope.originClient = angular.copy(client);
            client.editable = true;
        };

        $scope.saveSelectedPhones = function(index) {
            for (var k in $scope.list) {
                if ($scope.list[k].selected) {
                    var check = false;
                    for (var j in $scope.listsList[index].clients) {
                        if ($scope.listsList[index].clients[j].id == $scope.list[k].id) {
                            check = true;
                        }
                    }
                    if (! check) {
                        $scope.listsList[index].clients.push($scope.list[k]);
                    }
                    
                    $scope.list[k].selected = false;
                }
            }
            
            request.send('/clients/addToList/' + $scope.listsList[index].id, $scope.listsList[index].clients, function (data) {

            });
        };

        $scope.openImport = function() {
            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'ImportFile.html',
                controller: 'ImportFileCtrl'
            });

            modalInstance.result.then(function(response) {
            }, function () {});
        };
    };
})();

;

(function () {
    'use strict';

    angular.module('app').controller('ImportFileCtrl', ['$rootScope', '$scope', '$uibModalInstance', 'request', 'langs', 'logger', ImportFileCtrl]);

    function ImportFileCtrl($rootScope, $scope, $uibModalInstance, request, langs, logger) {
        $scope.csv = {'phones_firstname': 1,
                    'phones_lastname': 2,
                    'phones_number': 3,
                    'phones_email': "",
                    'starts_from': "0",
                    'upload_csv': false};

        $scope.upload_progress = false;
        $scope.upload_percent = 100;

       $scope.save = function() {
            var error = 1;
            if (! $scope.csv.upload_csv)
            {
                logger.logError('Please choose file');
                return;
            }

           if (error)
            {
                request.send('/phones/csv/', $scope.csv, function(data) {
                    if (data)
                    {
                        $uibModalInstance.close(data);
                    }
                });
            }
        };

        $scope.cancel = function() {
            $uibModalInstance.dismiss('cancel');
        };

        $scope.upload_csv = function(event) {
            var files = event.target.files;
            if (files.length)
            {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '/api/pub/upload/', true);
                xhr.onload = function(event)
                {
                    if (this.status == 200)
                    {
                        var response = JSON.parse(this.response);
                        if (response.data) {
                            var part = response.data.split('/data/');
                            var ext = part[1].split('.');
                            $timeout(function() { $scope.csv.upload_csv = '/data/' + part[1]; });
                        }
                        $scope.upload_progress = false;
                    }
                };

                xhr.upload.onprogress = function(event)
                {
                    if (event.lengthComputable)
                    {
                        $scope.upload_progress = true;
                        $scope.upload_percent = Math.round(event.loaded * 100 / event.total);
                    }
                };

                var fd = new FormData();
                fd.append("file", files[0]);

                xhr.send(fd);
                $scope.upload_progress = true;
            }
        };

        $scope.getFileName = function(path) {
            if (!path || path == "") return '';
            return path.replace(/^.*[\\\/]/, '')
        };
        
    };
})();

;