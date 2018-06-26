<?php

use common\core\form\GlobalActiveForm;
use common\models\admin\SettingsMessageSearch;

$params = [];
if(!$model->isNewRecord) {
    $params['accountid'] = $model->accountid;
}

$form = GlobalActiveForm::begin([
            'id' => 'facebook-addedit-form',
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'validateOnChange' => false,
            'validateOnSubmit' => false,
            'validateOnBlur' => false,
            'action' => $this->createUrl('user/updatefacebookproccess', ['id' => $model->id]),
            'fieldConfig' => [
                'template' => "<div class=\"form-group\">{label}{input}{error}</div>",
                'options' => [
                    'class' => 'item-input'
                ],
                'labelOptions' => [
                    'class' => 'title-input'
                ]
            ],
            'options' => [
                'class' => 'form-add-acc'
            ]
        ]);
echo $form->field($model, 'accountid')->textInput(['class' => 'form-control'])->label( SettingsMessageSearch::t('profile','facebook_account_id_title','Account ID: '));
echo $form->field($model, 'email')->textInput(['class' => 'form-control'])->label( SettingsMessageSearch::t('profile','facebook_email_title','Email: '));
echo $form->field($model, 'fullname')->textInput(['class' => 'form-control'])->label( SettingsMessageSearch::t('profile','facebook_fullname_title','Fullname: '));
?>
<div class="item-input">
    <div class="form-group div-submit">
        <button type="button" class="btn btn-default btn-submit bg-gradient" data-dismiss="modal"><?=SettingsMessageSearch::t('profile','facebook_button_cancel_title','Cancel')?></button>
        <button type="submit" class="btn btn-danger btn-submit"><?=SettingsMessageSearch::t('profile','facebook_button_submit_add_title','Save')?></button>
    </div>
</div>

<?php GlobalActiveForm::end(); ?>


<script type="text/javascript">
    $('body').on('submit','#facebook-addedit-form',function(e){
        resetForm();
        MainAjax({
            url         : $(this).attr('action'),
            data        : $(this).serialize(),
            success     : function(rs) {
                if(rs.code == 200) {
                    notif({
                        msg     : '<?=SettingsMessageSearch::t('profile','facebook_msg_edit_success','Change account facebook successfully!')?>',
                    });
                    $(document).closeAllPopup();
                    loadAjaxGridView(window.location.href);
                } else {
                    checkDataYii2(rs);
                }
            }
        });
        return false;
    })
</script>