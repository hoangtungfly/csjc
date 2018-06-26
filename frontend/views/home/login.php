<?php

use common\core\form\GlobalActiveForm;
use common\models\user\LoginForm;

$model = new LoginForm();
?>
<h1>Login</h1>

<p>Please fill out the following fields to login:</p>

<div class="row">
    <div class="col-lg-5">
        <?php
            $form = GlobalActiveForm::begin([
                'id' => 'login-form',
                'enableClientValidation' => false,
                'enableAjaxValidation' => false,
                'validateOnChange' => false,
                'validateOnSubmit' => false,
                'validateOnBlur' => false,
                'angular'   => true,
                'hideAction'    => true,
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'options'   => [
                        'class'     => 'form-group',
                    ],
                    'labelOptions'  => [
                        'class' => 'control-label',
                    ],
                    'inputOptions'  => [
                        'class' => 'form-control',
                    ],
                    'errorOptions'  => [
                        'class' => 'help-block help-block-error',
                    ],
                ],
                'options'   => [
                    'role' => 'form',
                    'ng-submit' => 'login()',
                ],
            ]);
        ?>
        
        
        <?=$form->field($model, 'username')->textInput()?>

        
        <?=$form->field($model, 'password')->passwordInput()?>

            <div class="form-group">
                <button type="submit" class="btn btn-primary" name="login-button">Login</button>
            </div>

        <?php GlobalActiveForm::end(); ?>
    </div>
</div>
