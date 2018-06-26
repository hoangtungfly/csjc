controllers.controller('IndexController', ['$scope', '$location', '$window', '$http', '$route', '$timeout', '$sce', '$rootScope',
    function($scope, $location, $window, $http, $route, $timeout, $sce, $rootScope) {
        $rootScope.category = {};
        $http.get(LINKJSON + 'indexjson').success(function(rs) {
            $scope = $.extend($scope, rs);
            $rootScope.breadcrumbs = $scope.breadcrumbs;
            document.title = $rootScope.system_settings.meta_title;
        });
    }
]);