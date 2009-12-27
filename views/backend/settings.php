<h1>Settings</h1>

<form method="POST" id="album-settings" action="<?php echo get_url('albums/saveSettings'); ?>">
	<table class="fieldset">
		<tr>
			<td class="help" colspan="3">
				Adjust your site settings
			</td>
		</tr>
		<tr>
			<td class="label">Image Route</td>
			<td class="field"><small><?php echo URL_PUBLIC; ?><input name="route" value="<?php echo $settings['route']; ?>" />/image.jpg</small></td>
			<td class="help">The path on the front end to display images from. Think of this as a virtual folder of images.</td>
		</tr>
		<tr>
			<td class="label">Default View</td>
			<td class="field">
				<select name="defaultView">
					<option value="grid"<?php if($settings['defaultView'] == 'grid') echo ' selected="selected"'; ?>>Grid</option>
					<option value="detail"<?php if($settings['defaultView'] == 'detail') echo ' selected="selected"'; ?>>Detail</option>
				</select>
			</td>
			<td class="help">When browing in the admin area, you can view details for each album or view them in grid view, which is handy for sites with lots of albums.</td>
		</tr>
		<tr>
			<td class="label">Logging</td>
			<td class="field">
				<select name="logging">
					<option value="on"<?php if($settings['logging'] == 'on') echo ' selected="selected"'; ?>>On</option>
					<option value="off"<?php if($settings['logging'] == 'off') echo ' selected="selected"'; ?>>Off</option>
				</select>
			</td>
			<td class="help">You can enable logging of image impressions. This is handy to see how popular certain images are or if you want to display a view count.</td>
		</tr>
		<tr>
			<td class="label">&nbsp;</td>
			<td class="field" colspan="2">
				<input type="submit" value="Save Settings" />
			</td>
		</tr>
	</table>
</form>