<?php

use common\models\category\CategoriesSearch;

$config = $this->context->array_config();
$menufooter = CategoriesSearch::FooterMenu();
$alias = $this->getParam('alias');
?>

<div class="clear"></div>
<div class="foot">
    <div class="foot-bg">
        <div>
            <div class="wrap">
                <div class="foot-top">
                <div class="foot-nav">
                    <h3>Li&ecirc;n hệ</h3>
                    <span> </span>
                    <div class="content-contact">
                       <p><span><strong>Trụ sở ch&iacute;nh:</strong></span></p>
                       <p><span>P.411 To&agrave; nh&agrave; TOYOTA Mỹ Đ&igrave;nh, 15 Phạm H&ugrave;ng, Nam Từ Li&ecirc;m, H&agrave; Nội.</span></p>
                       <p style="white-space:nowrap;">Tel: (+84) 243.7957.717</p>
                       <p style="white-space:nowrap;">Fax: (+84-4).3795.7716</p>
                       <p><strong>Chi nh&aacute;nh ph&iacute;a Nam:</strong></p>
                       <p>P.108, Tầng 10, Th&aacute;p B To&agrave; nh&agrave; S&agrave;i G&ograve;n Paragon, Số 3 Nguyễn Lương Bằng, Quận 7, Tp. HCM</p>
                       <span> </span>
                       <p><span>Tel: (+84) 285.4111.991</span></p>
                       <span> </span>
                       <p><span><strong>Email</strong>: cjsc@cjsc.vn<br />
                          <strong>Hotline</strong>: (+84) 983.384.888</span><span></span>
                       </p>
                       <span> </span>
                    </div>
                 </div>
                    <?php if($menufooter) { ?>
                        <div class="foot-nav">
                            <?php foreach($menufooter as $key => $menu) { ?>
                                <a href="<?= $menu['alias'] ?>" class="<?= $menu['alias'] == $alias ? 'active' : '' ?>" title="<?= $menu['name'] ?>"><?= $menu['name'] ?></a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php if(isset($config['copyright'])):?>
    <div class="wrap">
        <div class="foot-bottom">
           <div class="left">
              <p><?= $config['copyright'] ?></p>
           </div>
           <div class="right">
              <ul>
                 <li><a href="#"><span class="fa fa-facebook"></span> </a></li>
                 <li><a href="#"><span class="fa fa-twitter"></span> </a></li>
                 <li><a href="#"><span class="fa fa-google-plus"></span> </a></li>
              </ul>
           </div>
           <div class="clear"></div>
        </div>
     </div>
    <?php endif;?>
</div>