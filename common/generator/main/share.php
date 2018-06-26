<div class="socialicon pull-right" style="margin-right: 10px;overflow: hidden;height: 26px;">
    <div style="width:60px;float:left;overflow:hidden;margin-top:-2px;margin-left:5px;height: 26px;" id="script_share_google">
    </div>
    <div id="script_share_facebook" style="float:left;margin-top:3px;width:78px;height: 22px;">
    </div>
    <div style="float:right;margin-left:10px;">
        <a title="Facebook share" class="share" onclick="return share_facebook();" style="cursor:pointer;">
            <img src="{{LINK_PUBLIC}}img/iconface.png" border="0" alt="Share Facebook" title="Share Facebook">
        </a>
        <a href="https://twitter.com/share" data-url="{{curl}}">
            <img src="{{LINK_PUBLIC}}img/iconttwiter.png" border="0" alt="Twitter" title="Twitter">
        </a>
        <a href="https://plus.google.com/share?url={{curl}}&amp;hl=vi" target="_blank" data-url="{{curl}}" data-size="tall" data-action-type="Share via Google+" data-actions="click" data-button-type="gplus" data-category="Sharing" data-label="{{curl}}" title="Share on Google+">
            <img src="{{LINK_PUBLIC}}img/icongoogle.png" alt="Google Plus" title="Google Plus" border="0">
        </a>
        <a href="//www.pinterest.com/pin/create/button/?url={{curl}}&amp;media={{og_image}}&amp;description={{meta_description}}" data-pin-do="buttonPin" data-pin-config="beside">
            <img src="{{LINK_PUBLIC}}img/iconpin.png" title="Pinterest" border="0" alt="Pinterest">
        </a>
    </div>
    <style>#script_share_google > div{margin-top:5px !important;}</style>
</div>