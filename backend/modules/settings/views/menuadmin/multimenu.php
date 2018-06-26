<?php if($listMenu) { ?>
<ul class="multimenu">
<?php
foreach($listMenu as $k1=>$v1){
    $class = ($id == $k1) ? 'class="active" ' : '';
    echo '<li '.$class.' data-id="'.$k1.'">'.$v1.'</li>';
}
?>
</ul>
<?php } ?>