<script type="text/javascript">
	function dropdown(sel) {
		c = confirm('You are about to make a change to this album.\n\nAre you sure you want to do this?');
		if(c) { sel.form.submit(); } else { sel.selectedIndex = 0; }
	} 
</script>

<h1>Viewing Album</h1>

<div class="metaData">
	<p>&laquo; <a href="<?php echo get_url('albums/albums'); ?>">Back to albums</a></p>
</div>

<div class="metaData">
	<p><img src="<?php echo URL_PUBLIC . CORE_FOLDER .'/plugins/albums/images/add.png'; ?>" alt="+" align="top" height="16px" /> <a href="<?php echo get_url('albums/add/'.$album[0]['id'].''); ?>">Add images to this album</a></p>
</div>

<div class="clear"></div>

<div class="metaData">
	<p>Name: <span class="editRegion" id="name"><?php echo $album[0]['name']; ?></span></p>
	<p>Slug: <span class="editRegion" id="slug"><?php echo $album[0]['slug']; ?></span></p>
	<p>&nbsp;</p>
	<form action="<?php echo get_url('albums/changeAlbumPublishStatus'); ?>" method="post">
		<input type="hidden" name="album" value="<?php echo $album[0]['id']; ?>" />
		<small>This album is</small> 
		<select name="published" onchange="return dropdown(this)">
			<option value="yes"<?php if($album[0]['published'] == 'yes') { echo ' selected="selected"'; } ?>>published</option>
			<option value="no"<?php if($album[0]['published'] == 'no') { echo ' selected="selected"'; } ?>>not published</option>
		</select><br />
	</form>
	<form action="<?php echo get_url('albums/changeAlbumCategory'); ?>" method="post">
		<input type="hidden" name="album" value="<?php echo $album[0]['id']; ?>" />
		<small>in the category </small>
		<select name="category" onchange="return dropdown(this)">
<?php
	foreach($categories as $category) {
?>
			<option<?php if($category['id'] == $album[0]['category']) echo ' selected="selected"'; ?> value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
<?php } ?>
		</select>
	</form>
</div>

<div class="metaData">
	<p>Album Description:</p>
	<?php	if($album[0]['description'] == '') { ?>
	<p><span class="editRegion" id="description">add a description</span></p>
	<?php	} else {	?>
	<p><span class="editRegion" id="description"><?php echo $album[0]['description']; ?></span></p>
	<?php	} ?>
	<p>&nbsp;</p>
	<p>Album Credit:</p>
	<?php	if($album[0]['credits'] == '') { ?>
	<p><span class="editRegion" id="credits">add an image credit</span></p>
	<?php	} else {	?>
	<p><span class="editRegion" id="credits"><?php echo $album[0]['credits']; ?></span></p>
	<?php	} ?>
</div>

<div class="clear"></div>

<?php if(count($images) > 1) { ?>
<p id="note"><strong>Note:</strong> Simply drag photos to rearrange them. The new order will be saved automatically.</p>
<?php }
	  if(count($images) != 0) { ?>
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

<div class="metaData">
	<p>+ <a href="<?php echo get_url('albums/add/'.$album[0]['id'].''); ?>">Add images to this album</a></p>
</div>

<div class="metaData">
	<p><img src="<?php echo URL_PUBLIC . CORE_FOLDER .'/plugins/albums/images/delete.png'; ?>" alt="+" align="top" height="16px" /> <a href="<?php echo get_url('albums/delete-album/'.$album[0]['id'].''); ?>" title="Delete Album: <?php echo $album[0]['name']; ?>">Delete Album</a></p>
</div>

<div class="clear"></div>

<script type="text/javascript">

	Sortable.create('images', {
		ghosting:false, constraint:false, hoverclass:'over', onChange:function(element) {
			var totElement = <?php echo count($images); ?>;
			var newOrder = Sortable.serialize(element.parentNode);
			for(i=1; i<=totElement; i++) {
				newOrder = newOrder.replace("images[]=","");
				newOrder = newOrder.replace("&",",");
			}
			var page = window.location.href;
			var pageArray = page.split('/');
			var arrayLength = pageArray.length;
			modRewrite = '';
			if(pageArray[4] == '?') { modRewrite = '?/'; }
			var type_content = pageArray[arrayLength-2];
		  	var url = pageArray[0]+'//'+pageArray[2]+'/'+pageArray[3]+'/'+modRewrite+'albums/view/updateOrder/<?php echo $album[0]['id']; ?>o'+newOrder;
			new Ajax.Request(url, {method:'post'});
		}
	});

</script>