<?php
    $html = $this->render('form',  array(
        'model'         => $model,
        'table_id'      => $this->context->table_id,
        'modelTable'    => $this->context->setting_table,
        'idform'        => 'D_form_create',
        'action'        => '',
        'primaryKey'    => $model->getKey(),
        'multi_add'     => 1,
    ));
    $html = preg_replace('/(<form[^>]+>)|(<\/form>)/','',$html);
    $html = '<div class="div_multi_create_record">'.str_replace('[','[key_count_html][',$html).'</div>';
?>
<form id="content_multi_add" action="<?=$this->createUrl('multiadd')?>">
<input type="hidden" id="table_id" value="<?= $this->context->table_id ?>" />
<input type="hidden" id="tmp" value="<?= $this->context->setting_table->table_name ?>" />
<input type="hidden" id="did" value="<?=$model->isNewRecord ? '0' : $model->{$model->getKey()}?>" />

<input type="hidden" name="imageid" id="imageid" value="" />
<input type="hidden" name="imageiddelete" id="imageiddelete" value="" />
<input type="hidden" name="imageiddeletename" id="imageiddeletename" value="" />

<input type="hidden" name="fileid" id="fileid" value="" />
<input type="hidden" name="fileiddelete" id="fileiddelete" value="" />
<input type="hidden" name="fileiddeletename" id="fileiddeletename" value="" />

<script type="text/template" id="template_multi_add">
    <?=$html?>
</script>
<?php echo str_replace('key_count_html',0,$html); ?>
<button type="button" class="btn btn-primary fr bnone" id="add_create_multi_record" style="margin-right:500px;">Add</button>
<button type="submit" class="btn btn-success fr bnone" id="submit_create_multi_record" style="margin-right: 10px;">Submit</button>
</form>