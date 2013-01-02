<?php
/**
 * Plugin Name: Wp Posts embed
 * Description: Allow to embed WordPress content in an iframe on other WordPress
 * Version: 1.0
 * Author: palPalani
 * Author URI: http://www.m3webwrae.com
 */
 
 /*
<script type="text/javascript">
  var e_widget_embed = 'posts';
  var e_show_title = 'show';
  var e_post_count = '5';
</script>
<script src="http://www.m3webwrae.com/wp-content/plugins/wp-posts-embed/wp-widget.js" type="text/javascript"></script>
<div id="embed-envazhi-widget-container"></div>
*/

class WPWidgetEmbed  
{
    public function __construct()
    {
        add_action('template_redirect', array($this, 'catch_widget_query'));
        add_action('init', array($this, 'widget_add_vars'));
    }
    

    public function widget_add_vars() 
    { 
        global $wp; 
        $wp->add_query_var('em_embed'); 
        $wp->add_query_var('em_show_title');
	$wp->add_query_var('em_post_count');
    }
    
    private function export_posts( $show_title = false, $post_count = 2)
{
    $outstring  = '<html>';
    $outstring .= '<head><style>';
    $outstring .= '.teaser_envazhi ul {
             padding:0;
             margin:0;
          }
          
          .teaser_envazhi li > a {
             text-decoration: none;
             font-family: Arial, Helvetica, Sans-serif;
             font-size:12px;
             
          }
           
          .teaser_envazhi li {
             border-bottom: 1px solid #c0c0c0;
             padding: 3px 0 3px 0;
	     list-style: none;
          }
	   .teaser_envazhi li img{
	     width: 50px;
	     height: 50px;
	     float: left;
	     margin-right: 10px;
	   }
	.teaser_envazhi li h4{
	    margin: 0 0 3px;
	}
    
          .teaser_envazhi #widget-posts {
             width: 100%;
             padding: 0px;
             margin: 0px;
          }';
    $outstring .= '</style></head><body class="teaser_envazhi">';

if ( empty($post_count))
	$post_count = 2;

    $args = array(
        'numberposts' => $post_count,
        'offset' => 0,
        'category' => 0,
        'orderby' => 'post_date',
        'order' => 'DESC',
        'post_type' => 'post',
        'post_status' => 'publish',
        'suppress_filters' => true
    );
    
    $recent_posts = wp_get_recent_posts($args);
    
    $outstring .= '<div id="widget-posts">';

if (  $show_title == 'show' ) {
	$outstring .= '<h2>&#2958;&#2985;&#3021;&#2997;&#2996;&#3007;...&#2992;&#2972;&#3007;&#2985;&#3007; &#2997;&#2996;&#3007;</h2>';    
}
$outstring .= '<ul>';
    foreach($recent_posts as $recent)
    {
        $outstring .= '<li>';
	if ( has_post_thumbnail() ) {
		$outstring .= get_the_post_thumbnail( $recent["ID"], 'thumbnail', array( 'class' => 'alignleft' ));
		
	}
	else {
		$outstring .= '<img src="' . get_bloginfo( 'stylesheet_directory' ) . '/images/thumbnail.png" class="alignleft" />';
	}
	$outstring .= '<a rel="bookmark" target="_blank" href="' . get_permalink($recent["ID"]) . '" title="' . $recent["post_title"]. '">';
	$outstring .= '<h4>'. $recent["post_title"] .'</h4>';
	$outstring .= '</a>';
	$outstring .= '</li>';
    }
    
    $outstring .= '</ul></div>';
    $outstring .= '</body></html>';
    
    return $outstring;
}

    public function catch_widget_query()
   {
    if(!get_query_var('em_embed')) return;
    
    if(get_query_var('em_embed') == 'posts')
    { 

        $cache_name = 'm3widget_posts_'. get_query_var('em_show_title') .'_'. get_query_var('em_post_count');
        $cached = get_transient( $cache_name );
        if(empty($cached))
        {
            $data_to_embed = $this->export_posts( get_query_var('em_show_title'), get_query_var('em_post_count') );
            set_transient($cache_name, $data_to_embed, 60 * 60);
            echo $data_to_embed;
        }
        else
        {
            echo $cached;
        }
    }
    
    exit();
   }
}
 
$widget = new WPWidgetEmbed();