<?php

namespace common\core\enums;
use common\core\enums\base\GlobalEnumBase;

class TrackingEnum extends GlobalEnumBase{
    const WEBSITE_TRACKING = '<script type="text/javascript"> 
        var mtxJsHost = (("https:" == document.location.protocol) ? "https://" : "http://");
        document.write(unescape("%3Cscript src=\'" + mtxJsHost + "www.metrixa.com/ConvTrackNew/Scripts/metrixa_search_tracker.js\' type=\'text/javascript\'%3E%3C/script%3E"));
    </script>
    <script type="text/javascript">
        try {
            var tracker = new Tracker(\'MTX-__site_id__\');
            tracker.request();
        } catch (err) { }
    </script>';
}
