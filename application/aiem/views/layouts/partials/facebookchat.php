<div id="b-c-facebook" class="chat_f_vt">
    <div id="chat-f-b" onclick="b_f_chat()" class="chat-f-b">
        <img class="chat-logo" src="<?=LINK_PUBLIC?>img/facebook.png" alt="logo chat" />
        <label>
            CHAT NOW    		</label>
        <span id="fb_alert_num">
            1
        </span>
        <div id="t_f_chat">
            <a title="Close Chat" href="javascript:;" onclick="b_f_chat()" id="chat_f_close" class="chat-left-5"><img src="<?=LINK_PUBLIC?>img/close.png" alt="Close chat" title="Close chat" /></a>
        </div>
    </div>
    <div id="f-chat-conent" class="f-chat-conent">
        <div class="fb-page" data-tabs="messages" data-href="https://www.facebook.com/aiemtrangtri.com.vn/" data-width="250" data-height="310" data-small-header="true" data-adapt-container-width="true" data-hide-cover="true"
             data-show-facepile="false" data-show-posts="true">
        </div>
        <div id="fb_chat_start">
            <div id="f_enter_1" class="msg_b fb_hide">
                Xin chào! Cảm ơn bạn đã ghé thăm website. Hãy nhấn nút Bắt đầu để được trò chuyện với nhân viên hỗ trợ.    			
            </div>

            <p id="f_enter_3" class="fb_hide" align="center">
                <a href="javascript:;" onclick="f_bt_start_chat()" id="f_bt_start_chat">Bắt đầu</a>
            </p>

        </div>

    </div>
</div>
<div id="fb-root"></div>
<script>(function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id))
                    return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.5";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>