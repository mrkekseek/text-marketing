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
                for (var i in list) {
                    if (i.toLowerCase() == key.toLowerCase() && list[i] != '') {
                        text = list[i];
                    }
                }

                for (var i in vars) {
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

        var logIt = function (message, vars, type) {
            return toastr[type](langs.get(message, vars));
        };

        return {
            log: function (message, vars) {
                logIt(message, vars, 'info');
            },
            logWarning: function (message, vars) {
                logIt(message, vars, 'warning');
            },
            logSuccess: function (message, vars) {
                logIt(message, vars, 'success');
            },
            logError: function (message, vars) {
                logIt(message, vars, 'error');
            },
            check: function (data) {
                if (data.messages) {
                    for (var key in data.messages) {
                        var message = data.messages[key];
                        this[this.method(message.type)](message.text);
                    }
                }

                var data = typeof(data.data) == "string" && data.data != '' ? JSON.parse(data.data) : data.data;
                return data ? data : false;
            },
            method: function (type) {
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
                
                $http[method](api_url + adrress, post_mas).then(function (response) {
                    var data = logger.check(response.data);
                    if (callback) {
                        (callback)(data);
                    }
                }, function (reason) {
                    for (var k in reason.data.errors) {
                        logger.logError(reason.data.errors[k]);
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
            check: function (field, name, object_field, zero) {
                zero = zero || false;
                object_field = object_field || false;
                if (object_field && typeof(field.$viewValue) == 'object') {
                    if (field.$viewValue[object_field] == '0') {
                        logger.logError(':name is required', {'name': name});
                        return false;
                    }
                }

                if (field.$valid) {
                    if ((field.$$element["0"].localName == 'select' || zero) && field.$viewValue == '0') {
                        logger.logError('Choose :name first', {'name': name});
                        return false;
                    } else {
                        return true;
                    }
                } else {
                    if (field.$viewValue == '' || field.$viewValue == undefined) {
                        logger.logError(':name is required', {'name': name});
                    } else {
                        logger.logError(':name is incorrect', {'name': name});
                    }
                    return false;
                }
            },
            phone: function (field, name) {
                if (field.$valid) {
                    var val = this.phoneToNumber(field.$viewValue);
                    if (val.toString().match(/^[0-9]+$/)) {
                        if (val.length == 10) {
                            if (val.charAt(0) == '0' || val.charAt(0) == '1') {
                                logger.logError(':name can\'t start from 0 or 1', { 'name': name });
                            } else {
                                return true;
                            }
                        } else {
                            logger.logError(':name must contain 10 digits', {'name': name});
                        }
                    } else {
                        logger.logError(':name must contain only digits', {'name': name});
                    }
                } else {
                    if (field.$viewValue == '' || field.$viewValue == undefined) {
                        logger.logError(':name is required', {'name': name});
                    } else {
                        logger.logError(':name is incorrect', {'name': name});
                    }
                    return false;
                }
            },
            phoneToNumber: function(view_phone) {
                return $.trim(view_phone.replace(/\./gi, '').replace(/-/gi, '').replace(/ /gi, '').replace(/\(\)/gi, ''));
            }
        };
    };
})();

;

(function () {
    'use strict';

    angular.module('app').factory('getShortUrl',[getShortUrl]);

    function getShortUrl(){
        return {
            getLink: function(longUrl, func) {
                $.getJSON(
                    "http://api.bitly.com/v3/shorten?callback=?", 
                    { 
                        "format": "json",
                        "apiKey": 'R_ac165a693c4d43ab87337e0264f74263',
                        "login": "vbaychura",
                        "longUrl": longUrl
                    },
                    function(response)
                    {
                         func(response.data.url);
                    }
                );
            } 
        };
    };
    
})();

;