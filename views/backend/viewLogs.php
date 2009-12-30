<h1>Logs</h1>

<?php if($count != 0) { ?>

<p>There have been <?php echo $count; ?> image impressions since <?php echo date('F jS, Y', $firstImpression); ?> </p>

<div class="metaData">
	<p><img src="<?php echo URL_PUBLIC . CORE_FOLDER .'/plugins/albums/images/delete.png'; ?>" alt="delete" align="top" height="16px" /> 
		<a onclick="return confirm('Are you sure you want to clear the image request log?');" href="<?php echo get_url('albums/logs/clear'); ?>">
		Clear Log</a></p>
</div>

<div class="metaData">
	<p><a href="<?php echo get_url('albums/logs/full'); ?>">See a full Log</a> &raquo;</p>
</div>

<div class="clear"></div>

<?php } else { ?>
<p>The log is empty</p>
<?php } ?>