<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$form = ActiveForm::begin([
        'id' => 'login-form-signin',
        'enableClientValidation' => false,
        'enableAjaxValidation' => true,
        'validateOnChange' => false,
        'validateOnSubmit' => true,
        'validateOnBlur' => false,
        'action' => $this->createUrl('/site/login',['urlb' => $urlb]),
        'fieldConfig' => [
            'template' => "",
            'options'=>[
                'class'     => ''
            ]
        ],
]);
?>
        <fieldset>
            <?=
            $form->field($model, 'username',[
                'template'=>'<label class="block clearfix">
                        <span class="block input-icon input-icon-right">
                                {input}
                                <i class="ace-icon fa fa-user"></i>
                        </span>{error}
                </label>',
                ])->textInput([
                'class' => "form-control", 'placeholder' => 'Email'
            ])->error();
            ?>
            
            <?=
            $form->field($model, 'password',[
                'template'=>'<label class="block clearfix">
                        <span class="block input-icon input-icon-right">
                                {input}
                                <i class="ace-icon fa fa-lock"></i>
                        </span>{error}
                </label>',
                ])->passwordInput([
                'class' => "form-control", 'placeholder' => 'Password'
            ])->error();
            ?>
            

                <div class="space"></div>
            
                <div class="clearfix">
                    <?=
                    $form->field($model, 'rememberMe',[
                        'template'=>'<label class="inline">
                                {input}
                                <span class="lbl"> Remember Me</span>
                        </label>',
                        'options'=> ['style'=> 'float: left']
                        ])->input('checkbox',[
                        'class' => "ace",
                        'onclick'   => '$(this).val($(this).prop("checked") ? 1 : 0)',
                    ])->error();
                    ?>

                    <button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
                            <i class="ace-icon fa fa-key"></i>
                            <span class="bigger-110">Login</span>
                    </button>
                </div>

                <div class="space-4"></div>
        </fieldset>
<?php
ActiveForm::end();
?>