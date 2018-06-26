<div class="cart">
    <div class="page-title title-buttons">
        <h1>Yêu thích</h1>
    </div>
    <div class="overflow-table">
        <table id="shopping-cart-table" class="data-table cart-table">
            <thead>
                <tr class="first last">
                    <th rowspan="1">&nbsp;</th>
                    <th rowspan="1"><span class="nobr">Tên sản phẩm</span></th>
                    <th class="a-center" colspan="1">Giá</th>
                    <th rowspan="1" class="a-center">Số lượng</th>
                    <th rowspan="1" class="a-center">&nbsp;</th>
                </tr>
            </thead>
            <tfoot>
                <tr class="first last">
                    <td colspan="50" class="a-right last">
                        <a href="/" title="Về trang chủ" style="margin-top:7px;" class="button btn-continue">Về trang chủ</a>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <?php if(isset($Wishlists) && $Wishlists) { 
                    foreach ($Wishlists as $key => $product) { ?>
                    <tr class="first last odd">
                        <td>
                            <a href="<?= $product['link_main'] ?>" title="<?= $product['name'] ?>" class="product-image">
                                <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" />
                            </a>
                        </td>
                        <td>
                            <h2 class="product-name">
                                <a href="<?= $product['link_main'] ?>" title="<?= $product['name'] ?>"><?= $product['name'] ?></a>
                            </h2>
                        </td>
                        <td class="a-center" style="text-align: center;">
                            <?= $product['price_str'] ?>
                        </td>
                        <td class="a-center">
                            <input data-id="<?= $product['product_id'] ?>" type="text" id="soluong<?= $product['product_id'] ?>" class="input-text qty countwishlist" value="1" />
                            <button data-id="<?= $product['product_id'] ?>" type="button" title="Đặt hàng" class="button btn-cart settings_add_to_cart">Đặt hàng</button>
                        </td>
                        <td class="a-center last">
                            <a data-id="<?= $product['product_id'] ?>" class="btn-remove btn-remove2 deletewishlistdetail">Xóa</a>
                        </td>
                    </tr>
                <?php } 
                
                } else { ?>
                    <tr class="first last odd">
                        <td colspan="5">
                            Không có một sản phầm nào trong yêu thích của bạn
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>