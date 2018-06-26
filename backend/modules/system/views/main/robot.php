<textarea id="robottext" class="form-control" style="width:800px;height:400px;"><?=$text?></textarea>
<div style="width:800px;text-align: center;">
    <button id="taositemap" class="btn btn-success">Save</button>
</div>
<script type="text/javascript">
    $(document).ready(function(e){
        $('body').on('click','#taositemap',function(e){
            loadingFull({type:'thoigian'});
            $.ajax({
                url         : '<?=$this->createUrl("main/processrobot")?>',
                type        : 'POST',
                dataType    : 'text',
                data        : {text:$('#robottext').val()},
                success     : function(rs) {
                    notif({
                        msg : '<?=Yii::t("admin","Save file robot thành công")?>',
                    });
                },
            });
        });
    });
</script>