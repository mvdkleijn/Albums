<h1>Documentation</h1>

<p>Thanks for installing the Albums plugin.</p>

<h2>What does Albums do?</h2>

<p>This plugin allows you to manage image assets across an entire site. There are several benefits to serving out your site images using it and a couple of drawbacks so the implementation will depend greatly on your ambitions.</p>
<p>Before we delve into those, let's quickly go over a few things. The base of everything is categories. Within categories you can create albums and within albums you can add images. There must always be one category, which by default is "uncategorised". You can change the name of this category easily if this doesn't suit.</p>
<p>The plugin is intended to mimic traditional files on a server so that you can use the following directory structure:</p>

<p><small><?php echo URL_PUBLIC; ?>image-directory/category/album/image.ext</small></p>

<p>The <small>image-directory</small> is customisable in the <a href="<?php echo get_url('albums/settings'); ?>">settings page</a> and the <small>category</small> and <small>album</small> elements can be customised using the slug element of the respective edit page. This allows you to be very flexible in the structure of your site.</p>

<p>You can add JPG, GIF, or PNG images to the site. Other formats are not currently supported (and not likely to be either).</p>

<p>All uploads are stored in <small><?php echo CORE_FOLDER; ?>/plugins/albums/files</small> and served out to the frontend via PHP. If you ever need to access the files on the server, that's where they can be found.</p>

<h2>Pros and Cons</h2>

<p>That should give you an idea of what it can do and in the interests of equality, it would make sense to discuss briefly the benefits and drawbacks of managing your images this way.</p>

<p>Pros</p>

<p><small>
	1. The public root folder is clean<br />
	2. There are no 'real' folders to manage, so you don't need to create a connection to the server to manage images<br />
	3. There is only one version of an image on the server<br />
	4. You can monitor image impressions and see what sites are linking to your images<br />
	5. You can choose different naming schemes for your images (numeric - 1.jpg, 2.jpg etc | hashed - c8b821da976e5ed529a7b0218d141082.jpg etc | the name of the image)
</small></p>

<p>Cons</p>

<p><small>
	1. The load time of the image is a little longer due to it being served through PHP and not Apache / IIS<br />
	2. There is a little more load on the server (mainly RAM based) for the same reason.
</small></p>

<p>Pro point number 3 is very important. Often, when building a site from a design, you will resize an image from a source to make it fit a certain space. This plugin can resize images on the fly! So the path to your full size image may look like this:</p>
<p><small><?php echo URL_PUBLIC; ?>image-directory/category/album/image.ext</small></p>

<p>You can make that image 250px wide by using this URL instead:</p>
<p><small><?php echo URL_PUBLIC; ?>image-directory/category/album/image.<strong>250</strong>.ext</small></p>

<p>Please note, that when you resize an image like this, a cached version is kept on the server. Before resizing an image, which consumes RAM, we check if the cached version exists. So even if you upload a 10MB image file, you won't load it into RAM on each request for it. This speeds up serving and also attempts to alleviate Con point 2. So we only resize dynamically the first time an image is requested, keeping your server and sysadmin happy!</p>

<h2>Structure</h2>

<p>In addition to using the directory structure mentioned so far, you can also link to those images within the image directory 'folder'. So both these requests will display the same image:</p>

<p>
	<small><?php echo URL_PUBLIC; ?>image-directory/category/album/image.ext</small><br />
	<small><?php echo URL_PUBLIC; ?>image-directory/image.ext</small>
</p>

<h2>Advanced</h2>

<p>To create a link to an image there is a very useful function that you can call when writing in PHP. You need to know the internal image ID to display it, but it comes in handy so that if you change the structure of your images, or the image name, you don't need to change all instances of an image. That function is <small>urlToImage()</small> and is used like this:</p>

<p><small>&lt;img src="&lt;?php echo Albums::urlToImage($id, $size); ?&gt;" /&gt;</small></p>

<p><small>$id</small> and <small>$size</small> should be integers. If you want to display the full size image, leave <small>$size</small> blank, or <small>NULL</small></p>

<h2>Summary</h2>

<p>So hopefully that gives you a good idea of what this plugin can do for you.</p>

<p>If you have any feature requests or feedback on this plugin, please feel free to visit the <a href="http://www.band-x.org/downloads/albums" target="_blank">project homepage</a>.</p>