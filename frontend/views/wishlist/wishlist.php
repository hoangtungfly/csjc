<?php

use common\models\settings\SystemSettingSearch;
use common\utilities\UtilityUrl;

if (UtilityUrl::isMobile() && r()->isAjax) {
    ?>
    <script type="text/javascript">
        if (DEVICE == 1) {
            window.location.href = "<?= $this->createUrl('wishlist/index') ?>";
        }
    </script>
<?php } ?>
<div class="popgiohang-boc" style="display: block;">
    <div class="popgiohang-an"></div>
    <div class="popgiohang">
        <div class="popgiohang_main">
            <div class="popgiohang-tat"></div>
            <h2 class="chuden44" style="padding-left:0px;">
                Yêu thích
                <span class="cochu15" style="font-weight:normal;">
                    (đang có <span id="soluonggiohanghientai"><?= $wishlist_count ?></span> sản phẩm)
                </span>
            </h2>
            <div class="banvuathem">
                <span class="iconbanvuathem iconnho anhchinh"></span>
                <?php
                switch (app()->controller->action->id) {
                    case 'add':
                        echo 'Bạn vừa thêm ' . $product_name . ' vào yêu thích';
                        break;
                    case 'delete':
                        echo 'Bạn vừa xóa ' . $product_name . ' khỏi yêu thích';
                        break;
                }
                ?>

            </div>
            <table>
                <thead class="wishlist-head-tr">
                    <tr>
                        <td>Tên sản phẩm</td>
                        <td>Giá</td>
                        <td></td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($Wishlists as $key => $product) {
                        echo $this->render('temwishlist', array('product' => $product));
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="popgiohang-footer">
                            <div class="clear"></div>
                            <div class="tieptuc-thanhtoan">
                                <div class="tieptucmuahang"><a href="/">&lt; Tiếp tục mua hàng</a></div>
                                <div class="hotromuahang "><span class="iconhtgt iconnho anhchinh"></span>Hỗ trợ mua hàng: <span style="padding-left:25px;"> <?= SystemSettingSearch::getValue('hotline') ?></span></div>
                                <div class="fr">
                                    <a href="<?= $this->createUrl('wishlist/index') ?>" class="wishlist_view" title="Yêu thích">Xem chi tiết ></a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">resize_popup();</script>