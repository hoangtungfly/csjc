<tr class="cart-body-tr">
    <td>
        <img style="width:30px;height:30px;" class="D_tooltip" src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" />
        <span><?= $product['name'] ?></span>
    </td>
    <td>
        <?= $product['price_str'] ?>
    </td>
    <td>
        <input data-id="<?= $product['product_id'] ?>" type="text" style="height:25px;width:40px;"  id="soluong<?= $product['product_id'] ?>" class="form-control isnumberint soluonggiohang" data-value="<?= $product['count'] ?>" value="<?= $product['count'] ?>">
    </td>
    <td class="cochu15 chudam chuden44" id="thanhtien<?= $product['product_id'] ?>">
        <div class="mtop20">
            <?= $product['total_str'] ?>
            <div class="fr cpointer nutxoagiohang anhchinh" data-id="<?= $product['product_id'] ?>"></div>
        </div>
    </td>
</tr>