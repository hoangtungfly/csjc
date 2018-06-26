<div class="block block-content">
    <div class="container">
        <?php if ($item['title']) { ?>
        <h1 class="block-h1"><?= $item['title'] ?></h1>
        <?php } ?>
        <div class="block-content-container">
            <?= $item['content'] ?>
        </div>
    </div>
</div>