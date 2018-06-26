<?php
    $cart = new Cart;
    $cart->price = $totalPrice;
    if (!user()->isGuest) {
        $user = User::model()->findByPk(user()->id);
        $cart->customer_name = $user->name;
        $cart->customer_email = $user->email;
        $cart->customer_phone = $user->phone;
        $cart->customer_address = $user->address;
    }
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'cart-form',
        'action' => $this->createUrl('cart/addcart'),
        'htmlOptions' => array(
            'class' => 'form-horizontal',
            'role' => 'role',
        ),
        'enableClientValidation' => false,
        'enableAjaxValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => false,
            'afterValidate' => 'js:function(form,data,hasError){
                        if(!hasError){
                            $("#cart-detail-content").html("<span style=\"color:red;font-size:13px;\">Mua hàng thành công.<br> Chúng tôi sẽ trả lời bạn trong vòng 24h tới.<br> Cảm ơn quý khách đã mua hàng của chúng tôi.</span>");
                            $("body,html").animate({scrollTop: $("#D_content").offset().top - 100},500);
                        }
                    }',
        ),
    ));
    ?>
    <div class="col-sm-12 plr0 mt10">
        <div class="col-sm-6 pl0">
            <label>Họ và tên</label>
            <?php
            echo $form->hiddenField($cart, 'price');
            echo $form->textField($cart, 'customer_name', array('class' => 'form-control'));
            echo $form->error($cart, 'customer_name');
            ?>
            <div class="col-sm-12 mt10 plr0">
                <div class="col-sm-7 pl0">
                    <label>Email</label>
                    <?php
                    echo $form->textField($cart, 'customer_email', array('class' => 'form-control'));
                    echo $form->error($cart, 'customer_email');
                    ?>
                </div>
                <div class="col-sm-5 plr0">
                    <label>Số điện thoại</label>
                    <?php
                    echo $form->textField($cart, 'customer_phone', array('class' => 'form-control'));
                    echo $form->error($cart, 'customer_phone');
                    ?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 pr0">
            <label>Địa chỉ</label>
            <?php
            echo $form->textArea($cart, 'customer_address', array('class' => 'form-control', 'style' => 'height:100px;'));
            echo $form->error($cart, 'customer_address');
            ?>
        </div>
    </div>
    <div class="col-sm-12 plr0" id="contact-submit-div" style="margin-top:10px;margin-bottom:20px; ">
        <button type="submit" class="btn btn-primary contact-submit">gửi phản hổi</button>
    </div>
    <?php $this->endWidget(); ?>