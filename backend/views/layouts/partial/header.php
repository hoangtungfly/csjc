<?php
use common\models\user\UserModel;
$user = user()->identity;
?>
<div id="navbar" class="navbar navbar-default    navbar-collapse       h-navbar">
    <script type="text/javascript">
            try{ace.settings.check('navbar' , 'fixed')}catch(e){}
    </script>
    <div class="navbar-container" id="navbar-container">
        <div class="navbar-header pull-left">
            <a href="<?=$this->context->homeUrl?>" class="navbar-brand">
                <small>
                        Admin System
                </small>
            </a>
        </div>
        <div class="navbar-buttons navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav">
                <li class="light-blue">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <span class="user-info"><small><?=Yii::t('admin','Welcome')?>,</small><?=$user->email?></span>
                        <i class="ace-icon fa fa-caret-down"></i>
                    </a>

                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        <li>
                            <a href="<?=$this->createUrl('/settings/user/update',array('id'=>user()->id))?>">
                                <i class="ace-icon fa fa-user"></i>
                                <?=Yii::t('admin','Profile')?>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="<?=$this->createUrl('/site/logout/')?>">
                                <i class="ace-icon fa fa-power-off"></i>
                                <?=Yii::t('admin','Logout')?>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        
    </div><!-- /.navbar-container -->
</div>














