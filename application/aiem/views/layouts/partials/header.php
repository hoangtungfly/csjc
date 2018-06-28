<?php

$post = r()->post();
$get = r()->get();
$config = $this->context->array_config();
$logoh1 = is_main() ? 'h1' : 'h2';
?>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/" title="<?= $config['seoh1'] ?>">
                <img class="logo" src="<?= $config['logo'] ?>" alt="<?= $config['seoh1'] ?>">
            </a>
        </div>
        <?= $this->render('menu') ?>
    </div>
</nav>