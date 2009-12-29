<h1>Categories</h1>

<div class="metaData">
	<p>&laquo; <a href="<?php echo get_url('albums'); ?>">Back to overview</a></p>
</div>

<div class="metaData">
	<p><img src="<?php echo URL_PUBLIC . CORE_FOLDER .'/plugins/albums/images/add.png'; ?>" alt="+" align="top" height="16px" /> <a href="<?php echo get_url('albums/addCategory'); ?>">Add a new Category</a></p>
</div>

<div class="clear"></div>

<p>You can categorise your albums depending on the context they're being used in.</p>
<p>For example, you may want to set up albums for your contact and about page and then categorise them in a "Site Images" category.</p>
<p>You can choose to ignore the categorisation features but they make it easier to manage image assets for bigger sites and allows you to use images in your pages and designs without having to manage folders on your server.</p>

<?php
	foreach($categories as $category) {
?>
	<div class="category-list">
		<p><a href="<?php echo get_url('albums/categories/'.$category['id'].''); ?>"><?php echo $category['name']; ?></a></p>
		<?php if($category['id'] != '1') { ?>
		<p><small>&times;  <a href="<?php echo get_url('albums/delete-category/'.$category['id'].''); ?>">Delete this category</a></small></p>
		<?php } else { ?>
		<p><small>This category cannot be deleted</small></p>
		<?php } ?>
	</div>	
<?php
	}
?>
<div class="clear"></div>