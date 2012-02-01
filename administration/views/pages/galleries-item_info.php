<div style="width:500px;height:200px">
  <span>Absolutní odkaz:</span> <?=$item->file()?><br />
  <span>Relativní odkaz:</span> <?=$item->file(TRUE)?><br />
  <span>Directiva:</span> {img:<?=$item->id?>}
  
</div>