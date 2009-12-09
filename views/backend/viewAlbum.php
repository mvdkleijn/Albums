<script type="text/javascript">
	function dropdown(sel){ 
		c = confirm('You are about to change this albums publish status.\n\nAre you sure you want to do this?');
		if(c) { sel.form.submit(); } else { sel.selectedIndex = 0; } 
	} 
</script>

<h1>Viewing Album: <span class="editRegion" id="name"><?php echo $album[0]['name']; ?></span></h1>

<p>&laquo; <a href="<?php echo get_url('albums'); ?>">Back to albums</a></p>

<p>+ <a href="<?php echo get_url('albums/add/'.$album[0]['id'].''); ?>">Add images to this album</a></p>

<form action="<?php echo get_url('albums/changeAlbumPublishStatus'); ?>" method="post">
	<input type="hidden" name="album" value="<?php echo $album[0]['id']; ?>" />
	This album is 
	<select name="published" onchange="return dropdown(this)">
		<option value="yes"<?php if($album[0]['published'] == 'yes') { echo ' selected="selected"'; } ?>>published</option>
		<option value="no"<?php if($album[0]['published'] == 'no') { echo ' selected="selected"'; } ?>>not published</option>
	</select>
</form>

<p>&nbsp;</p>

<?php	if($album[0]['description'] == '') { ?>
<p><span class="editRegion" id="description">add a description</span></p>
<?php	} else {	?>
<p><span class="editRegion" id="description"><?php echo $album[0]['description']; ?></span></p>
<?php	} ?>
<?php if(count($images) != 0) { ?>
<p id="note"><strong>Note:</strong> Simply drag photos to rearrange them. The new order will be saved automatically.</p>
<div class="album-view">
	<ul id="images" class="images">
<?php
	foreach($images as $image) {
?>
		<li id="item_<?php echo $image['id']; ?>">
			<div id="imageInfo">
				<h2><?php echo $image['name']; ?></h2>
				<p>Added on <?php echo date('jS F Y', $image['timeAdded']); ?></p>
				<p><?php echo $image['description']; ?></p>
				<p><a class="imageLink" href="<?php echo get_url('albums/image/'.$image['id'].''); ?>">View / Edit this image</a></p>
			</div>
			<div id="imageWrapper">
				<img src="<?php echo Albums::urlToImage($image['id'], '150'); ?>" />
			</div>
		</li>
<?php
	}
?>
	</ul>
</div>
<?php } ?>

<p>+ <a href="<?php echo get_url('albums/add/'.$album[0]['id'].''); ?>">Add images to this album</a></p>

<script type="text/javascript">

	Sortable.create('images', {
		ghosting:false, constraint:false, hoverclass:'over', onChange:function(element) {
			var totElement = <?php echo count($images); ?>;
			var newOrder = Sortable.serialize(element.parentNode);
			for(i=1; i<=totElement; i++) {
				newOrder = newOrder.replace("images[]=","");
				newOrder = newOrder.replace("&",",");
			}
		  	var url = 'updateOrder/<?php echo $album[0]['id']; ?>o'+newOrder;
			new Ajax.Request(url, {method:'post'});
		}
	});

</script>