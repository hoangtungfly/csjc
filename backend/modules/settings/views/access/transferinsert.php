<form action="<?=$this->createUrl('transferinsert')?>" method="POST">
    <div class="col-sm-12">
        <label class="col-sm-1">Table:</label>
        <div class="col-sm-11"><input type="text" class="form-control" name="table" /></div>
    </div>
    <div class="col-sm-12" style="margin:20px 0px;">
        <label class="col-sm-1">Text:</label>
        <div class="col-sm-11"><textarea class="form-control" name="text" style="height: 200px;"></textarea></div>
    </div>
    <div class="col-sm-12">
        <label class="col-sm-1"></label>
        <div class="col-sm-11"><input type="submit" class="btn btn-success" value="submit" style="border: none;" /></div>
    </div>
    <?php if($sql != "") { ?>
    <div class="col-sm-12">
        <label class="col-sm-1">Result:</label>
        <div class="col-sm-11"><?=$sql?></div>
    </div>
    <?php } ?>
</form>