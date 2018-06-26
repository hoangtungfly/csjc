<ul class="menudacap">
<?php
foreach($data as $k1=>$v1){
    $class = ($id == $k1) ? 'class="active" ' : '';
    echo '<li '.$class.' data-mappingid="'.$mappingid.'" data-id="'.$k1.'">'.$v1.'</li>';
}
?>
</ul>