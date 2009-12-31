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
			<td class="field"><small><?php echo URL_PUBLIC; ?><input name="route" value="<?php echo $settings['route']; ?>" />/imageid.jpg</small></td>
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
			<td class="label">Display Size</td>
			<td class="field">
				<select name="displaySize">
					<option value="yes"<?php if($settings['displaySize'] == 'yes') echo ' selected="selected"'; ?>>Yes</option>
					<option value="no"<?php if($settings['displaySize'] == 'no') echo ' selected="selected"'; ?>>No</option>
				</select>
			</td>
			<td class="help">When selected, album, category and image sizes will be shown on the main page.</td>
		</tr>
		<tr>
			<td class="label">Format</td>
			<td class="field">
				<select name="format">
					<option value="numeric"<?php if($settings['format'] == 'numeric') echo ' selected="selected"'; ?>>Numeric</option>
					<option value="hash"<?php if($settings['format'] == 'hash') echo ' selected="selected"'; ?>>Hash</option>
					<option value="name"<?php if($settings['format'] == 'name') echo ' selected="selected"'; ?>>Image Name</option>
				</select>
			</td>
			<td class="help">Image ID's may be numeric (213.jpg), hashed with a salt (ded80474080b5a99df0e30128192ca31.jpg) or you can use the image name (My%20Picture.jpg). Hashing offers more secure images, and uses the plugin install timestamp as the salt. Note that if you use the image name, all casing and spaces / characters will be used in HTML entity equivalents.</td>
		</tr>
		<tr>
			<td class="label">Hash Salt</td>
			<td class="field">
				<input name="salt" value="<?php echo $settings['salt']; ?>" />
			</td>
			<td class="help">If you use the hash format above, you can change the salt here.</td>
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
			<td class="label">Time to Keep Logs</td>
			<td class="field">
				<select name="logCollection">
					<option value="0"<?php if($settings['logCollection'] == '0') echo ' selected="selected"'; ?>>-- never flush --</option>
					<option value="86400"<?php if($settings['logCollection'] == '86400') echo ' selected="selected"'; ?>>1 Day</option>
					<option value="604800"<?php if($settings['logCollection'] == '604800') echo ' selected="selected"'; ?>>1 Week</option>
					<option value="1209600"<?php if($settings['logCollection'] == '1209600') echo ' selected="selected"'; ?>>2 Weeks</option>
					<option value="2678400"<?php if($settings['logCollection'] == '2678400') echo ' selected="selected"'; ?>>1 Month</option>
					<option value="8035200"<?php if($settings['logCollection'] == '8035200') echo ' selected="selected"'; ?>>3 Months</option>
					<option value="16070400"<?php if($settings['logCollection'] == '16070400') echo ' selected="selected"'; ?>>6 Months</option>
					<option value="31536000"<?php if($settings['logCollection'] == '31536000') echo ' selected="selected"'; ?>>1 Year</option>
				</select>
			</td>
			<td class="help">If you don't flush logs, you can easily end up with a database that is huge on sites with large volumes of traffic.</td>
		</tr>
		<tr>
			<td class="label">&nbsp;</td>
			<td class="field" colspan="2">
				<input type="submit" value="Save Settings" />
			</td>
		</tr>
	</table>
</form>