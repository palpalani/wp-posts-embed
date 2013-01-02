/**
  * wp-widget.js
  *
  * Inserts an iframe into the DOM and calls the remote embed plugin
  * via a get parameter:
  * e.g http://www.envazhi.com/?embed=posts
  *
  */

(function() {

var jQuery;
if (window.jQuery === undefined || window.jQuery.fn.jquery !== '1.7.2') 
{
    var script_tag = document.createElement('script');
    script_tag.setAttribute("type","text/javascript");
    script_tag.setAttribute("src",
        "http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js");
    if (script_tag.readyState) 
    {
      script_tag.onreadystatechange = function () 
      { // For old versions of IE
          if (this.readyState == 'complete' || this.readyState == 'loaded') 
          {
              scriptLoadHandler();
          }
      };
    } 
    else 
    {
      script_tag.onload = scriptLoadHandler;
    }
    
    (document.getElementsByTagName("head")[0] || document.documentElement).appendChild(script_tag);
} 
else 
{
    // The jQuery version on the window is the one we want to use
    jQuery = window.jQuery;
    main();
}

function scriptLoadHandler() 
{
    jQuery = window.jQuery.noConflict(true);
    main(); 
}

function main() 
{ 
    jQuery(document).ready(function($) 
    { 
        var widget = window.e_widget_embed;
var em_show_title = window.e_show_title;
var em_post_count = window.e_post_count;
        var domain = encodeURIComponent(window.document.location);

	var posts = parseInt(em_post_count);
	if ( posts == 0 )
		posts = 2;
	var max_height = 60 * posts;
	if ( em_show_title == 'show' )
		max_height = max_height + 40;    	

        var iframeContent = '<iframe style="overflow-y: hidden;" \
                             height="'+ max_height +'" width="100%" frameborder="0" \
                             border="0" cellspacing="0" scrolling="no" \
                             src="http://www.envazhi.com/?em_embed=' + widget + '&em_show_title=' + em_show_title + '&em_post_count=' + em_post_count + '&'+ (Math.floor(Math.random()*99999) + new Date().getTime()) +'"></iframe>';
                             
        $("#embed-envazhi-widget-container").html(iframeContent);
    });
}

})();