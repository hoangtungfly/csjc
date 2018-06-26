<?php

use common\core\form\GlobalActiveForm;
use common\models\admin\SettingsMessageSearch;
use common\models\lib\LibCountriesSearch;
$list_country = ['' => 'Select country'] + LibCountriesSearch::getAllDropDown();
$form = GlobalActiveForm::begin([
            'id' => 'information-edit-form',
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'validateOnChange' => false,
            'validateOnSubmit' => false,
            'validateOnBlur' => false,
            'action' => $this->createUrl('user/updateuserproccess',['id' => $model->userid]),
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
                'class' => 'form-add-acc',
            ]
        ]);
echo $form->field($model, 'firstname')->textInput(['class' => 'form-control'])->label( SettingsMessageSearch::t('profile','information_firstname_title','First Name: '));
echo $form->field($model, 'email')->textInput(['class' => 'form-control'])->label( SettingsMessageSearch::t('profile','information_email_title','Email: '));
echo $form->field($model, 'name')->textInput(['class' => 'form-control'])->label( SettingsMessageSearch::t('profile','information_name_title','Name: '));
echo $form->field($model, 'businessname')->textInput(['class' => 'form-control'])->label( SettingsMessageSearch::t('profile','information_businessname_title','Business Name: '));
echo $form->field($model, 'contactphone')->textInput(['class' => 'form-control'])->label(SettingsMessageSearch::t('profile','information_phone_title','Phone: '));
echo $form->field($model, 'address')->textarea(['class' => 'form-control'])->label(SettingsMessageSearch::t('profile','information_address_title','Address: '));
echo $form->field($model, 'countrycode')->dropDownList($list_country,['class' => 'setting_chosen chosen-select','style' => 'width:100%;'])->label(SettingsMessageSearch::t('profile','information_country_title','Country: '));

?>
<div class="item-input">
    <div class="form-group div-submit">
        <button type="button" class="btn btn-default btn-submit bg-gradient" data-dismiss="modal"><?=SettingsMessageSearch::t('profile','information_button_cancel_title','Cancel')?></button>
        <button type="submit" class="btn btn-danger btn-submit"><?=SettingsMessageSearch::t('profile','information_button_submit_title','Save')?></button>
    </div>
</div>

<?php GlobalActiveForm::end(); ?>

<script type="text/javascript">
    setTimeout(function(){
        resetAjax();
    },200);
    $('body').on('submit','#information-edit-form',function(e){
        resetForm();
        MainAjax({
            url         : $(this).attr('action'),
            data        : $(this).serialize(),
            success     : function(rs) {
                if(rs.code == 200) {
                    notif({
                        msg     : '<?=SettingsMessageSearch::t('profile','information_edit_success','Edit information successfully!')?>',
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