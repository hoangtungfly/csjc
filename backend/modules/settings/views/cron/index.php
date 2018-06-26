<iframe id="iframe" src="http://news.zing.vn/" style="width:100%;"></iframe>
<script type="text/javascript">
    $(document).ready(function(e){
        var obj = $('#iframe').contents().find('body');
        console.log(obj.html());
    })
</script>