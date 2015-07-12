<?php
add_action( 'add_meta_boxes', 'lf' );
        function lf() {
        if(current_user_can( 'edit_posts' ) ){
                add_meta_box( 'lf_meta', 'Language', 'lf_language_code_meta', 'post', 'side', 'high' );
                }else{}
                 //add_meta_box( 'lf_meta', 'Language CODE', 'lf_language_code_meta', 'page', 'side', 'high' );
                }

            function lf_language_code_meta( $post ) {
                $lf_language_code = get_post_meta( $post->ID, '_lf_language_code', true);
                // you can also make lf_language_code a input type text to use multiple languages

                	$language_fields=get_option("language_fields");
                	$arr=array_reverse($language_fields);
                	
                	
	foreach ($arr as $key => $value) {
      if(strstr($key,"language_name")){
			echo "This Article is in ".$value;
			}
			      if(strstr($key,"language_code")){
			?> <input type="radio" name="lf_language_code" value="<?php echo $value;?>"<?php if (esc_attr( $lf_language_code )==$value){ echo "checked=\"checked\"";} ?> /><br />
			<?php
			}
			
			
			
			}

                
        }

add_action( 'save_post', 'lf_save_project_meta' );
        function lf_save_project_meta( $post_ID ) {
            global $post;
            if( $post->post_type == "post" ) {
            if (isset( $_POST ) ) {
                update_post_meta( $post_ID, '_lf_language_code', strip_tags( $_POST['lf_language_code'] ) );
            }
        }
        }

function get_language_code_of_this()
{

global $wp_query;
$thePostID = (int)$wp_query->post->ID;
 $lf_language_code = get_post_meta( $thePostID, '_lf_language_code', true);
 if ($lf_language_code!='' && is_single() || is_home()) {
 return "dir=\"ltr\" lang=\"".$lf_language_code."\"";
 }
 else
 {
 return "dir=\"ltr\" lang=\"".get_bloginfo('language')."\"";
 }


}


add_filter('language_attributes', 'get_language_code_of_this');


function alter_the_query( $request ) {
    $dummy_query = new WP_Query();  // the query isn't run if we don't pass any query vars
    $dummy_query->parse_query( $request );

    // this is the actual manipulation; do whatever you need here
    
    $rewrite_hack=str_replace(get_bloginfo('url'),"", get_current_url_lf());
            // echo "<blink><h1>".$rewrite_hack."</h1></blink>";
		//echo "<blink><h1>works</h1></blink>";
   
        // get those two if-blocks for the languages you need 
        /*
        if ( $_GET['lang']=="de"  )
        $request['meta_value'] = 'de-DE';
        
         if ( $rewrite_hack=="/de/"  ){
        $request['meta_value'] = 'de-DE';

		}
		
		    if ( $_GET['lang']=="en" )
        $request['meta_value'] = 'en-EN';
        
                 if ( $rewrite_hack=="/en/"  ){
        $request['meta_value'] = 'en-EN';

		}

		    if ( $_GET['lang']=="pl" )
        $request['meta_value'] = 'pl-PL';
        
                 if ( $rewrite_hack=="/pl/"  ){
        $request['meta_value'] = 'pl-PL';

		}


 if ( $_GET['lang']=="es" )
        $request['meta_value'] = 'es-ES';
        
                 if ( $rewrite_hack=="/es/"  ){
        $request['meta_value'] = 'es-ES';

		}*/
		
		$language_fields=get_option("language_fields");
		
		
		$arr=array_reverse($language_fields);
                	
                	
		foreach ($arr as $key => $value) {
		if(strstr($key,"language_shortcode")){
		$num=str_replace("language_shortcode_","",$key);
		
			 if ( $_GET['lang']==$value ){
			 	$request['meta_value'] = $arr['language_code_'.$num];
			 }
			 
			 if ( $rewrite_hack=="/".$value."/"  ){
        		$request['meta_value'] = $arr['language_code_'.$num];

				}
			
			}
		}
		
		
    return $request;
}

add_filter( 'request', 'alter_the_query' );




function get_current_url_lf(){
$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
if ($_SERVER["SERVER_PORT"] != "80")
{
    $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
} 
else 
{
    $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
}
return $pageURL;

}





//add_action('init', 'lf_init');

add_filter('rewrite_rules_array', 'lf_rewrite_rules_array');

add_filter('query_vars', 'lf_query_vars');



function lf_init() {

	global $wp_rewrite;



	$wp_rewrite->flush_rules();

}

function lf_rewrite_rules_array($rewrite_rules) {

	global $wp_rewrite;


	
	$arr=get_option("language_fields");
	$custom = array();
	foreach ($arr as $key => $value) {
      if(strstr($key,"language_shortcode")){
			$custom[$value.'/?$'] = 'index.php?post_type=post';
			}
			} 
			

	return $custom + $rewrite_rules;

}

function lf_query_vars($query) {

	array_push($query, 'language');



	return $query;

}



//add_action('init', 'lf_init');
?>