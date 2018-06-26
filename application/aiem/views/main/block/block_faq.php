<?php

use common\utilities\UtilityHtmlFormat;
$curl = $this->createUrl('category',['alias' => $model->alias]);
$item['faq'] = (array)$item['faq'];
?>
<div class="block block-faq">
    <div class="container">
        <div class="block-faq-header">
            <h1><?= $item['title'] ?></h1>
            <div>
                <?php if(count($item['faq'])) {  ?>
                <?php foreach($item['faq'] as $k => $v) {
                    $v = (array)$v;
                    if(!isset($v['title'])) {
                        break;
                    }
                    
                $v['alias'] = UtilityHtmlFormat::stripUnicode($v['title'],''); ?>
                <a href="<?=$curl?>#<?= $v['alias'] ?>" class="<?= !$k ? 'active' : '' ?>"><?= $v['title'] ?></a>
                <?php $item['faq'][$k] = $v;} ?>
                <?php } ?>
            </div>
        </div>
        <?php if(count($item['faq'])) {  ?>
        <?php foreach($item['faq'] as $k => $md) { 
            if(!isset($v['title'])) break;
            $md['faq'] = json_decode($md['faq'],true); 
        ?>
        <div class="block-faq-content " style="display: block;" id="<?= $md['alias'] ?>">
            <div class="block-faq-left">
                <?php if($md['faq'])  {
                    foreach($md['faq'] as $kleft => $vleft) { 
                $vleft = (array)$vleft;
                $vleft['alias'] = UtilityHtmlFormat::stripUnicode($vleft['title'],'');
                ?>
                <a href="<?=$curl?>#<?=$md['alias']?>-<?= $vleft['alias'] ?>" class="<?= !$kleft ? 'active' : '' ?>"><?=$vleft['title']?></a>
                <?php $md['faq'][$kleft] = $vleft; ?>
                <?php } 
                    } ?>
            </div>
            <div class="block-faq-right">
                <?php 
                if($md['faq'])  {
                    foreach($md['faq'] as $kleft => $vleft) {
                        $vleft['left'] = json_decode($vleft['left'],true); 
                ?>
                <div class="div-faq-block " <?= !$kleft ? 'style="display: block;"' : '' ?> id="<?=$md['alias']?>-<?= $vleft['alias'] ?>">
                    <?php foreach($vleft['left'] as $k4 => $question_answer) { ?>
                    <div class="div-faq-block-container">
                        <div class="div-faq-block-header">
                            <a href="javascript:void(0);"><?= $question_answer['question'] ?></a>
                        </div>
                        <div class="div-faq-content-content">
                            <?= $question_answer['answer'] ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <?php }
                } ?>
            </div>
        </div>
        <?php } ?>
        <?php } ?>
    </div>
</div>