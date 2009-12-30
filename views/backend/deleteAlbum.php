<h1>Confirm Album Deletion:</h1>
<img src="<?php echo Albums::urlToImage($album[0]['coverImage'], '200'); ?>" alt="<?php echo $album['name']; ?>" />
<p>Are you sure you want to delete the <strong><?php echo $album[0]['name']; ?></strong> album?</p>
<p><strong>You will delete all images within this album as well...</strong></p>
<p>
	<span class="delete">
		<a href="<?php echo get_url('albums/confirm-album-delete/'.$album[0]['id'].''); ?>">Yes, Delete It!</a>
	</span>
	<span class="cancel">
		<a href="<?php echo get_url('albums/view/'.$album[0]['id'].''); ?>">I've changed my mind</a>
	</span>
</p>