<?php

// Step 1 - Building your plugin within a class

/* Check to see if the class exists. This prevents an error if the 
	plugin is somehow loaded twice. */
	
if(!class_exists( 'MakeMyBlogHonest' )) {
	
	/* Define the class */
	
	class MakeMyBlogHonest
	{
		
		/* Define class variables to store name and url of our plugin.
			They will be used for namespacing and to reference scripts and styles */
			
		const PREFIX = 'mmbh';
		
		private $plugin_url;
		
		/* Define our class constructor. This will be executed on every page load
			as long as our plugin is activated. It's a good place to initialize variables
			and register actions. */
		function __construct() {
			
			
			$this->plugin_url = plugins_url(basename(dirname(__FILE__)));
			
			/* The function plugins_url returns the url to the WordPress plugins 
				directory, even if it's been moved outside the normal location. 
				Use plugins_url() instead of the WP_PLUGIN_URL constant  */
			
		}
		
	}
	
	/* Create an instance of the class */
	$myHonestBlog = new MakeMyBlogHonest();

}

/* We now have an empty plugin that does nothing. Yay! */

?>