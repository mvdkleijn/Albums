<h1>Confirm Category Deletion:</h1>
<p>Are you sure you want to delete the <strong><?php echo $category[0]['name']; ?></strong> category?</p>
<p>
	<span class="delete">
		<a href="<?php echo get_url('albums/confirm-category-delete/'.$category[0]['id'].''); ?>">Yes, Delete It!</a>
	</span>
	<span class="cancel">
		<a href="<?php echo get_url('albums/categories'); ?>">I've changed my mind</a>
	</span>
</p>