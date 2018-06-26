<?php

use common\models\settings\SystemSettingSearch;

 if (r()->isAjax) { ?>
    <script type="text/javascript">
        if (DEVICE == 1) {
            window.location.href = "<?= $this->createUrl('cart/index') ?>";
        }
    </script>
<?php } ?>
<div class="popgiohang-boc" style="display: block;">
    <div class="popgiohang-an"></div>
    <div class="popgiohang">
        <div class="popgiohang-tat"></div>
        <h2 class="chuden44">
            GIỎ HÀNG CỦA TÔI
            <span class="icongiohang iconnho anhchinh"></span>
            <span class="cochu15" style="font-weight:normal;">
                (đang có <span id="soluonggiohanghientai"><?= $cart_count ?></span> sản phẩm)
            </span>
        </h2>
        <?php if (app()->controller->action->id != 'viewno') { ?>
            <div class="banvuathem">
                <span class="iconbanvuathem iconnho anhchinh"></span>
                <?php
                switch (app()->controller->action->id) {
                    case 'view':
                    case 'add':
                        echo 'Bạn vừa thêm ' . $product_name . ' vào giỏ hàng';
                        break;
                    case 'delete':
                        echo 'Bạn vừa xóa ' . $product_name . ' khỏi giỏ hàng';
                        break;
                    case 'change':
                        echo 'Bạn vừa sửa ' . $product_name . ' trong giỏ hàng';
                        break;
                }
                ?>

            </div>
            <?php }
        ?>
        <table>
            <thead class="cart-head-tr">
                <tr><td>Tên sản phẩm</td><td>Giá</td><td>Số lượng</td><td>Thành tiền</td></tr>
            </thead>
            <tbody>
                <?php $totalPrice = 0; ?>
                <?php
                foreach ($Carts as $key => $product) {
                    echo $this->render('temcart', array('product' => $product, 'total' => $total));
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="popgiohang-footer">
                        <div class="tongtien"><p class="cart-total-price">Tổng tiền: <span class="chudam"><?= $total ?></span></p></div>
                        <div class="clear"></div>
                        <div class="tieptuc-thanhtoan">
                            <div class="tieptucmuahang"><a href="/" title="<?=  SystemSettingSearch::getValue('web_name')?>">&lt; Tiếp tục mua hàng</a></div>
                            <div class="hotromuahang "><span class="iconhtgt iconnho anhchinh"></span>Hỗ trợ mua hàng: <span style="padding-left:25px;"> <?=  SystemSettingSearch::getValue('hotline')?></span></div>
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
<script type="text/javascript">
    $(document).ready(function (e) {
//        jsDefault();
        if ($('#countproduct').length > 0)
            $('#countproduct').html($('#soluonggiohanghientai').html());
    });
</script>