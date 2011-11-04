<?php

// Step 2 - Using filters to modify data

if(!class_exists( 'MakeMyBlogHonest' )) {
	
	class MakeMyBlogHonest
	{
		
		const PREFIX = 'mmbh';
		
		private $plugin_url;
		
		function __construct() {
			
			$this->plugin_url = plugins_url(basename(dirname(__FILE__)));
			
/* NEW CODE */
			
			/* Add a filter to modify any calls to bloginfo(); */
			add_filter( 'bloginfo', array( $this, 'AddEdsCheesySloganToTitle' ), 1, 2 );
		
			/* Add a filter to modify the header image for themes
				that support custom header images (e.g. TwentyTen, TwentyEleven) */
			add_filter( 'theme_mod_header_image', array( $this, 'AddEdsHorribleBanner' ) );
			
			/*	For a complete list of filters visit http://is.gd/dkXfDP */
			
		}
		
		/* Note that the number of parameters here must match the 4th parameter on line 23 */
		function AddEdsCheesySloganToTitle( $current_value, $field )
		{
			/* Check if bloginfo('name') is being requested. 
				If true, append our tagline to it and return the value, so that
				other filters have a chance to act on it. */
			if( $field == 'name' ) return $current_value . ' &ndash; you can\'t beat his prices!';
		
		}
		
		/* When using a theme that supports header images, this method
			will override the default image, and return our custom image. */
		function AddEdsHorribleBanner()
		{
		
			return $this->plugin_url . '/images/eds-horrible-banner1.jpg';
		
		}
		
/* END NEW CODE */
		
	}
	
	$myHonestBlog = new MakeMyBlogHonest();

}

/* We have now modified the appearance of our blog using filters. Woot! */

?>