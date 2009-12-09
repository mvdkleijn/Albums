<h1>Create a new Album:</h1>

<script type="text/javascript">
	function startCallback() {
		return true;
	}
	function completeCallback(response) {
		document.getElementById('result').innerHTML = response;
		document.getElementById('form').reset();
	}
</script>

<form id="form" enctype="multipart/form-data" action="<?php echo get_url('albums/addAlbumHandler'); ?>" method="post" onsubmit="return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback})">
	<div class="label">&nbsp;</div>
	<div class="result-holder"><p id="result"></p></div>
	<div class="form-clear"></div>
	<div class="label"><label>Name:</label></div>
	<div class="field"><input class="textbox" type="text" name="name" /></div>
	<div class="form-clear"></div>
	<div class="form-clear"></div>
	<div class="label"><label>Description:</label></div>
	<div class="field"><textarea name="description"></textarea></div>
	<div class="form-clear"></div>
	<div class="label">&nbsp;</div>
	<div class="field"><input type="submit" value="Add this Album" /></div>
</form>