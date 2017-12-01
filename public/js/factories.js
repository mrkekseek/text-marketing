(function () {
    'use strict';

    angular.module('app').factory('langs', ['$http', langs]);

    function langs($http) {
        var list = {};
        /*$http.get('/api/langs/get').then(function(response) {
            list = response;
        });*/

        return {
            get: function(key, vars) {
                vars = vars || {};
                var text = key;
                for (var i in list) 
                {
                    if (i.toLowerCase() == key.toLowerCase() && list[i] != '')
                    {
                        text = list[i];
                    }
                }

                for (var i in vars) 
                {
                    text = text.replace(":" + i, vars[i]);
                }

                return text;
           }
        };
    };
})();

;

(function () {
    'use strict';

    angular.module('app').factory('logger', ['langs', logger]);

    function logger(langs) {

        toastr.options = {
            "closeButton": true,
            "positionClass": "toast-bottom-right",
            "timeOut": "3000"
        };

        var logIt = function(message, vars, type) {
            return toastr[type](langs.get(message, vars));
        };

        return {
            log: function(message, vars) {
                logIt(message, vars, 'info');
            },
            logWarning: function(message, vars) {
                logIt(message, vars, 'warning');
            },
            logSuccess: function(message, vars) {
                logIt(message, vars, 'success');
            },
            logError: function(message, vars) {
                logIt(message, vars, 'error');
            },
            check: function(data) {
                if (data.messages)
                {
                    for (var key in data.messages)
                    {
                        var message = data.messages[key];
                        this[this.method(message.type)](message.text);
                    }
                }

                var data = typeof(data.data) == "string" ? JSON.parse(data.data) : data.data;
                if (data)
                {
                    return data;
                }
                else
                {
                    return false;
                }
            },
            method: function(type) {
                return 'log' + type.charAt(0).toUpperCase() + type.slice(1);
            }
        };
    };
})();

;

(function () {
    'use strict';

    angular.module('app').factory('request', ['$http', '$rootScope', 'Upload', 'logger', request]);

    function request($http, $rootScope, Upload, logger) {
        var api_url = '/api/v1';
        return {
            send: function (adrress, post_mas, callback, method) {
                callback = callback || false;
                method = method || 'post';
                
                $http[method](api_url + adrress, post_mas).then(function(response) {
                    var data = logger.check(response.data);
                    if (callback) {
                        (callback)(data);
                    }
                });
            },

            sendWithFiles: function (adrress, post_mas, callback, percentsCallback, method) {
                callback = callback || false;
                percentsCallback = percentsCallback || false;
                method = method || 'post';

                Upload.upload({
                    url: (api_url + adrress),
                    data: post_mas
                }).then(function (response) {
                    var data = logger.check(response.data);
                    if (callback) {
                        (callback)(data);
                    }
                }, function (response) {
                    
                }, function (event) {
                    var progress = parseInt(100.0 * event.loaded / event.total);
                    if (percentsCallback) {
                        (percentsCallback)(progress);
                    }
                });
            }
        };
    };
})();

;

(function () {
    'use strict';

    angular.module('app').factory('validate', ['logger', validate])
    
    function validate(logger) {     
        return {
            check: function(field, name, object_field, zero) {
                zero = zero || false;
                object_field = object_field || false;
                if (object_field && typeof(field.$viewValue) == 'object')
                {
                    if (field.$viewValue[object_field] == '0')
                    {
                        logger.logError(':name is required', {'name': name});
                        return false;
                    }
                }

                if (field.$valid)
                {
                    if ((field.$$element["0"].localName == 'select' || zero) && field.$viewValue == '0')
                    {
                        logger.logError('Choose :name first', {'name': name});
                        return false;
                    }
                    else
                    {
                        return true;
                    }
                }
                else
                {

                    if ( field.$viewValue == '' || field.$viewValue == undefined )
                    {
                        logger.logError(':name is required', {'name': name});
                    }
                    else
                    {
                        logger.logError(':name is incorrect', {'name': name});
                    }
                    return false;
                }
            }
        };
    };
})();

;

(function () {
    'use strict';

    angular.module('app').factory('charset', [charset]);

    function charset() {
        return {
            set: function(areaId, text) {
                var txtarea = document.getElementById(areaId);
                var scrollPos = txtarea.scrollTop;
                var strPos = 0;
                var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ? 
                    'ff' : (document.selection ? 'ie' : false ) );
                if (br == 'ie')
                {
                    txtarea.focus();
                    var range = document.selection.createRange();
                    range.moveStart('character', -txtarea.value.length);
                    strPos = range.text.length;
                }
                else if (br == 'ff') strPos = txtarea.selectionStart;
                
                var front = (txtarea.value).substr(0,strPos);  
                var back = (txtarea.value).substr(strPos,txtarea.value.length);
                if(front.substr(-1) != ' ' && front.substr(-1) != '') {
                    text = ' ' + text;
                }

                txtarea.value = front + text + back;

                strPos = strPos + text.length;
                if (br == 'ie') {
                    txtarea.focus();
                    var range = document.selection.createRange();
                    range.moveStart('character', -txtarea.value.length);
                    range.moveStart('character', strPos);
                    range.moveEnd('character', 0);
                    range.select();
                } else if (br == "ff") {
                    txtarea.selectionStart = strPos;
                    txtarea.selectionEnd = strPos;
                    txtarea.focus();
                }

                txtarea.scrollTop = scrollPos;
                return txtarea.value;
               }
            };
        };
})();

;