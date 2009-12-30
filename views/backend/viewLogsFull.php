<h1>Full Request Log</h1>

<div class="metaData">
	<p>&laquo; <a href="<?php echo get_url('albums/logs'); ?>">Back to Overview</a></p>
</div>

<div class="metaData">
	<p><img src="<?php echo URL_PUBLIC . CORE_FOLDER .'/plugins/albums/images/delete.png'; ?>" alt="delete" align="top" height="16px" /> 
		<a onclick="return confirm('Are you sure you want to clear the image request log?');" href="<?php echo get_url('albums/logs/clear'); ?>">
		Clear Log</a></p>
</div>

<div class="clear"></div>

<table class="index" id="imageLog">
	<thead>
		<tr>
			<th>ID</th>
			<th>Image</th>
			<th>Referrer</th>
			<th>IP</th>
			<th>Request Time</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach($logs as $log) {
?>
		<tr>
			<td><?php echo $log['id']; ?></td>
			<td><?php echo end(explode('/', $log['image'])); ?></td>
			<td><?php echo $log['referrer']; ?></td>
			<td><?php echo $log['ip']; ?></td>
			<td><?php echo date('Y-m-d H:i:s', $log['time']); ?></td>
		</tr>
<?php
	}
?>
	</tbody>
</table>
