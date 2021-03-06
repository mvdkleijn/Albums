<h1>Create a new Category:</h1>

<div class="metaData">
	<p>&laquo; <a href="<?php echo get_url('albums/categories'); ?>">Back to categories</a></p>
</div>

<script type="text/javascript">
	function startCallback() {
		return true;
	}
	function completeCallback(response) {
		document.getElementById('result').innerHTML = response;
		setTimeout("clearMessage()", 500);
	}
	function clearMessage() {
		document.getElementById('form').reset();
	}
</script>


<form id="form" enctype="multipart/form-data" action="<?php echo get_url('albums/addCategoryHandler'); ?>" method="post" onsubmit="return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback})">
	<div class="label">&nbsp;</div>
	<div class="result-holder"><p id="result"></p></div>
	<div class="form-clear"></div>
	<table class="fieldset">
		<tr>
			<td class="label">Name</td>
			<td class="field"><input class="textbox" type="text" name="name" id="nametoslug" /></td>
			<td class="help">What should we call this category?</td>
		</tr>
		<tr>
			<td class="label">Slug</td>
			<td class="field"><input class="textbox" type="text" name="slug" id="slug" /></td>
			<td class="help">This will be used in advanced routing configurations</td>
		</tr>
		<tr>
			<td class="label">Description</td>
			<td class="field"><textarea name="description" style="height: 100px;"></textarea></td>
			<td class="help">You can add a description for this category</td>
		</tr>
		<tr>
			<td class="label"></td>
			<td class="field" colspan="2"><input type="submit" value="Add this Category" /></td>
		</tr>
	</table>
</form>