<button id="taositemap" class="btn btn-success">Tạo sitemap</button>
<script type="text/javascript">
    $(document).ready(function(e){
        $('#taositemap').click(function(e){
            loadingFull({type:'thoigian'});
            $.ajax({
                url         : '<?=$this->createUrl("main/processsitemap")?>',
                type        : 'POST',
                dataType    : 'text',
                success     : function(rs) {
                    $(document).LoPopUp({
                        title       : 'Sitemap',
                        contentHtml : rs,
                    });
                    notif({
                        msg : '<?=Yii::t("admin","Tạo sitemap thành công")?>',
                    });
                },
            });
        });
    });
</script>