(function () {
    'use strict';

    angular.module('app').controller('PlansCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', PlansCtrl]);

    function PlansCtrl($rootScope, $scope, $uibModal, request, langs) {
        $scope.request_finish = false;
        $scope.plan_name = '';
        $scope.list = [];
        $scope.stripe = {};
        $scope.showCardDetails = true;

        $scope.init = function () {
            $scope.get();
        };
        
        $scope.initPlanPage = function () {
            $scope.getPlanInfo();
            $scope.planDetailsPage();
        };

        $scope.get = function () {
        	request.send('/plans', {}, function (data) {
                $scope.list = data;
    			$scope.request_finish = true;
			}, 'get');
        };
        
        $scope.getPlanInfo = function () {
        	request.send('/plans/get', {}, function (data) {
                $scope.request_finish = true;
                if (data.stripe_id) {
                    $scope.stripe = data;
                    $scope.showCardDetails = false;
                } else {
                    $scope.plan_name = data.plan_name;
                    $scope.stripe = {};
                    $scope.showCardDetails = true;
                }
			}, 'get');
        };

        $scope.create = function (plans_id) {
            plans_id = plans_id || 0;
            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'ModalPlansCreate.html',
                controller: 'ModalPlansCreateCtrl',
                resolve: {
                    items: function () {
                        return {'plan': $scope.by_id(plans_id)};
                    }
                }
            });

            modalInstance.result.then(function (response) {
               $scope.get();
            }, function () {
                
            });
        };

        $scope.remove = function (plans_id) {
            if (confirm(langs.get('Do you realy want to remove this Plan?'))) {
                request.send('/plans/' + plans_id, {}, function (data) {
                    $scope.get();
                }, 'delete');
            }
        };

        $scope.by_id = function (plans_id) {
            for (var k in $scope.list) {
                if ($scope.list[k].id == plans_id) {
                    return $scope.list[k];
                }
            }

            return {};
        };

        $scope.planDetailsPage = function() {
            var stripe = Stripe('pk_live_qfYiDhjIK1fw6XPECmbLafr2');
            var elements = stripe.elements();

            var style = {
                base: {
                    color: '#32325d',
                    lineHeight: '18px',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            };

            var card = elements.create('card', {style: style});
            card.mount('#card-element');

            card.addEventListener('change', function (event) {
                var displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            var form = document.getElementById('payment-form');
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                stripe.createToken(card).then(function (result) {
                    if (result.error) {
                        var errorElement = document.getElementById('card-errors');
                        errorElement.textContent = result.error.message;
                    } else {
                        $scope.request_finish = false;
                        $scope.showCardDetails = false;
                        $scope.subscribe(result.token);
                    }
                });
            });
        };

        $scope.subscribe = function(token) {
            request.send('/plans/subscribe', {'token': token.id}, function (data) {
                $scope.getPlanInfo();
            }, ($scope.stripe.stripe_id ? 'put' : 'post'));
        };
        
        $scope.cancelSubscription = function() {
            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'ModalCancelPlansConfirm.html',
                controller: 'ModalConfirmCancelPlanCtrl',
                resolve: {
                    items: $scope.stripe,
                }
            });

            modalInstance.result.then(function (response) {
                $scope.request_finish = false;
                if (response) {
                    $scope.getPlanInfo();
                }
            }, function () {

            });
            
        };

        $scope.reactivate = function() {
            $scope.request_finish = false;
            request.send('/plans/reactivate', $scope.stripe, function (data) {
                $scope.request_finish = true;
                $scope.getPlanInfo();
            }, 'post');
        };
    };
})();

;

(function () {
    'use strict';

    angular.module('app').controller('ModalPlansCreateCtrl', ['$rootScope', '$scope', '$uibModalInstance', 'request', 'validate', 'logger', 'langs', 'items', ModalPlansCreateCtrl]);

    function ModalPlansCreateCtrl($rootScope, $scope, $uibModalInstance, request, validate, logger, langs, items) {
        $scope.plan = angular.copy(items.plan);
        if ( ! $scope.plan.id) {
            $scope.plan.interval = 'month';
        }

        $scope.save = function () {
            var error = 1;
            error *= validate.check($scope.form.name, 'Name');
            error *= validate.check($scope.form.amount, 'Amount');

            if (error) {
                request.send('/plans/' + ($scope.plan.id ? $scope.plan.id : 'save'), $scope.plan, function (data) {
                    $uibModalInstance.close(data);
                }, ($scope.plan.id ? 'post' : 'put'));
            }
        };

        $scope.cancel = function () {
            $uibModalInstance.dismiss();
        };
    };
})();

;

(function () {
    'use strict';

    angular.module('app').controller('ModalConfirmCancelPlanCtrl', ['$rootScope', '$scope', '$uibModalInstance', '$window', 'request', 'items', ModalConfirmCancelPlanCtrl]);

    function ModalConfirmCancelPlanCtrl($rootScope, $scope, $uibModalInstance, $window, request, items) {
        $scope.plan = {};
        $scope.plan = items;
        $scope.request_finish = true;
        
        $scope.unsubscribe = function () {
            $scope.request_finish = false;
            request.send('/plans/unsubscribe', $scope.plan, function (data) {
                $scope.request_finish = true;
                $window.location.href = '/';
            }, 'post');
        };
        
        $scope.downgrade = function () {
            $scope.request_finish = false;
            request.send('/plans/free', $scope.plan, function (data) {
                $scope.request_finish = true;
                $uibModalInstance.close('true');
            }, 'post');
        };
        
        $scope.cancel = function () {
            $uibModalInstance.dismiss();
        };
    };
})();

;