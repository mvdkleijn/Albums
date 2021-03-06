<div class="box">
	<p class="button"><a href="<?php echo get_url('albums/images'); ?>"><img src="<?php echo URL_PUBLIC . CORE_FOLDER . '/plugins/albums/images/images.png'; ?>" align="middle" alt="Images" />Images</a></p>
	<p class="button"><a href="<?php echo get_url('albums/add'); ?>"><img src="<?php echo URL_PUBLIC . CORE_FOLDER . '/plugins/albums/images/add.png'; ?>" align="middle" alt="Add a new image" />Add an Image</a></p>
</div>
<div class="box">
	<p class="button"><a href="<?php echo get_url('albums/albums'); ?>"><img src="<?php echo URL_PUBLIC . CORE_FOLDER . '/plugins/albums/images/albums.png'; ?>" align="middle" alt="Albums" />Albums</a></p>
	<p class="button"><a href="<?php echo get_url('albums/addAlbum'); ?>"><img src="<?php echo URL_PUBLIC . CORE_FOLDER . '/plugins/albums/images/add.png'; ?>" align="middle" alt="Create a new Album" />Create a new Album</a></p>
</div>
<div class="box">
	<p class="button"><a href="<?php echo get_url('albums/categories'); ?>"><img src="<?php echo URL_PUBLIC . CORE_FOLDER . '/plugins/albums/images/categories.png'; ?>" align="middle" alt="Categories" />Categories</a></p>
	<p class="button"><a href="<?php echo get_url('albums/addCategory'); ?>"><img src="<?php echo URL_PUBLIC . CORE_FOLDER . '/plugins/albums/images/add.png'; ?>" align="middle" alt="Create a new Category" />Create a new Category</a></p>
</div>

<?php
	if($settings['logging'] == 'on') {
?>
	<p class="button"><a href="<?php echo get_url('albums/logs'); ?>"><img src="<?php echo URL_PUBLIC . CORE_FOLDER . '/plugins/albums/images/logs.png'; ?>" align="middle" alt="Logs" />Logs</a></p>
<?php
	}
?>

	<p class="button"><a href="<?php echo get_url('albums/settings'); ?>"><img src="<?php echo URL_PUBLIC . CORE_FOLDER . '/plugins/albums/images/settings.png'; ?>" align="middle" alt="Settings" />Settings</a></p>

	<p class="button"><a href="<?php echo get_url('albums/documentation'); ?>"><img src="<?php echo URL_PUBLIC . CORE_FOLDER . '/plugins/albums/images/documentation.png'; ?>" align="middle" alt="Documentation" />Documentation</a></p>