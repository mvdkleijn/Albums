<h1>Editing Category</h1>

<div class="metaData">
	<p>&laquo; <a href="<?php echo get_url('albums/categories'); ?>">Back to categories</a></p>
</div>

<div class="clear"></div>

<form method="POST" id="form" enctype="multipart/form-data" action="<?php echo get_url('albums/editCategory'); ?>">
	<input type="hidden" name="id" value="<?php echo $category[0]['id']; ?>" />
	<table class="fieldset">
		<tr>
			<td class="label">Name</td>
			<td class="field"><input class="textbox" type="text" name="name" id="nametoslug" value="<?php echo $category[0]['name']; ?>" /></td>
			<td class="help">What should we call this category?</td>
		</tr>
		<tr>
			<td class="label">Slug</td>
			<td class="field"><input class="textbox" type="text" name="slug" id="slug" value="<?php echo $category[0]['slug']; ?>" /></td>
			<td class="help">This will be used in advanced routing configurations</td>
		</tr>
		<tr>
			<td class="label">Description</td>
			<td class="field"><textarea name="description" style="height: 100px;"><?php echo $category[0]['description']; ?></textarea></td>
			<td class="help">You can add a description for this category</td>
		</tr>
		<tr>
			<td class="label"></td>
			<td class="field" colspan="2"><input type="submit" value="Edit this Category" /></td>
		</tr>
	</table>
</form>