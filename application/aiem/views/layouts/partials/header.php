<?php

$post = r()->post();
$get = r()->get();
$config = $this->context->array_config();
$logoh1 = is_main() ? 'h1' : 'h2';
?>
<header class="navbar navbar-default navbar-fixed-top style1  header-with-container">
    <div class="nav-container  container">
        <div class="navbar-header">
            <a href="/" title="<?= $config['seoh1'] ?>" class="navbar-brand nav-to">
                <!--<img src="<?= $config['logo'] ?>" alt="<?= $config['seoh1'] ?>" />-->
                <img class="logo_normal notalone" style="position: relative;" src="images/nanopay-logo2x.png" alt="" title="">
                <img class="logo_retina" style="display:none; position: relative;" src="images/nanopay-logo2x.png" alt="" title="">
                <img class="logo_retina logo_after_scroll" style="display:none; position: relative;" src="images/nanopay-logo2x.png" alt="" title="">
            </a>
        </div>
        
        <div class="maple_right_header_icons ">
            <div class="header_social_icons with-social-icons">
               <div class="header_social_icons_wrapper">
                  <div class="social_container linkedin_container" onclick="window.open('https://www.linkedin.com/company/5183090/', '_blank');">
                     <i class="fa fa-linkedin"></i>
                  </div>
                  <div class="social_container twitter_container" onclick="window.open('https://twitter.com/nano_pay/', '_blank');">
                     <i class="fa fa-twitter"></i>
                  </div>
                  <div class="social_container facebook_container" onclick="window.open('https://www.facebook.com/nanopayco/', '_blank');">
                     <i class="fa fa-facebook"></i>
                  </div>
               </div>
            </div>
        </div>
        
        <div id="dl-menu" class="dl-menuwrapper">
            <div class="dl-trigger-wrapper">
                <button class="dl-trigger"></button>
            </div>
            <ul id="menu-main-nav" class="dl-menu">
                <li id="mobile-nav-menu-item-1066" class="main-menu-item  menu-item-even menu-item-depth-0 menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children custom-1066"><a href="#" class="menu-link main-menu-link">Solutions</a><span class="gosubmenu fa fa-angle-right"></span>
                    <ul class="dropdown-menu menu-odd  menu-depth-1 dl-submenu-smart"><li class="dl-back"><a href="#">back</a></li>
                        <li id="mobile-nav-menu-item-322"><a href="https://nanopay.net/solutions/cross-border-payments/" class="menu-link sub-menu-link outsider outsider">Cross-Border</a></li>
                        <li id="mobile-nav-menu-item-353"><a href="https://nanopay.net/solutions/b2b-payments/" class="menu-link sub-menu-link outsider outsider">B2B Payments</a></li>
                        <li id="mobile-nav-menu-item-380"><a href="https://nanopay.net/solutions/digital-cash/" class="menu-link sub-menu-link outsider outsider">Digital Cash</a></li>
                        <li id="mobile-nav-menu-item-352"><a href="https://nanopay.net/solutions/capital-markets/" class="menu-link sub-menu-link outsider outsider">Capital Markets</a></li>
                    </ul>
                </li>
                <li id="mobile-nav-menu-item-1108" class="main-menu-item  menu-item-even menu-item-depth-0 menu-item menu-item-type-post_type menu-item-object-page page-98"><a href="https://nanopay.net/technology/" class="menu-link main-menu-link outsider">Technology</a></li>
                <li id="mobile-nav-menu-item-251" class="main-menu-item  menu-item-even menu-item-depth-0 menu-item menu-item-type-post_type menu-item-object-page page-227"><a href="https://nanopay.net/news/" class="menu-link main-menu-link outsider">News &amp; Video</a></li>
                <li id="mobile-nav-menu-item-1284" class="main-menu-item  menu-item-even menu-item-depth-0 menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children custom-1284"><a href="#" class="menu-link main-menu-link">About</a><span class="gosubmenu fa fa-angle-right"></span>
                <li id="mobile-nav-menu-item-104" class="main-menu-item  menu-item-even menu-item-depth-0 menu-item menu-item-type-post_type menu-item-object-page page-102"><a href="https://nanopay.net/blog/" class="menu-link main-menu-link outsider">Blog</a></li>
                <li id="mobile-nav-menu-item-107" class="main-menu-item  menu-item-even menu-item-depth-0 menu-item menu-item-type-post_type menu-item-object-page page-93"><a href="https://nanopay.net/contact/" class="menu-link main-menu-link outsider">Contact</a></li>
            </ul>
        </div>
        
        <div class="navbar-collapse collapse">
            <ul id="menu-main-nav-1" class="nav navbar-nav navbar-right">
                <li id="nav-menu-item-1066" class="main-menu-item  menu-item-even menu-item-depth-0 menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children custom-1066"><a href="#" class="menu-link main-menu-link">Solutions</a></li>
                <li id="nav-menu-item-1108" class="main-menu-item  menu-item-even menu-item-depth-0 menu-item menu-item-type-post_type menu-item-object-page page-98"><a href="https://nanopay.net/technology/" class="menu-link main-menu-link outsider">Technology</a></li>
                <li id="nav-menu-item-251" class="main-menu-item  menu-item-even menu-item-depth-0 menu-item menu-item-type-post_type menu-item-object-page page-227"><a href="https://nanopay.net/news/" class="menu-link main-menu-link outsider">News &amp; Video</a></li>
                <li id="nav-menu-item-1284" class="main-menu-item  menu-item-even menu-item-depth-0 menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children custom-1284"><a href="#" class="menu-link main-menu-link">About</a></li>
                <li id="nav-menu-item-104" class="main-menu-item  menu-item-even menu-item-depth-0 menu-item menu-item-type-post_type menu-item-object-page page-102"><a href="https://nanopay.net/blog/" class="menu-link main-menu-link outsider">Blog</a></li>
                <li id="nav-menu-item-107" class="main-menu-item  menu-item-even menu-item-depth-0 menu-item menu-item-type-post_type menu-item-object-page page-93"><a href="https://nanopay.net/contact/" class="menu-link main-menu-link outsider">Contact</a></li>
            </ul>
        </div>
    </div>
</header>