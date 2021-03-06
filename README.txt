**Status:** No longer active / archived


ALBUM PLUGIN
------------

Author - Andrew Waters at band-x (andrew@band-x.org)

This plugin allows website administrators to manage their digital images, which can then be displayed on the front end of the site.
Images can be retrieved individually if you know the ID of the image, or as an 'album' (a collection of images) or by category
(a collection of albums).

Icons in this plugin are a mixture from Function and Web Injection icon packs:

	http://wefunction.com/2008/07/function-free-icon-set
	http://www.tutorial9.net/resources/free-icon-pack-web-injection/



REQUIREMENTS
------------

In order to run the plugin, you must have Wolf version 0.6.0 or later as it makes use of the new Dispatcher functionality in the core.
You will also need to have GD Library installed into PHP as there is server side image manipulation involved.
When you first visit the plugin, it will check the environment for you and display an error if there is a problem.



HOW TO SETUP
------------

1. Upload the plugin to your Wolf installed 'plugins' directory.
2. CHMOD the folder in /wolf/plugins/albums/files to 755 so that you can add images to your web server.
3. Install the plugin via the Administration section of your Wolf installation.
4. Visit the "Albums" tab in the admin area and configure the plugin to your specifications.
5. Enjoy



WHAT IT INSTALLS
----------------

plugin_settings		-	bundled settings which are configurable via the Settings page.

albums				-	This table stores information about the Albums themselves.
albums_categories	-	this table contains information about categories you can store albums in.
albums_images		-	This table contains information about the Images.
albums_log			-	This table is installed but note that you need to set logging to 'on' in the Settings page to use it
						It will contain information that is usually only available in Server Logs and may be useful for debugging.
albums_order		-	When an album has more than one image, it is possible to arrange the order of the images, which is stored here.
albums_tags			-	This table contains tagging information for images.



NOTES
-----

	The table names mentioned above can be changed in ServeModel.php and AlbumsModel.php if they conflict with existing tables in your database.
