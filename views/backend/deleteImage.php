<h1>Confirm Image Deletion:</h1>
<img src="<?php echo Albums::urlToImage($image[0]['id'], '200'); ?>" />
<p>Are you sure you want to delete this image?</p>

<p>
	<span class="delete">
		<a href="<?php echo get_url('albums/confirm-image-delete/'.$image[0]['id'].''); ?>">Yes, Delete It!</a>
	</span>
	<span class="cancel">
		<a href="<?php echo get_url('albums/image/'.$image[0]['id'].''); ?>">I've changed my mind</a>
	</span>
</p>