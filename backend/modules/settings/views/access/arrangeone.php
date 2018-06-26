<div class="dd" id="nestable">
    <ol class="dd-list">
        <?php foreach($listArrange as $key=>$arrange){ $id = $arrange->$primaryKey;  ?>
            <li class="dd-item" data-id="<?=$id?>">
                    <div class="dd-handle">
                            <?php echo $arrange->$label; ?>
                    </div>
            </li>
        <?php } ?>
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
                loadingFull();
                $.ajax({
                    url     : '<?=$url?>',
                    data    : {update:str,pid: pid},
                    type    : 'POST',
                    dataType: 'json',
                    success : function(){
                        notif({
                            type    : 'success',
                            msg     : 'Order successfully!',
                            position: 'bottom',
                        });
                    },
                });
            }
        });
        $('.dd-handle a').on('mousedown', function(e){
                e.stopPropagation();
        });
//        $('[data-rel="tooltip"]').tooltip();
    });
</script>
