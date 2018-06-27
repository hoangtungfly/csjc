<?php

$post = r()->post();
$get = r()->get();
$config = $this->context->array_config();
$logoh1 = is_main() ? 'h1' : 'h2';
?>
<header>
    <div class="container">
        <div class="logo">
            <a href="/" title="<?= $config['seoh1'] ?>" >
                <img src="<?= $config['logo'] ?>" alt="<?= $config['seoh1'] ?>" />
            </a>
        </div>
        <?= $this->render('menu') ?>
    </div>
</header>