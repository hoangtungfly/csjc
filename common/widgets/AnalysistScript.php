<?php

/**
 * @author Phong Pham Hong
 * 
 * Generate scripts for google analysist or metrixa
 */

namespace common\widgets;

use Yii;

class AnalysistScript extends \yii\base\Widget {

    public $key = '';
    public $config = array();

    const METRIXA_PORT = 'MTX-1032';
    const METRIXA_HOST = 'www.metrixa.com/ConvTrackNew/Scripts/metrixa_search_tracker.js';
    const PREFIX_TRANSACTION = 'lotsop_';

    /**
     * @inheritdoc
     * load all configs into a singeleton variable
     * @return type
     */
    public function init() {
        return parent::init();
    }

    /**
     * declare some key will be ignore 
     * @return type
     */
    protected function unrequireLogin() {
        return [
            'initjs'
        ];
    }

    /**
     * @inheritdoc
     * @return boolean
     */
    public function run() {
        if (YII_ENV != 'prod' || (app()->user->isGuest && !in_array($this->key, $this->unrequireLogin()))) {
            return false;
        }
        $this->loadConfig($this->key);
        switch ($this->key) {
            case 'initjs':
                echo $this->initJsfunction();
                break;
            case 'member_signup':
            case 'partner_signup':
                echo $this->showScriptSignup();
                break;
            case 'checkout':
                if (app()->getRequest()->isAjax) {
                    echo $this->showScriptCheckoutAjax();
                } else
                    echo $this->showScriptCheckout();
                break;
            case 'analytics':
                echo $this->showScriptAnalytics();
                break;
            case 'remarketing':
                echo $this->showScriptRemarketing();
                break;
            case 'metrixa_conversion':
                echo $this->showScriptMetrixaConversion();
                break;
            case 'metrixa_signup':
                echo $this->showScriptMetrixaSignup();
                break;
            default :
                return false;
        }
        return parent::run();
    }

    /**
     * load config
     * @param type $key
     */
    public function loadConfig($key) {
        if ($key) {
            $def = isset(app()->params['analysis_script'][$key]) ? app()->params['analysis_script'][$key] : array();
            $this->config = array_merge($def, $this->config);
        }
    }

    /**
     * get config by key
     * @param type $key
     * @return type
     */
    public function getConfig($key) {
        $value = isset($this->config[$key]) ? $this->config[$key] : '';
        # some sepencial key
        if ($key == 'transaction_id') {
            $value = self::PREFIX_TRANSACTION . $value;
        }
        return $value;
    }

    public function showScriptSignup() {
        $string = '<!-- Google Code for Member signup Conversion Page -->
                        <script type="text/javascript">
                        /* <![CDATA[ */
                        var google_conversion_id = ' . $this->getConfig('google_conversion_id') . ';
                        var google_conversion_language = "' . $this->getConfig('google_conversion_language') . '";
                        var google_conversion_format = "' . $this->getConfig('google_conversion_format') . '";
                        var google_conversion_color = "' . $this->getConfig('google_conversion_color') . '";
                        var google_conversion_label = "' . $this->getConfig('google_conversion_label') . '";
                        var google_remarketing_only = ' . $this->getConfig('google_remarketing_only') . ';
                        /* ]]> */
                        </script>
                        <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
                        </script>
                        <noscript>
                        <div style="display:inline;">
                        <img height="1" width="1" style="border-style:none;" alt="" 
                            src="//www.googleadservices.com/pagead/conversion/' . $this->getConfig('google_conversion_id') . '/
                            ?label=' . $this->getConfig('google_conversion_label') . '&amp;guid=ON&amp;script=0"/>
                        </div>
                        </noscript>';
        return $string;
    }

    public function showScriptCheckout() {
        if (!$this->getConfig('no_converprice')) {
            $price_value = (float) $this->getConfig('total_price');
            $price_value = $price_value > 0 ? $price_value * 5 / 100 : 3.00;
            $this->config['google_conversion_value'] = $price_value;
        }
        return '<!-- Google Code for Payment Conversion Page -->
            <script type="text/javascript">
            /* <![CDATA[ */
            var google_conversion_id = ' . $this->getConfig('google_conversion_id') . ';
            var google_conversion_language = "' . $this->getConfig('google_conversion_language') . '";
            var google_conversion_format = "' . $this->getConfig('google_conversion_format') . '";
            var google_conversion_color = "' . $this->getConfig('google_conversion_color') . '";
            var google_conversion_label = "' . $this->getConfig('google_conversion_label') . '";
            var google_conversion_value = ' . $price_value . ';
            var google_conversion_currency = "' . $this->getConfig('google_conversion_currency') . '";
            var google_remarketing_only = ' . $this->getConfig('google_remarketing_only') . ';
            /* ]]> */
            </script>
            <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
            <noscript>
            <div style="display:inline;">
            <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/' . $this->getConfig('google_conversion_id') . '/?value=' . $this->getConfig('google_conversion_value') . '&currency_code=' . $this->getConfig('google_conversion_currency') .
                '&label=' . $this->getConfig('google_conversion_label') . '&amp;guid=ON&amp;script=0"/>
            </div>
            </noscript>
            ';
    }

    public function showScriptCheckoutAjax() {
        if (!$this->getConfig('no_converprice')) {
            $price_value = (float) $this->getConfig('total_price');
            $price_value = $price_value > 0 ? $price_value * 5 / 100 : 3.00;
            $this->config['google_conversion_value'] = $price_value;
        }
        return '<!-- Google Code for Payment Conversion Page -->
            <div style="display:inline;">
            <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/' . $this->getConfig('google_conversion_id') . '/?value=' . $price_value . '&currency_code=' . $this->getConfig('google_conversion_currency') .
                '&label=' . $this->getConfig('google_conversion_label') . '&amp;guid=ON&amp;script=0"/>
            </div>
            ';
    }

    public function showScriptAnalytics() {
        return "<script type='text/javascript'>
                ga('require', 'ecommerce');
                ga('ecommerce:addTransaction', {
                  'id': '{$this->getConfig('transaction_id')}',                     // Transaction ID. Required.
                  'affiliation': '{$this->getConfig('affiliation')}',   // Affiliation or store name.
                  'revenue': '{$this->getConfig('revenue')}',               // Grand Total.
                  'shipping': '{$this->getConfig('shipping')}',                  // Shipping.
                  'tax': '{$this->getConfig('tax')}'                     // Tax.
                });
                ga('ecommerce:addItem', {
                  'id': '{$this->getConfig('transaction_id')}',                     // Transaction ID. Required.
                  'name': '".  str_replace("'", "&DiacriticalGrave;", $this->getConfig('name'))."',    // Product name. Required.
                  'sku': '{$this->getConfig('sku')}',                 // SKU/code.
                  'category': '{$this->getConfig('category')}',         // Category or variation.
                  'price': '{$this->getConfig('price')}',                 // Unit price.
                  'quantity': '{$this->getConfig('quantity')}',                   // Quantity.
                  'currency': '{$this->getConfig('object_currency')}'
                });
                ga('ecommerce:send');
                </script>
                ";
    }

    public function showScriptRemarketing() {
        return '<script type="text/javascript">
                /* <![CDATA[ */
                var google_conversion_id = ' . $this->getConfig('google_conversion_id') . ';
                var google_custom_params = window.google_tag_params;
                var google_remarketing_only = ' . $this->getConfig('google_remarketing_only') . ';
                /* ]]> */
                </script>
                <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
                <noscript>
                <div style="display:inline;">
                <img height="0" width="0" style="border-style:none;display:none" alt=""
                src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/' . $this->getConfig('google_conversion_id') . '/?value=0&amp;guid=ON&amp;script=0"/>
                </div>
                </noscript>
                ';
    }

    public function showScriptMetrixaConversion() {
        $metrixaport = self::METRIXA_PORT;
        $price_value = (float) $this->getConfig('price');
        if (!$this->getConfig('no_converprice')) {
            $price_value = $price_value > 0 ? $price_value * 5 / 100 : 3.00;
        }
        return '<script type="text/javascript">
                    loadMetrixaTracker(function() {
                            try {
                               var tracker = new Tracker("' . $metrixaport . '");
                                   tracker.setLinkId("' . $this->getConfig('customer_id') . '");
                                   tracker.setTransName("' . $this->getConfig('transaction_name') . '");
                                   tracker.addItem("' . $this->getConfig('product_code') . '","' . $this->getConfig('product_name') . '","' . $this->getConfig('product_category') . '"
                                       ,"' . $price_value . '","' . $this->getConfig('quantity') . '","' . $this->getConfig('cost') . '"
                               );
                               tracker.request();             
                            } catch (err) { }
                            });
                            </script>
                            ';
    }

    public function showScriptMetrixaSignup() {
        $metrixaport = self::METRIXA_PORT;
        $metrixakey = $this->getConfig('metrixa_key') ? $this->getConfig('metrixa_key') : 'Registration';
        $customerid = $this->getConfig('customer_id') ? $this->getConfig('customer_id') : app()->user->id;
        $string = '<script type="text/javascript">
                loadMetrixaTracker(function() {
                    try {
                        var tracker = new Tracker("' . $metrixaport . '");
                        tracker.setGoal("' . $metrixakey . '");
                        tracker.setLinkId("' . $customerid . '");
                        tracker.request();
                    } catch (err) {
                    }
                 });
            </script>';
        $this->loadConfig('metrixa_conversion');
        $string .= $this->showScriptMetrixaConversion();
        return $string;
    }

    public function initJsfunction() {
        $metrixaport = self::METRIXA_PORT;

        $js = '<script type="text/javascript">' . "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-55628527-1', 'auto');
            ga('send', 'pageview');";
        $js .= '//loading metrixa
            function downloadJSAtOnload() {
                var arrayLink = [];
                var mtxJsHost = (("https:" == document.location.protocol) ? "https://" : "http://")
                arrayLink.push(mtxJsHost + "' . self::METRIXA_HOST . '");
                for (var k in arrayLink) {
                    var element = document.createElement("script");
                    element.src = arrayLink[k];
                    document.body.appendChild(element);
                }
            }

            if (window.addEventListener)
                window.addEventListener("load", downloadJSAtOnload, false);
            else if (window.attachEvent)
                window.attachEvent("onload", downloadJSAtOnload);
            else
                window.onload = downloadJSAtOnload;';
        $js .= "function loadMetrixaTracker(callback) {
                var timeoutTracker = 0;
                var trackerInterval = setInterval(function() {
                    if (typeof Tracker == 'function') {
                        callback();
                        clearInterval(trackerInterval);
                    }
                    if (timeoutTracker == 10000) {
                        clearInterval(trackerInterval);
                    }
                    timeoutTracker += 500;
                }, 500);
            }
            loadMetrixaTracker(function() {
                var tracker = new Tracker('" . $metrixaport . "');
                tracker.request();
            });</script>";
        return $js;
    }

}
