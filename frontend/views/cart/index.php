<div class="main-container col1-layout">
    <div class="main">
        <div class="col-main">
            <div class="container" style="clear:both;">
                <div class="row">
                    <div class="col-lg-12 col-md-12" id="settings_cartdetail">
                        <?=$this->render('cartdetail',$params)?>
                    </div>
                    <?php if(isset($Carts) && $Carts && is_array($Carts) && count($Carts)) { ?>
                    <?=$this->render('checkout')?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>





