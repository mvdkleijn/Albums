<?php

	if(count($albums) == 0) { ?>
<h1>Just a Moment...</h1>
<p>Before you can add an image, you must <a href="<?php echo get_url('albums/addAlbum'); ?>">add an Album</a></p>
<?php
	} else {

?>
<h1>Add an Image to an Album:</h1>
<?php if($id) { ?>
<p>&laquo; <a href="<?php echo get_url('albums/view/'.$id.''); ?>">Back to <?php echo $album[0]['name'] ?> Album</a></p>
<?php } else {
			$album[0]['id'] = 0;
?>
<p>&laquo; <a href="<?php echo get_url('albums'); ?>">Back to Albums</a></p>
<?php } ?>

<script type="text/javascript">
	function startCallback() {
		return true;
	}
	function completeCallback(response) {
		document.getElementById('result').innerHTML = response;
		document.getElementById('form').reset();
	}
</script>

<p id="note"><strong>Note:</strong> You can select JPG, GIF and PNG files of ANY SIZE to upload here</p>


<form id="form" enctype="multipart/form-data" action="<?php echo get_url('albums/add-image/'.$album[0]['id'].''); ?>" method="post" onsubmit="return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback})">
	<div class="label">&nbsp;</div>
	<div class="result-holder"><p id="result"></p></div>
	<div class="form-clear"></div>
	<div class="label"><label>Name:</label></div>
	<div class="field"><input class="textbox" type="text" name="name" /></div>
	<div class="form-clear"></div>
	<div class="label"><label>File:</label></div>
	<div class="field"><input type="file" name="image" /></div>
	<div class="form-clear"></div>
	<div class="label"><label>Make Cover Image for Album?</label></div>
	<div class="field">
		<select name="makeCoverImage">
			<option value="no" selected="selected">No</option>
			<option value="yes">Yes</option>
		</select>
	</div>
	<div class="form-clear"></div>
	<div class="label"><label>Description:</label></div>
	<div class="field"><textarea name="description"></textarea></div>
	<div class="form-clear"></div>
	<div class="label"><label>Album:</label></div>
	<div class="field">
		<select name="album">
			<?php foreach ($albums as $albumList) {
					$selected = '';
					if($albumList['id'] == $id) {
						$selected = ' selected="selected"';
					}
			?>
			<option value="<?php echo $albumList['id']; ?>"<?php echo $selected; ?>><?php echo $albumList['name']; ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="form-clear"></div>
	<div class="label">&nbsp;</div>
	<div class="field"><input type="submit" value="Add this Image" /></div>
</form>
<?php } ?>