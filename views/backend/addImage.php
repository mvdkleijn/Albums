<?php

	$saveFolder = CORE_ROOT .'/plugins/albums/files';

	if(!is_writeable($saveFolder)) {
?>
<h1>I need some help!</h1>
<p>We need to be able to upload images to your server and at the moment the folder:<br /><small><?php echo $saveFolder; ?></small><br />is not writeable. Please can you CHMOD that folder to 755 and I can then let you upload some images!</p>
<?php
	}
	elseif(count($albums) == 0) { ?>
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

	<table class="fieldset">
		<tr>
			<td class="label">Name</td>
			<td class="field"><input class="textbox" type="text" name="name" /></td>
			<td class="help">Add a name for this image</td>
		</tr>
		<tr>
			<td class="label">File</td>
			<td class="field" colspan="2"><input type="file" name="image" /></td>
		</tr>
		<tr>
			<td class="label">Cover Image</td>
			<td class="field">
				<select name="makeCoverImage">
					<option value="no">No</option>
					<option value="yes" selected="selected">Yes</option>
				</select>
			</td>
			<td class="help">Each Album needs a Cover Image.</td>
		</tr>
		<tr>
			<td class="label">Description</td>
			<td class="field"><textarea name="description" style="height:100px;"></textarea></td>
			<td class="help">Add a description for this image</td>
		</tr>
		<tr>
			<td class="label">Image Credit</td>
			<td class="field"><textarea name="credits" style="height:50px;"></textarea></td>
			<td class="help">If this image needs a credit, add it here</td>
		</tr>
		<tr>
			<td class="label">Album</td>
			<td class="field">
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
			</td>
			<td class="help">Which album should we put this in</td>
		</tr>
		<tr>
			<td class="label"></td>
			<td class="field" colspan="2"><input type="submit" value="Add this Image" /></td>
		</tr>
	</table>
</form>
<?php } ?>