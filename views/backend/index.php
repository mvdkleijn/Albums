<h1>Albums</h1>

<p>Your images are currently using <?php echo number_format(Albums::calculateSizeOnDisk('all', NULL, 'megabytes'), 2); ?><small>MB</small> of disk space (excluding cached versions)</p>

<?php

	$settings = Plugin::getAllSettings('albums');

	if(count($albums) == 0) {
		$gdInfo = gd_info();
		if($gdInfo['GD Version'] == '') { ?>
			<div id="error">
				<p>There is a problem - you don't appear to have GD Library installed. Without it, this plugin will not function properly!</p>
				<p>Please contact your server administrator for more information</p>
			</div>
			<div style="clear:both;"></div>
<?php	} ?>
<p>Welcome to the Albums plugin!</p>
<p>It looks like you haven't set up any albums yet, so why not <a href="<?php echo get_url('albums/addAlbum'); ?>">create one now</a>?</p>
<p>Once you've set up your first album, you can add some images to it...</p>
<p>If you haven't done so already please <a href="<?php echo get_url('albums/documentation'); ?>">read the documentation</a> to get an idea of what this plugin can do for you.</p>
<?php
	}
?>

<?php	foreach($categories as $category) {
?>
<div class="category">
	<p>	<small>Category:</small> <a href="<?php echo get_url('albums/categories/'.$category['id'].''); ?>"><?php echo $category['name']; ?></a><br />
		<strong><small>[<?php echo number_format(Albums::calculateSizeOnDisk('category', $category['id'], 'megabytes'), 2); ?>MB]</small></strong></p>
<?php	foreach($albums as $album) {
			if($category['id'] == $album['category']) {
?>
	<div class="album">
		<p><small>Album:</small> <a href="<?php echo get_url('albums/view/'.$album['id'].''); ?>"><?php echo $album['name']; ?></a><br />
			<strong><small>[<?php echo number_format(Albums::calculateSizeOnDisk('album', $album['id'], 'megabytes'), 2); ?>MB]</small></strong></p>
			<ul class="allImages">
<?php	foreach($images as $image) {
			if($image['album'] == $album['id']) {
?>
				<li>
					<a href="<?php echo get_url('albums/image/'.$image['id'].''); ?>"><img src="<?php echo Albums::urlToImage($image['id'], '100'); ?>" /></a><br />
					<small>[<?php echo number_format(Albums::calculateSizeOnDisk('image', $image['id'], 'megabytes'), 2); ?>MB]</small>
				</li>
<?php		}
		}	?>
		</ul>
	</div>
<?php		}
		}
?>
<div style="clear:both;"></div>
</div>
<?php	}	?>