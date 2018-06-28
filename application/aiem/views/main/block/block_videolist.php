<?php 
    $list_data = $item['arraymanyjson'] ? $item['arraymanyjson'] : [];
?>

<?php if(count($list_data)):?>
<div class="container-fluid section_3">
    <div class="row">
        <div class="container">
            <div class="col-xs-12">
                <h4 class="text-center">Video List</h4>
            </div>
            <?php foreach($list_data as $data):?>
            <div class="col-xs-3">
                <h4 class="videoTitle"><?=isset($data->altimage) ? $data->altimage : ''?></h4>
                <video src="<?=isset($data->link)? $data->link : ''?>" poster="<?= isset($data->image) ? $data->image : ''?>" type="video/webm">
                    <p>Your browser does not support the video element.</p>
                </video>
            </div>
            <?php endforeach;?>
        </div>
    </div>
</div>
<?php endif;?>