<h1>Images</h1>

<ul class="allImages">
<?php
	foreach($images as $image) {
?>
	<li class="image" id="<?php echo $image['id']; ?>">
		<a href="<?php echo get_url('albums/image/'.$image['id'].''); ?>"><img src="<?php echo Albums::urlToImage($image['id'], '150'); ?>" alt="Image <?php echo $image['id']; ?>" title="Image ID: <?php echo $image['id']; ?> | Description: <?php echo $image['description']; ?> | Credits: <?php echo $image['credits']; ?>" /></a>
	</li>
<?php
	}
?>
</ul>