<tr>
    <td></td>
</tr>

<td>
        <img style="width:30px;height:30px;" class="D_tooltip" src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" />
        <span><?= $product['name'] ?></span>
    </td>
    <td>
        <?= $product['price_str'] ?>
    </td>
    <td><a data-id="<?= $product['product_id'] ?>" class="settings_add_to_cart wihslist_add_cart" href="javascript:void(0);">Đặt hàng</a></td>
    <td class="cochu15 chudam chuden44">
        <div class="mtop20">
            <div class="fr cpointer deleteproductcompare anhchinh" data-id="<?= $product['product_id'] ?>"></div>
        </div>
    </td>