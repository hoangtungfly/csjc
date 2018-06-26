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
            <a href="<?= $this->createUrl('/') ?>">Home</a>
        </li>
        <?php
        $count = count($this->context->breadcrumbs);
        if ($count > 0) {
            $dem = 1;
            foreach ($this->context->breadcrumbs as $key => $item) {
                echo '<li ' . (($dem == $count) ? 'active' : '') . '>';
                echo '<a href="' . $item->linkMenu() . '">' .Yii::t("admin",$item->name) . '</a>';
                echo '</li>';
                $dem++;
            }
        }
        ?>
    </ul>
</div>