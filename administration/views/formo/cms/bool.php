<?=$open; ?>
	<label<?if ($id = $this->attr('id')) echo ' for="'.$id.'"';?>><?=$this->label();?></label>
	<?=$this->html();?>
<?=$close; ?>