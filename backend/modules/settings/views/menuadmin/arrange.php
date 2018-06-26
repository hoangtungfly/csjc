<div class="dd" id="nestable">
    <ol class="dd-list">
        <?php if($listArrange && isset($listArrange[0])) { 
            foreach($listArrange[0] as $key=>$arrange){ $id = $arrange->id;  ?>
            <li class="dd-item" data-id="<?=$id?>" <?=$arrange->pid != 0 ? 'data-pid="'.$arrange->pid.'"' : '' ?>>
                    <div class="dd-handle">
                            <?php echo $arrange->name; ?>
                    </div>
                
                    <?php if(isset($listArrange[$id])) { ?>
                    <ol class="dd-list">
                        <?php foreach($listArrange[$id] as $key2=>$arrange2){ $id = $arrange2->id; ?>
                        <li class="dd-item" data-id="<?=$id?>">
                                <div class="dd-handle">
                                        <?=$arrange2->name?>
                                </div>
                                <?php if(isset($listArrange[$id])) { ?>
                                <ol class="dd-list">
                                    <?php foreach($listArrange[$id] as $key3=>$arrange3){ $id = $arrange3->id; ?>
                                    <li class="dd-item item-orange" data-id="<?=$id?>">
                                            <div class="dd-handle"> <?=$arrange3->name?> </div>
                                            <?php if(isset($listArrange[$id])) { ?>
                                            <ol class="dd-list">
                                                <?php foreach($listArrange[$id] as $key4=>$arrange4){ $id = $arrange4->id; ?>
                                                <li class="dd-item item-orange" data-id="<?=$id?>">
                                                        <div class="dd-handle"> <?=$arrange4->name?> </div>
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
                MainAjax({
                    url     : '<?=$this->createUrl('arrangesuccess')?>',
                    data    : {update:str,pid: pid},
                    type    : 'POST',
                    dataType: 'json',
                    success : function(){
                        notif({
                            type: "success",
                            msg: "Arrange menuadmin successfully!",
                            position: "bottom",
                            fade: true,
                            timeout: 5000,
                        });
                    },
                });
            },
        });
        setTimeout(function(){
            $('*[data-action="collapse"]').each(function(){
                $(this).click();
                console.log($(this));
            });
        },500);
        $('.dd-handle a').on('mousedown', function(e){
                e.stopPropagation();
        });
//        $('[data-rel="tooltip"]').tooltip();
    });
</script>
