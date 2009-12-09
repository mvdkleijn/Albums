<script type="text/javascript">
	function dropdown(sel){ 
		c = confirm('You are about to move this photo into another album\nDo you want to do this?');
		if(c) { sel.form.submit(); } else { sel.selectedIndex = 0; } 
	} 
</script>

<h1>Viewing Image: <span class="editRegion" id="name"><?php echo $image[0]['name']; ?></span></h1>

<p>&laquo; <a href="<?php echo get_url('albums/view/'.$album[0]['id'].''); ?>">Back to <?php echo $album[0]['name']; ?> Album</a></p>

<p align="center"><img src="<?php echo Albums::urlToImage($image[0]['id'], '660'); ?>" alt="<?php echo $album[0]['name']; ?>" /></p>

<div id="previousNextWrapper">
	<div id="previousImage">
<?php if($previousNext['previous'] != '') { ?>
		<a href="<?php echo get_url('albums/image/'.$previousNext['previous'].''); ?>">
			&laquo; Previous Image<br /><br />
			<img src="<?php echo Albums::urlToImage($previousNext['previous'], '100'); ?>" alt="Previous Image" />
		</a>
<?php } ?>
	</div>
	
	<div id="nextImage">
<?php if($previousNext['next'] != '') { ?>
		<a href="<?php echo get_url('albums/image/'.$previousNext['next'].''); ?>">
			Next Image &raquo;<br /><br />
			<img src="<?php echo Albums::urlToImage($previousNext['next'], '100'); ?>" alt="Next Image" />
		</a>
<?php } ?>
	</div>
</div>

<?php	if($image[0]['description'] == '') { ?>
<p><span class="editRegion" id="description">add a description</span></p>
<?php	} else {	?>
<p><span class="editRegion" id="description"><?php echo $image[0]['description']; ?></span></p>
<?php	} ?>

<h3>Original Image Information:</h3>
<?php $imageInfo = getimagesize(CORE_ROOT . '/plugins/albums/files/'.$image[0]['id'].'.'.$image[0]['extension'].''); ?>

<p>
	Width: <?php echo $imageInfo[0]; ?>px<br />
	Height: <?php echo $imageInfo[1]; ?>px<br />
	Added on <?php echo date('jS F, Y', $image[0]['timeAdded']); ?>
</p>

<?php 
	if($album[0]['coverImage'] == $image[0]['id']) {
?>
<p>This is the Cover Image for the <?php echo $album[0]['name']; ?> album</p>
<?php
	} else { 
?>
<p>Would you like to <a href="<?php echo get_url('albums/makeCover/'.$image[0]['id'].''); ?>">make this the cover image</a> for the album?</p>
<?php
	} 
?>


<p>
<?php

	if($image[0]['published'] == 'yes') {
		$published = 'Published ';
	} else {
		$published = '<strong>Not</strong> Published';
	}
?>
		<form action="<?php echo get_url('albums/changeAlbum'); ?>" method="post">
			<input type="hidden" name="image" value="<?php echo $image[0]['id']; ?>" />
			<?php echo $published; ?> in the
			<select name="album" onchange="return dropdown(this)">
			<?php foreach($albums as $albumList) {
					$selected = '';
					if($albumList['id'] == $album[0]['id']) $selected = ' selected="selected"';
			?>
				<option value="<?php echo $albumList['id']; ?>"<?php echo $selected; ?>><?php echo $albumList['name']; ?></option>
			<?php } ?>
			</select> album
		</form>
</p>

<p>x <a href="<?php echo get_url('albums/delete-image/'.$image[0]['id'].''); ?>">Delete this image</a></p>