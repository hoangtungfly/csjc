<!DOCTYPE html>
<html lang="vi" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#">
    <head>
        <title><?= $title ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <h1><?= $title ?></h1>
        <div><?= $content ?></div>
        <script type="text/javascript">
            var flag = false;
            (function() {
                window.onmousemove = function() {
                    if (flag) {
                        window.close();
                    }
                }
                var beforePrint = function() {
                    console.log('Functionality to run before printing.');
                };
                var afterPrint = function() {
                    flag = true;
                };

                if (window.matchMedia) {
                    var mediaQueryList = window.matchMedia('print');
                    mediaQueryList.addListener(function(mql) {
                        if (mql.matches) {
                            beforePrint();
                        } else {
                            afterPrint();
                        }
                    });
                }

                window.onbeforeprint = beforePrint;
                window.onafterprint = afterPrint;
            }());
            window.print();</script>
    </body>
</html>
