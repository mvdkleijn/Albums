ALBUM PLUGIN
------------

This plugin allows website administrators to manage their digital images, which can then be displayed on the front end of the site.
Images can be retrieved individually if you know the ID of the image, or as an 'album'.



REQUIREMENTS
------------

In order to run the plugin, you must have Wolf version 0.6.0 or later as it makes use of the new Dispatcher functionality in the core.
You will also need to have GD Library installed into PHP as there is server side manipulation involved.
When you first visit the plugin, it will check the environment for you and display an error if there is a problem.



HOW TO SETUP
------------

1. Upload the plugin to your Wolf installed 'plugins' directory.
2. CHMOD the folder in /wolf/plugins/albums/files to 0777 so that you can add images to your web server.
3. Install the plugin via the Administration section of your Wolf installation.
4. Visit the "Albums" tab in the admin area and configure the plugin to your specifications.
5. Enjoy



WHAT IT INSTALLS
----------------

plugin_settings	-	bundled settings which are configurable via the Settings page
albums			-	This table stores information about the Albums themselves
albums_images	-	This table contains information about the Images
albums_log		-	This table is installed but not you need to set logging to 'on' in the Settings page to use it
					It will contain information that is usually only available in Server Logs and may be useful for debugging.
albums_order	-	When an album has more than one image, it is possible to arrange the order of the images, which is stored here.



NOTES
-----

	The table names mentioned above can be changed in ServeModel.php and AlbumsModel.php if they conflict with anything.

	TO DO LIST (In no particular order)

		Add a hash for each image to prevent sequence guessing [requires setting to enable/disable and salt]
		Add ability for administrators to export an album to a zip and download.
		Add batch import facilities