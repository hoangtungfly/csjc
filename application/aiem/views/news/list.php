<?php 
    use common\models\admin\SettingsMessageSearch;
?>

<div class="container company">
    <h1 class="text-center"><?=SettingsMessageSearch::t('news','new_list_title', 'OIBI News')?></h1>
    <h2 class="text-center"><?=SettingsMessageSearch::t('news','new_list_description', 'Updates from company')?></h2>
    <hr>
</div>
<?php 
    $len = count($listNews);
    if($len) {  
?>
<div class="container listNew">
    <div class="list">
        <?php for($i= 0 ; $i < $len/2; $i++):?>
        <div class="col-xs-6">
            <?php for($j = $i ; $j < $i+2;$j++) :?>
            <?php if(isset($listNews[$j]) && $listNews[$j]) : 
                $item = $listNews[$j];
            ?>
            <div class="item">
                <a href="<?=$item['link_main']?>"><img class="img-responsive" src="<?=$item['image_main']?>"></a>
                <div class="content">
                    <h4><a href="<?=$item['link_main']?>"><?=$item['name_display']?></a></h4>
                    <p><?=$item['description']?></p>
                    <a href="<?=$item['link_main']?>" class="general" style="color: #333"><?=SettingsMessageSearch::t('common','button_more', 'Read more')?></a>
                </div>
            </div>
            <?php endif;?>
            <?php endfor;?>
        </div>
        <?php endfor;?>
    </div>
    <div class="pagination_section">
        <div class="col-xs-12">
            <ul class="pagination">
                <li>
                    <a href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="active"><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
                <li>
                    <a href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<?php }?>