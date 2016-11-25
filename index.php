<?php 
/*
Plugin Name: BDNEWS Headlines
Plugin URI: http://www.cxrana.com
Description: Display Latest News Headlines From bangla.bdnews24.com
Version: 1.0
Author: ahrana 
Author URI: http://www.cxrana.com
License: GPL3
*/ 


        
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        

	include('functions.php');
    


    function register_rfw_scripts() {
            
			
			
			
			wp_enqueue_script(
				'rfw-script',
				plugins_url('functions.js', __FILE__),
				array( 'jquery' )
			);
			
			wp_enqueue_script(
				'rfw-slider-script',
				plugins_url('jquery.bxslider.js', __FILE__),
				array( 'jquery' )
			);			

            wp_register_style('rfw-style', plugins_url('style.css', __FILE__));
			wp_register_style('rfw-slider-style', plugins_url('jquery.bxslider.css', __FILE__));
			
			wp_enqueue_style( 'rfw-style' );
			wp_enqueue_style( 'rfw-slider-style' );
 
        }
	
        
	add_action( 'wp_enqueue_scripts', 'register_rfw_scripts' );

	add_action( 'widgets_init', 'rfw_init');

	