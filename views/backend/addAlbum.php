<h1>Create a new Album:</h1>

<script type="text/javascript">
	function startCallback() {
		return true;
	}
	function completeCallback(response) {
		document.getElementById('result').innerHTML = response;
		setTimeout("clearMessage()", 2000);
	}
	function clearMessage() {
		document.getElementById('result').innerHTML = '';
		document.getElementById('form').reset();
	}
</script>


<form id="form" enctype="multipart/form-data" action="<?php echo get_url('albums/addAlbumHandler'); ?>" method="post" onsubmit="return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback})">
	<div class="label">&nbsp;</div>
	<div class="result-holder"><p id="result"></p></div>
	<div class="form-clear"></div>
	<table class="fieldset">
		<tr>
			<td class="label">Name</td>
			<td class="field"><input class="textbox" type="text" name="name" /></td>
			<td class="help">What should we call this album?</td>
		</tr>
		<tr>
			<td class="label">Description</td>
			<td class="field"><textarea name="description" style="height: 100px;"></textarea></td>
			<td class="help">You can add a description for this album</td>
		</tr>
		<tr>
			<td class="label">Album Credits</td>
			<td class="field"><textarea name="credits" style="height: 50px;"></textarea></td>
			<td class="help">If there are any album credits you can add them here.</td>
		</tr>
		<tr>
			<td class="label">Category</td>
			<td class="field">
				<select name="category">
					<?php
							foreach($categories as $category) {
					?>
					<option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
					<?php
							}
					?>
				</select>
			</td>
			<td class="help">What category should we put this album in?</td>
		</tr>
		<tr>
			<td class="label"></td>
			<td class="field" colspan="2"><input type="submit" value="Add this Album" /></td>
		</tr>
	</table>
</form>