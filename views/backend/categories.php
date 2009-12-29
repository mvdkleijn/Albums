<h1>Categories</h1>

<div class="metaData">
	<p>&laquo; <a href="<?php echo get_url('albums'); ?>">Back to overview</a></p>
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
	</div>	
<?php
	}
?>
<div class="clear"></div>