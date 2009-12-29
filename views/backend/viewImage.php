<script type="text/javascript">
	function dropdown(sel){ 
		c = confirm('You are about to move this photo into another album\nDo you want to do this?');
		if(c) { sel.form.submit(); } else { sel.selectedIndex = 0; } 
	} 
</script>

<h1>Viewing Image</h1>

<h2>Name: <span class="editRegion" id="name"><?php echo $image[0]['name']; ?></span></h2>

<p>&laquo; <a href="<?php echo get_url('albums/view/'.$album[0]['id'].''); ?>">Back to <?php echo $album[0]['name']; ?> Album</a></p>

<div class="originalImageInfo">
	<div class="originalImageInfoGeneral">	
		<small>
			Added on <?php echo date('jS F, Y', $image[0]['timeAdded']); ?><br />
			Disk Usage: <?php echo number_format(((filesize(CORE_ROOT . '/plugins/albums/files/'.$image[0]['id'].'.'.$image[0]['extension'].'')) / 1024 / 1024), 2); ?> MB
		</small>
	</div>
	<div class="originalImageInfoDimensions">
		<?php $imageInfo = getimagesize(CORE_ROOT . '/plugins/albums/files/'.$image[0]['id'].'.'.$image[0]['extension'].''); ?>
		<small>
			&harr; <?php echo $imageInfo[0]; ?>px<br />
			&uarr; <?php echo $imageInfo[1]; ?>px<br />
			<a href="<?php echo URL_PUBLIC . $settings['route'] . '/' . $image[0]['id'].'.'.$image[0]['extension']; ?>" target="_blank">View full size image in a new window</a>
		</small>
	</div>
	<div class="clear"></div>
</div>



<img class="imageView" src="<?php echo Albums::urlToImage($image[0]['id'], '620'); ?>" alt="<?php echo $album[0]['name']; ?>" />

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

<div class="metaData">
	<p>Tags: <span class="editRegion" id="tags"><?php
		$tags = array_reverse($tags);
		$countTags = count($tags);
		if($countTags == 0) echo 'add a tag';
		$i = 1;
		foreach($tags as $tag) {
			echo $tag['tag'];
			if($i < $countTags) echo ', ';
			$i= $i+1;
		}
	?></span></p>
	<p><small><strong>(Comma Separated)</strong></small></p>
</div>


<div class="metaData">
	<?php	if($image[0]['description'] == '') { ?>
	<p>Description: <span class="editRegion" id="description">add a description</span></p>
	<?php	} else {	?>
	<p>Description: <span class="editRegion" id="description"><?php echo $image[0]['description']; ?></span></p>
	<?php	} ?>
</div>

<div class="clear"></div>

<div class="metaData">
	<?php	if($image[0]['credits'] == '') { ?>
	<p>Image Credit: <span class="editRegion" id="credits">add an image credit</span></p>
	<?php	} else {	?>
	<p>Image Credit: <span class="editRegion" id="credits"><?php echo $image[0]['credits']; ?></span></p>
	<?php	} ?>
</div>

<div class="metaData" id="options">
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
	<p>&times; <a href="<?php echo get_url('albums/delete-image/'.$image[0]['id'].''); ?>">Delete this image</a></p>
</div>

<div class="clear"></div>