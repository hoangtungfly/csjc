'use strict';

var app = angular.module('app', [
    'ngRoute', //$routeProvider
    'mgcrea.ngStrap', //bs-navbar, data-match-route directives
    'controllers', //Our module frontend/web/js/controllers.js
    'ngSanitize',
    'ui.bootstrap',
]);
app.config(['$routeProvider', '$httpProvider', '$locationProvider',
    function($routeProvider, $httpProvider, $locationProvider) {
        $routeProvider.
                when(ALIAS + '/', {
                    templateUrl: LINK_PUBLIC + 'partials/main/index.html',
                    controller: 'IndexController'}).
                whenroutereplace.
                when(ALIAS + ':alias.rss', {
                    templateUrl: LINK_PUBLIC + 'partials/main/index.html',
                    controller: function() {
                        window.location.href = window.location.href;
                    },
                }).otherwise({
                    templateUrl: LINK_PUBLIC + 'partials/main/404.html',
                    controller: 'ErrorController',
                });
        $httpProvider.interceptors.push('authInterceptor');
        $locationProvider.html5Mode(true);
    }
]);
app.run(['$location', '$rootScope', '$http', function($location, $rootScope, $http) {

        $http.get(LINK_PUBLIC + 'partials/json/config.json').success(function(data) {
            $rootScope = angular.extend($rootScope, data);
            $rootScope.LINK_PUBLIC = LINK_PUBLIC;
            $rootScope.search = 'tìm kiếm';
        });
        $rootScope.$on('$routeChangeSuccess', function(event, current, previous) {
            $('html,body').animate({scrollTop: 0}, 0);
            $rootScope.curl = window.location.href;
            if (current.hasOwnProperty('$$route')) {
                $rootScope.current_location = $location.path();
            }
        });
    }]);
app.factory('authInterceptor', function($q, $window, $location) {
    return {
        request: function(config) {
            if ($window.sessionStorage.access_token) {
                //HttpBearerAuth
                config.headers.Authorization = 'Bearer ' + $window.sessionStorage.access_token;
            }
            return config;
        },
        responseError: function(rejection) {
            if (rejection.status === 401) {
                $location.path('/login').replace();
            }
            return $q.reject(rejection);
        }
    };
});