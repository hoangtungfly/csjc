<?php

use common\models\product\ColorSearch;
use common\models\settings\SystemSettingSearch;
use common\utilities\UtilityUrl;
use yii\helpers\ArrayHelper;
$list_color = ArrayHelper::map(ColorSearch::getAll(), 'id', 'cl');
if (UtilityUrl::isMobile() && r()->isAjax) {
    ?>
    <script type="text/javascript">
        if (DEVICE == 1) {
            window.location.href = "<?= $this->createUrl('productcompare/index') ?>";
        }
    </script>
<?php } ?>
<div class="popgiohang-boc" style="display: block;">
    <div class="popgiohang-an"></div>
    <div class="popgiohang">
        <div class="popgiohang_main">
            <div class="popgiohang-tat"></div>
            <h2 class="chuden44" style="padding-left:0px;">
                So sánh
                <span class="cochu15" style="font-weight:normal;">
                    (đang có <span id="soluonggiohanghientai"><?= $product_compare_count ?></span> sản phẩm)
                </span>
            </h2>
            <div class="banvuathem">
                <span class="iconbanvuathem iconnho anhchinh"></span>
                <?php
                if (isset($product_name) && $product_name) {
                    switch (app()->controller->action->id) {
                        case 'add':
                            echo 'Bạn vừa thêm ' . $product_name . ' vào so sánh';
                            break;
                        case 'delete':
                            echo 'Bạn vừa xóa ' . $product_name . ' khỏi so sánh';
                            break;
                    }
                }
                ?>

            </div>
            <div class="product_compare_wrap">
                <table border="1" id="product_compare_table" class="product_compare_table">
                    <tbody>
                        <tr>
                            <td class="product_compare_first">Sản phẩm</td>
                            <?php foreach ($ProductCompares as $key => $product) { ?>
                                <td>
                                    <a class="product_compare_name" href="<?= $product['link_main'] ?>" title="<?= $product['name'] ?>"><?= $product['name'] ?></a>
                                    <div class="fr cpointer deleteproductcompare anhchinh" data-id="<?= $product['product_id'] ?>"></div>
                                </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td class="product_compare_first">Hình ảnh</td>
                            <?php foreach ($ProductCompares as $key => $product) { ?>
                                <td>
                                    <a class="product-image" href="<?= $product['link_main'] ?>" title="<?= $product['name'] ?>">
                                        <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" width="125" height="125" />
                                    </a>
                                </td>
                            <?php } ?>
                        </tr>
<!--                        <tr>
                            <td class="product_compare_first">Đơn giá</td>
                            <?php foreach ($ProductCompares as $key => $product) { ?>
                                <td>
                                    <div class="price-box">
                                        <p class="old-price">
                                            <span class="price-label">Regular Price:</span>
                                            <span class="price"><?= $product['price_old_str'] ?></span>
                                        </p>
                                        <p class="special-price">
                                            <span class="price-label">Special Price</span>
                                            <span class="price"><?= $product['price_str'] ?></span>
                                        </p>
                                    </div>
                                </td>
                            <?php } ?>
                        </tr>-->
<!--                        <tr>
                            <td class="product_compare_first">Đánh giá</td>
                            <?php foreach ($ProductCompares as $key => $product) { ?>
                                <td>
                                    <div class="ratings">
                                        <div class="rating-box">
                                            <div class="rating" style="width:100%"></div>
                                        </div>
                                    </div>
                                </td>
                            <?php } ?>
                        </tr>-->
                        <tr>
                            <td class="product_compare_first">Mã sản phẩm</td>
                            <?php foreach ($ProductCompares as $key => $product) { ?>
                                <td><?= $product['code'] ?></td>
                            <?php } ?>
                        </tr>
<!--                        <tr>
                            <td class="product_compare_first">Thương hiệu</td>
                            <?php foreach ($ProductCompares as $key => $product) { ?>
                                <td><?= $product['manufacturer'] ?></td>
                            <?php } ?>
                        </tr>-->
                        <tr>
                            <td class="product_compare_first">Màu sắc</td>
                            <?php foreach ($ProductCompares as $key => $product) { 
                                $color = $product['color_old'] ? explode(',', $product['color_old']) : [];
                                ?>
                                <td>
                                    <?php foreach($color as $key22 => $vl) { ?>
                                    <span class="khoimau" style="background-color: <?=isset($list_color[$vl]) ? strtolower($list_color[$vl]) : ''?>;"></span>
                                    <?php } ?>
                                </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td class="product_compare_first">Size</td>
                            <?php foreach ($ProductCompares as $key => $product) { 
                                $size = $product['size'] ? explode(',', $product['size']) : [];
                                ?>
                            <td>
                                <?php if(count($size)) { ?>
                                    <?php foreach($size as $size_id => $size_cl) { ?>
                                    <span class="khoimau"><?=$size_cl?></span>
                                    <?php } ?>
                                <?php } ?>
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td class="product_compare_first">Tình trạng</td>
                            <?php foreach ($ProductCompares as $key => $product) { ?>
                                <td>
                                    <strong  class="<?= $product['status_product'] ? 'conhang' : 'hethang' ?>"></strong>
                                </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td class="product_compare_first">Mô tả tóm tắt</td>
                            <?php foreach ($ProductCompares as $key => $product) { ?>
                                <td>
                                    <?= nl2br($product['description']) ?>
                                </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td class="product_compare_first"></td>
                            <?php foreach ($ProductCompares as $key => $product) { ?>
                                <td>
                                    <p><button type="button" data-id="<?= $product['product_id'] ?>" title="Đặt hàng" class="button btn-cart settings_add_to_cart">Đặt hàng</button></p>
                                    <p><button type="button" data-id="<?= $product['product_id'] ?>" title="Đặt hàng" class="button btn-cart settings_add_to_wishlist">Thêm vào yêu thích</button></p>
                                </td>
                            <?php } ?>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div colspan="<?= $product_compare_count + 1 ?>" class="popgiohang-footer">
                <div class="clear"></div>
                <div class="tieptuc-thanhtoan">
                    <div class="tieptucmuahang"><a href="/">&lt; Tiếp tục mua hàng</a></div>
                    <div class="hotromuahang "><span class="iconhtgt iconnho anhchinh"></span>Hỗ trợ mua hàng: <span style="padding-left:25px;"> <?= SystemSettingSearch::getValue('hotline') ?></span></div>
                    <div class="fr">
                        <a href="<?= $this->createUrl('productcompare/index') ?>" class="product_compare_view" title="Yêu thích">Xem chi tiết ></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">resize_popup();</script>