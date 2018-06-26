<div class="dd" id="nestable">
    <ol class="dd-list">
        <?php if($listArrange && isset($listArrange[0])) { 
            foreach($listArrange[0] as $key=>$arrange){ $id = $arrange->$primaryKey;  ?>
            <li class="dd-item" data-id="<?=$id?>" <?=$arrange->pid != 0 ? 'data-pid="'.$arrange->pid.'"' : '' ?>>
                    <div class="dd-handle">
                            <?php echo $arrange->$label; ?>
                    </div>
                
                    <?php if(isset($listArrange[$id])) { ?>
                    <ol class="dd-list">
                        <?php foreach($listArrange[$id] as $key2=>$arrange2){ $id = $arrange2->$primaryKey; ?>
                        <li class="dd-item" data-id="<?=$id?>">
                                <div class="dd-handle">
                                        <?=$arrange2->$label?>
                                </div>
                                <?php if(isset($listArrange[$id])) { ?>
                                <ol class="dd-list">
                                    <?php foreach($listArrange[$id] as $key3=>$arrange3){ $id = $arrange3->$primaryKey; ?>
                                    <li class="dd-item item-orange" data-id="<?=$id?>">
                                            <div class="dd-handle"> <?=$arrange3->$label?> </div>
                                            <?php if(isset($listArrange[$id])) { ?>
                                            <ol class="dd-list">
                                                <?php foreach($listArrange[$id] as $key4=>$arrange4){ $id = $arrange4->$primaryKey; ?>
                                                <li class="dd-item item-orange" data-id="<?=$id?>">
                                                        <div class="dd-handle"> <?=$arrange4->$label?> </div>
                                                </li>
                                                <?php } ?>
                                            </ol>
                                            <?php } ?>
                                    </li>
                                    <?php } ?>
                                </ol>
                                <?php } ?>
                        </li>
                        <?php } ?>
                    </ol>
                
                    <?php } ?>
            </li>
        <?php } 
        
        } ?>
    </ol>
</div>
<script type="text/javascript">
    $(document).ready(function(e){
        $('.dd').nestable({
            stop : function($this){
                var str = '';
                $this.parent().children('li').each(function(i){
                    str +=  ((i != 0) ? '|' : '') + $(this).data('id') + ',' + i;
                });
                str.replace('|','');
                var pid = $this.parent().parent().data('id') ? $this.parent().parent().data('id') : 0;
                if(pid == 0 && $this.data('pid')) {
                    pid = $this.data('pid');
                }
                loadingFull();
                $.ajax({
                    url     : '<?=$url?>',
                    data    : {update:str,pid: pid},
                    type    : 'POST',
                    dataType: 'json',
                    success : function(){
                        notif({
                            type        : 'success',
                            position    : 'bottom',
                            msg         : 'Order successfully.'
                        });
                    },
                });
            },
        });
        setTimeout(function(){
            $('*[data-action="collapse"]').each(function(){
                $(this).click();
            });
        },500);
        $('.dd-handle a').on('mousedown', function(e){
                e.stopPropagation();
        });
    });
</script>
