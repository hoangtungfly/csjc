<div class="in-tab-section">
    <div class="nav nav-tabs" id="multi-section">
        <?php if(count($listForm) > 0) { ?>
            <?php foreach ($listForm as $key => $row) { 
                $urlGet = $this->createUrl('/settings/buildform/getform', array('id'=>$row['form_id']));
                $urlDelete = $this->createUrl('/settings/buildform/deleteform', array('id'=>$row['form_id']));
            ?>
                <div class="li <?php echo ($key == 0) ? 'active' : ''; ?>">
                    <a class="edit_item_new" data-form_id="<?= $row['form_id'] ?>" href="<?= $urlGet; ?>" data-toggle="tab">
                        Form <samp><?= $key+1 ?></samp> </a>
                    <i data-href="<?= $urlDelete; ?>" class="fa fa-times delete-item-down"></i>
                    <div><?=$row['form_name']?></div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
    <a href="javascript:void(0);" class="left-btn"><i class="fa fa-chevron-left"></i></a>
    <a href="javascript:void(0);" class="right-btn"><i class="fa fa-chevron-right"></i></a>
</div>