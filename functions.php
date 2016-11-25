<?php

class bdnews_bangla extends WP_Widget {

	public function __construct() {
		// widget actual processes
		parent::__construct(
			'bdnews_bangla', // Base ID
			__('BDNEWS Headlines', 'text_domain'), // Name
			array( 'description' => __( 'display latest news from bangla.bdnews24', 'text_domain' ), ) // Args
		);
	}
	

	function clean_xhtml($string)
	{
		$string = ereg_replace("<[^>]*>", "", $string);
		$string = preg_replace("@<p[^>]*?>.*?
		@siu", '',$string);
		return $string;
	}


	public function widget( $args, $instance ) {
		// outputs the content of the widget
		 
		 global $wpdb;
		 
		 $limit_feeds = ($instance['number']>0?$instance['number']:1);
		 
		 $title = $instance['title'];
		 
		 $rss_url = $instance['rss_url'];
		 
		 $show_feed_title = $instance['show_feed_title'];
		 
		 $feed_words = ($instance['feed_words']>0?$instance['feed_words']:60);
		 
		 $display_type = $instance['content_display'];
		
		 $html = '<nav class="add-nav widget_cx" id="rfw-authors">';
		 
		 $html.= ($title!='')?'<h2 class="widgettitle">'.$title.'</h2>':'';
		 
		 $rss = fetch_feed($rss_url);
		 
		 if ( ! is_wp_error( $rss ) ){
			 
			$maxitems = $rss->get_item_quantity( $limit_feeds );  
			
			$rss_items = $rss->get_items( 0, $maxitems );
			
			if ( $maxitems > 0 ){
				$html.= '<ul class="bdnews_bangla">';
           
				foreach ( $rss_items as $item ){
					
					$html.='<li>';
					
					$img = '';
					
					$link = '<a href="'.esc_url( $item->get_permalink() ).'" target="_blank" rel="nofollow">_LINK_TEXT_</a>';
					
					$html.= $show_feed_title?str_replace('_LINK_TEXT_', '<h3 class="entry-title">'.esc_html( $item->get_title() ).'</h3>', $link):'';
					
					$description = $item->get_description();	
					
					
					
					switch($display_type){
						
						case 'text_only':						
							$html.= $this->string_limit_words($this->clean_xhtml($description), $feed_words).' ...';							
						break;
						
						default:
							$html.= $this->string_limit_words($description, $feed_words).' ...';	
						break;
						
						case 'image_only':						
							//preg_match_all('/<img[^>]+>/i', $description, $img); 
							//
							//preg_match_all('/src="([^"]*)"/', $description, $img); 
							preg_match_all('@<img.*src="([^"]*)"[^>/]*/?>@Ui', $description, $img); 
							
							
							if(!empty($img)){
								
								$img = end($img);
								
								if(isset($img[0]) && $img[0]!=''){
									$img = $img[0];
									
									$img = current(explode('?', $img));
									
									
									
								}else{
									$img = '';
								}
							}
						break;		
					}
					
					
				   
					if($img!=''){
					   
						$html.= str_replace('_LINK_TEXT_', '<div class="image_only"><img src="'.$img.'" /></div>', $link);
					
					}
				   
				   
					$html.= '</li>';
          		  
				}
			}
		 }
		
		 
		 $html .= '</ul></nav>';
		 echo $html;
	
	}

	private function string_limit_words($string, $word_limit)
	{
	  $words = explode(' ', $string, ($word_limit + 1));
	  if(count($words) > $word_limit)
	  array_pop($words);
	  return implode(' ', $words);
	}
	
 	public function form( $instance ) {
		// outputs the options form on admin
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$rss_url     = isset( $instance['rss_url'] ) ? esc_attr( $instance['rss_url'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 6;
		
		$show_feed_title = isset( $instance['show_feed_title'] ) ? esc_attr( $instance['show_feed_title'] ) : true;
		$feed_words    = isset( $instance['feed_words'] ) ? absint( $instance['feed_words'] ) : 60;
		$content_display    = isset( $instance['content_display'] ) ? $instance['content_display'] : 'default';
		
		
		
		?>
		<p>
		<label for="bdnews24.com latest news"><?php _e( '<center><img class="" src="http://photos.bdnews24.com/bangla-media/2013/01/04/logo1.png/BINARY/logo1.png" alt="bdnews24.com" width="150" height="33" /></center></br>' ); ?></label> 
				<label for="bdnews24.com latest news">Title: </label> 

		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<label for="Source">Source:Auto Inserted, You Can't Edit/Delete</label> 
	<input class="widefat" id="<?php echo $this->get_field_id( 'rss_url' ); ?>" name="<?php echo $this->get_field_name( 'rss_url' ); ?>" type="text" value="http://bangla.bdnews24.com/?widgetName=rssfeed&widgetId=1151&getXmlFeed=true" /></p>
            
        <p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of News:' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
        
        <p><label for="<?php echo $this->get_field_id( 'show_feed_title' ); ?>"><?php _e( 'Display News title (Yes/No):' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'show_feed_title' ); ?>" name="<?php echo $this->get_field_name( 'show_feed_title' ); ?>" type="checkbox" value="true" <?php echo $show_feed_title?'checked="checked"':''; ?>  /></p>
        
        <p><label for="<?php echo $this->get_field_id( 'feed_words' ); ?>"><?php _e( 'News Details (Words):' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'feed_words' ); ?>" name="<?php echo $this->get_field_name( 'feed_words' ); ?>" type="text" value="<?php echo $feed_words; ?>" size="3" /></p>
        
        
       
        
		<?php 
	}

	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		
		$instance = array();
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['rss_url'] = strip_tags($new_instance['rss_url']);
		$instance['number'] = (int) $new_instance['number'];
		
		$instance['show_feed_title'] = ($new_instance['show_feed_title']==true);
		$instance['feed_words'] = (int) $new_instance['feed_words'];
		$instance['content_display'] = strip_tags($new_instance['content_display']);
		
		

		return $instance;
		
	}
}
if(!function_exists('rfw_init')){
	function rfw_init(){
		 register_widget( 'bdnews_bangla' );
		}
	}
?>