<div class="cart">
    <div class="page-title title-buttons">
        <h1>Giỏ hàng</h1>
    </div>
    <div class="overflow-table">
        <table id="shopping-cart-table" class="data-table cart-table">
            <thead>
                <tr class="first last">
                    <th rowspan="1">&nbsp;</th>
                    <th rowspan="1"><span class="nobr">Tên sản phẩm</span></th>
                    <th class="a-center" colspan="1">Giá</th>
                    <th rowspan="1" class="a-center">Số lượng</th>
                    <th class="a-center" colspan="1">Thành tiền</th>
                    <th rowspan="1" class="a-center">&nbsp;</th>
                </tr>
            </thead>
            <tfoot>
                <tr class="first last">
                    <td colspan="50" class="a-right last">
                        <a href="/" title="Tiếp tục mua hàng" style="margin-top:7px;" class="button btn-continue">Tiếp tục mua hàng</a>
                        <div class="tongtien-details fr">Tổng tiền: <span class="chudam"><?= $total ?></span></div>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <?php 
                if(isset($Carts) && $Carts) {
                foreach ($Carts as $key => $product) { ?>
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
                            <input data-id="<?= $product['product_id'] ?>" type="text" id="soluong<?= $product['product_id'] ?>" class="input-text qty soluonggiohangdetail" value="<?= $product['count'] ?>" />
                        </td>
                        <td class="a-center" style="text-align: center;">
                            <?= $product['total_str'] ?>
                        </td>
                        <td class="a-center last">
                            <a data-id="<?= $product['product_id'] ?>" class="btn-remove btn-remove2 nutxoagiohangdetail">Xóa</a>
                        </td>
                    </tr>
                <?php } 
                
                } else { ?>
                    <tr class="first last odd">
                        <td colspan="6">
                            Không có một sản phầm nào trong giỏ hàng của bạn
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>