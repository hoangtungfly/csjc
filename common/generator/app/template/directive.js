app.directive('template11Ang', ['$rootScope', '$http', '$timeout', function ($rootScope, $http, $timeout) {
        return {
            restrict: 'E',
            templateUrl: LINK_PUBLIC + 'partials/main/template11.html',
            replace: true,
            link: function (scope, elem, attrs) {
                
            }
        };
    }]);