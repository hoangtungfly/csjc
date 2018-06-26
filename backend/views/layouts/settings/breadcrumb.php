<?php
$loadiframe = (int) $this->getParam('loadiframe');
if (!$loadiframe) {
    ?>
    <div class="breadcrumbs" id="breadcrumbs">
        <script type="text/javascript">
            try {
                ace.settings.check('breadcrumbs', 'fixed')
            } catch (e) {
            }
        </script>
        <ul class="breadcrumb">
            <li>
                <i class="icon-home home-icon"></i>
                <a href="/admin">Home</a>
            </li>
            <?php
            if (isset($this->context->breadcrumbs) && $this->context->breadcrumbs) {
                $count = count($this->context->breadcrumbs);
                if ($count > 0) {
                    $dem = 1;
                    foreach ($this->context->breadcrumbs as $key => $item) {
                        echo '<li ' . (($dem == $count) ? 'active' : '') . '>';
                        echo '<a class="breadcrumbsa" href="' . $item->linkMenu() . '">' . Yii::t("admin", $item->name) . '</a>';
                        echo '</li>';
                        $dem++;
                    }
                }
            }
            ?>
        </ul>
        <div class="fr">
            <?php if (user()->id == 1) { ?>
                <a id="buildgrid_link" href="<?= $this->createUrl('/settings/grid/index', array('SettingsGridSearch[table_id]' => $this->context->table_id)) ?>" class="btn btn-info bnone">Build Grid</a>
                <a id="buildform_link" href="<?= $this->createUrl('/settings/buildform/index', array('table_id' => $this->context->table_id)) ?>" class="btn btn-primary bnone">Build Form</a>
            <?php } ?>
                <a id="delete_cache" href="<?= $this->createUrl('/settings/access/deletecache') ?>" class="btn btn-warning bnone"><?=Yii::t('admin','Delete cache')?></a>
        </div>
    </div>
<?php } ?>