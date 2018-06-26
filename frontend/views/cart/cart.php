<?php

use common\models\settings\SystemSettingSearch;
use common\utilities\UtilityUrl;

if (UtilityUrl::isMobile() && r()->isAjax) {
    ?>
    <script type="text/javascript">
        if (DEVICE == 1) {
            window.location.href = "<?= $this->createUrl('cart/index') ?>";
        }
    </script>
<?php } ?>
<div class="popgiohang-boc" style="display: block;">
    <div class="popgiohang-an"></div>
    <div class="popgiohang">
        <div class="popgiohang_main">
            <div class="popgiohang-tat"></div>
            <h2 class="chuden44">
                GIỎ HÀNG CỦA TÔI
                <span class="icongiohang iconnho anhchinh"></span>
                <span class="cochu15" style="font-weight:normal;">
                    (đang có <span id="soluonggiohanghientai"><?= $cart_count ?></span> sản phẩm)
                </span>
            </h2>
            <div class="banvuathem">
                <span class="iconbanvuathem iconnho anhchinh"></span>
                <?php
                if (isset($product_name)) {
                    switch (app()->controller->action->id) {
                        case 'add':
                        case 'view':
                            echo 'Bạn vừa thêm ' . $product_name . ' vào giỏ hàng';
                            break;
                        case 'delete':
                            echo 'Bạn vừa xóa ' . $product_name . ' khỏi giỏ hàng';
                            break;
                        case 'edit':
                            echo 'Bạn vừa sửa ' . $product_name . ' trong giỏ hàng';
                            break;
                    }
                }
                ?>

            </div>
            <table>
                <thead class="cart-head-tr">
                    <tr><td>Tên sản phẩm</td><td>Giá</td><td>Số lượng</td><td>Thành tiền</td></tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($Carts) && $Carts && is_array($Carts)) {
                        foreach ($Carts as $key => $product) {
                            echo $this->render('temcart', array('product' => $product));
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="popgiohang-footer">
                            <div class="tongtien"><p class="cart-total-price">Tổng tiền: <span class="chudam"><?= $total ?></span></p></div>
                            <div class="clear"></div>
                            <div class="tieptuc-thanhtoan">
                                <div class="tieptucmuahang"><a href="/">&lt; Tiếp tục mua hàng</a></div>
                                <div class="hotromuahang "><span class="iconhtgt iconnho anhchinh"></span>Hỗ trợ mua hàng: <span style="padding-left:25px;"> <?= SystemSettingSearch::getValue('hotline') ?></span></div>
                                <div class="fr">
                                    <a href="<?= $this->createUrl('cart/index') ?>" class="iconpopdang anhchinh" title="Giỏ hàng"></a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    if (jQuery('#cart_count').length > 0) {
        jQuery('#cart_count').html(jQuery('#soluonggiohanghientai').html());
    }
    resize_popup();
</script>