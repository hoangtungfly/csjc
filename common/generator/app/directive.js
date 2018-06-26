app.directive('headerAng', ['$rootScope', '$http', '$timeout', function ($rootScope, $http, $timeout) {
        return {
            restrict: 'E',
            templateUrl: LINK_PUBLIC + 'partials/main/header.html',
            replace: true,
            link: function (scope, elem, attrs) {
                    $http.get("http://ipinfo.io").success(function(rs) {
                        var data = $.extend({
                            table: 0,
                            table_id: 0,
                            pagelink: window.location.href,
                            pagelinkpre: document.referrer,
                            useronline_type: 0,
                        },rs);
                        $http.post('/rest/countaccess', data).success(function() {});
                    });
            }
        };
    }]);

app.directive('commentAng', ['$rootScope', '$http', '$timeout', function ($rootScope, $http, $timeout) {
        return {
            restrict: 'E',
            templateUrl: LINK_PUBLIC + 'partials/main/comment.html',
            replace: true,
            link: function (scope, elem, attrs) {
                $timeout(function(){
                    $('#sort').val(0);
                },1000);
                scope.CommentSearch = {sort:0};
                scope.commentlist = function() {
                    var a = setInterval(function(){
                        if($rootScope.table_name) {
                            $http.post('/rest/commentlist',{table_name:$rootScope.table_name,did:$rootScope.did,sort:$('#sort').val()}).success(function(rs){
                                scope.comments = rs;
                            });
                            clearInterval(a);
                        }
                    },100);
                    
                };
                scope.refreshCaptcha = function () {
                    $http.get('/site/captcha?refresh=1').success(function (data) {
                        scope.captchaUrl = data.url;
                    });
                };
                scope.like = function (id,key) {
                    $http.post('/rest/like',{id:id,status:1}).success(function (data) {
                        if(data.code == 200) {
                            scope.comments[key].like = parseInt(scope.comments[key].like) + 1;
                        } else {
                            notif({
                                type    : 'warning',
                                msg     : data.msg,
                            });
                        }
                    });
                };
                scope.dislike = function (id,key) {
                    $http.post('/rest/like',{id:id,status:0}).success(function (data) {
                        if(data.code == 200) {
                            scope.comments[key].dislike = parseInt(scope.comments[key].dislike) + 1;
                        } else {
                            notif({
                                type    : 'warning',
                                msg     : data.msg,
                            });
                        }
                    });
                };
                scope.comment_call = function(name) {
                    scope.CommentSearch.content = '@' + name;
                    $('#commentsearch-content').focus();
                };
                
                scope.commentlist();
                scope.refreshCaptcha();
                scope.comment = function () {
                    scope.submitted = true;
                    scope.error = {};
                    loadingFull();
                    scope.CommentSearch.did = $rootScope.did;
                    scope.CommentSearch.table_name = $rootScope.table_name;
                    $http.post('/rest/comment', scope.CommentSearch).success(
                            function (rs) {
                                if (rs.code == 400) {
                                    angular.forEach(rs.data, function (error) {
                                        scope.error[error.field] = error.message;
                                    });
                                } else if (rs.code == 200) {
                                    scope.commentlist();
                                    scope.refreshCaptcha();
                                    scope.CommentSearch = {sort:0};
                                }
                                $('.DD_loadingfull').remove();
                            }).error(
                            function (data) {
                                angular.forEach(data, function (error) {
                                    scope.error[error.field] = error.message;
                                });
                                $('.DD_loadingfull').remove();
                                ;
                            }

                    );
                };
            }
        };
    }]);

app.directive('ultityAng', ['$rootScope', '$http', '$timeout', '$window', '$uibModal', function ($rootScope, $http, $timeout, $window, $uibModal) {
        return {
            restrict: 'E',
            templateUrl: LINK_PUBLIC + 'partials/main/ultity.html',
            replace: true,
            link: function (scope, elem, attrs) {
                scope.sendmail = function (that) {
                    scope.modalInstance = $uibModal.open({
                        templateUrl: LINK_PUBLIC + 'partials/main/sendmail.html',
                        animation: true,
                        title: 'Send email',
                        size: 'lg',
                        controller: 'SendemailController',
                    });
                }
            }
        };
    }]);

app.directive('shareAng', ['$rootScope', '$http', '$timeout', '$window', function ($rootScope, $http, $timeout, $window) {
        return {
            restrict: 'E',
            templateUrl: LINK_PUBLIC + 'partials/main/share.html',
            replace: true,
            link: function (scope, elem, attrs) {
                if (!$window.FB) {
                    $.getScript('//connect.facebook.net/en_US/sdk.js', function () {
                        $window.FB.init({
                            appId: $rootScope.facebookAppId,
                            xfbml: true,
                            version: 'v2.0'
                        });
                        renderLikeButton();
                    });
                } else {
                    renderLikeButton();
                }
                function renderLikeButton() {
                    $('#script_share_facebook').html('<div class="fb-like" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>');
                    $window.FB.XFBML.parse($('#script_share_facebook').parent()[0]);
                }
                if (!$window.gapi) {
                    $.getScript('//apis.google.com/js/platform.js', function () {
                        renderPlusButton();
                    });
                } else {
                    renderPlusButton();
                }

                function renderPlusButton() {
                    $('#script_share_google').html('<div class="g-plusone" data-href="' + $rootScope.curl + '" data-size="medium"></div>');
                    $window.gapi.plusone.go($('#script_share_google').parent()[0]);
                }

            }
        };
    }]);
