<?php
$field_options['class'] = 'setting_map';
$name = $modelField->field_name;
echo $form->field($model,$name)->hiddenInput($field_options);
$className = explode("\\",get_class($model));
$className = strtolower($className[count($className) - 1]);
$value = $model->$name;
$positionX = "21.003007756558173";
$positionY = "105.80680393856198";
$zoom1 = "14";
$address = "Royal city, Thanh Xuân, Hà Nội";
$mapinfo = '';
if($value != ''){
    $array = explode('||',$value);
    $zoom1 = $array[0];
    $positionX = $array[1];
    $positionY = $array[2];
    $address = $array[3];
    $mapinfo = $array[4];
}
?>
<input type="text" id="ToadoX" value="<?=$positionX?>" style="display: none;"/>
<input type="text" id="ToadoY" value="<?=$positionY?>" style="display: none;"/>
<input type="text" id="zoom1" value="<?=$zoom1?>" style="display: none;"/>
<script type="text/javascript">
    $(document).ready(function(e){
        map_initialize();
        google.maps.event.addDomListener(window,'dblclick',copyText1);
    });
    function copyText(){
        document.getElementById("ToadoX").value=map.getCenter().lat();
        document.getElementById("ToadoY").value=map.getCenter().lng();
        document.getElementById("zoom1").value=map.getZoom();
        document.getElementById("<?= strtolower($className . '-' . $name) ?>").value= document.getElementById("zoom1").value + "||" 
                + document.getElementById("ToadoX").value + "||" 
                + document.getElementById("ToadoY").value + "||" 
                + document.getElementById("address").value + "||"
                + document.getElementById("mapinfo").value;
    }
    function copyText1(){
        copyText();
        map_initialize();
    }
</script>
<div id="setting_showmap" class="col-sm-12">
    <div id="panel" class="col-sm-12" style="padding-right:2px;">
        <label class="col-sm-3">location search</label>
        <input id="address" type="text" value="<?=$address?>" autocomplete="off" class="col-sm-6"/>
        <input type="button" value="Tìm vị trí của bạn" onclick="codeAddress()" class="col-sm-2 btn btn-danger" style="border:0px;height:28px;float: right;" />
        <textarea class="col-sm-12" style="margin:10px 0px;" id="mapinfo"><?=$mapinfo?></textarea>
    </div>
    <div id="map-canvas" style="height: 400px;width:100%;margin: 0px;padding: 0px;"></div>
</div>
<?php if($modelForm->hidden == 0) { ?>
<script type="text/javascript">
    var panel = $('#setting_showmap').closest('.panel-collapse.collapse');
    $(document).ready(function(e){
       panel.prev().find('a').click(function(e){
           $('#setting_showmap').removeAttr('style');
           panel.append($('#setting_showmap'));
       });
    });
    $('#setting_showmap').css({position:'fixed',top:'-10000px',width:panel.width()});
    $('body').append($('#setting_showmap'));
</script>
<?php } ?>