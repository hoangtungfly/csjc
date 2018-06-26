<?php

use common\models\product\ColorSearch;
use yii\helpers\ArrayHelper;
$list_color = ArrayHelper::map(ColorSearch::getAll(), 'id', 'cl');
?>
<div class="cart">
    <div class="page-title title-buttons">
        <h1>So sánh</h1>
    </div>
    <div class="overflow-table">
        <table id="shopping-cart-table" class="data-table cart-table">
            <tfoot>
                <tr class="first last">
                    <td colspan="50" class="a-right last">
                        <a href="/" title="Về trang chủ" style="margin-top:7px;" class="button btn-continue">Về trang chủ</a>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <?php if (isset($ProductCompares) && $ProductCompares && count($ProductCompares)) { ?>


                    <tr>
                        <td class="product_compare_first">Sản phẩm</td>
                        <?php foreach ($ProductCompares as $key => $product) { ?>
                            <td>
                                <a class="product_compare_name" href="<?= $product['link_main'] ?>" title="<?= $product['name'] ?>"><?= $product['name'] ?></a>
                                <div class="fr cpointer deleteproductcomparedetail anhchinh" data-id="<?= $product['product_id'] ?>"></div>
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
<!--                    <tr>
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
                    <tr>
                        <td class="product_compare_first">Mã sản phẩm</td>
                        <?php foreach ($ProductCompares as $key => $product) { ?>
                            <td><?=$product['code']?></td>
                        <?php } ?>
                    </tr>
<!--                    <tr>
                        <td class="product_compare_first">Thương hiệu</td>
                        <?php foreach ($ProductCompares as $key => $product) { ?>
                            <td><?=$product['manufacturer']?></td>
                        <?php } ?>
                    </tr>-->
<!--                    <tr>
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


                <?php } else { ?>
                    <tr class="first last odd">
                        <td colspan="50">
                            Không có một sản phầm nào trong so sánh của bạn
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>