<h1>Albums</h1>

<?php

	$settings = Plugin::getAllSettings('albums');

?>

<div class="metaData">
	<p>&laquo; <a href="<?php echo get_url('albums'); ?>">Back to overview</a></p>
</div>

<div id="viewOptions">
	<a href="<?php echo get_url('albums/changeView/detail'); ?>"><img align="middle" src="<?php echo URL_PUBLIC . CORE_FOLDER . '/plugins/albums/images/info.png'; ?>" /> view in detail</a><br />
	<a href="<?php echo get_url('albums/changeView/grid'); ?>"><img align="middle" src="<?php echo URL_PUBLIC . CORE_FOLDER . '/plugins/albums/images/grid.png'; ?>" /> view in grid</a>
</div>

<div style="clear:both;"></div>

<div class="albums">
<?php
	foreach($albums as $album) {
		$coverImage = Albums::getCoverImage($album['id']);
		if($settings['defaultView'] == 'detail') {
?>
	<div class="album-holder">
		<div class="cover-image">
			<p align="center">
				<a href="<?php echo get_url('albums/view/'.$album['id'].''); ?>" title="View / Edit Album: <?php echo $album['name']; ?>">
<?php
				
					if($coverImage[0]['coverImage'] != 0) {
						echo '<img src="' . Albums::urlToImage($coverImage[0]['coverImage'], '150') . '" alt="' . $album['name'] . '" />';
					} else {
						$settings = Plugin::getAllSettings('albums');
						echo 'No Images yet!';
					}
					
?>
				</a>
			</p>
			<p align="center">[<?php if($album['published'] == 'no') { echo 'Not Published'; } else { echo 'Published'; } ?>]</p>
		</div>
		<div class="album-info">
			<h2><a href="<?php echo get_url('albums/view/'.$album['id'].''); ?>" title="View Album: <?php echo $album['name']; ?>"><?php echo $album['name']; ?></a></h2>
			<p>
				<?php echo Albums::countImagesInAlbum($album['id']); ?> photo<?php if((Albums::countImagesInAlbum($album['id']) != 1)) echo 's'; ?>
			</p>
			<p><?php echo $album['description']; ?></p>
			<p>
				Created on <?php echo date('jS F Y', $album['created']); ?><br />
				<?php if($album['updated'] != 0) { ?>Updated on <?php echo date('jS F Y', $album['updated']); ?><?php } ?>
			</p>
			<p>
				&raquo; <a href="<?php echo get_url('albums/view/'.$album['id'].''); ?>" title="View Album: <?php echo $album['name']; ?>">View / Edit Album</a><br />
				&times; <a href="<?php echo get_url('albums/delete-album/'.$album['id'].''); ?>" title="Delete Album: <?php echo $album['name']; ?>">Delete Album</a>
			</p>
		</div>
		<div class="album-holder-clear">
		</div>
	</div>
<?php
		}
		elseif($settings['defaultView']=='grid') {
?>
		<div id="image-holder-grid">
				<a href="<?php echo get_url('albums/view/'.$album['id'].''); ?>" title="View / Edit Album: <?php echo $album['name']; ?>">
					<h3><?php echo $album['name']; ?></h3>
	<?php
				
					if($coverImage[0]['coverImage'] != 0) {
						echo '<img src="' . Albums::urlToImage($coverImage[0]['coverImage'], '150') . '" alt="' . $album['name'] . '" />';
					} else {
						$settings = Plugin::getAllSettings('albums');
						echo 'No Images yet!';
					}
					
	?>
				</a>
		</div>
<?php
		}
	}
?>
</div>
<div class="clear"></div>