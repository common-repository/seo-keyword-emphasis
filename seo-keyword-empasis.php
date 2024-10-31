<?php 
/*
Plugin Name: SEO Keyword Emphasis
Description: This plugin help you to emphasis the keywords inside your posts or pages. You have the option to make the keyword blold, italic, underline, change the background color, change the foreground color. You can use multiple keywords.
Author: Ovidiu Purdea
Version: 1.0.0
Author URI: http://www.wpguru.info
*/

add_action('admin_menu','text_setting_menu');
add_action('admin_init','highlighted_text_register_settings');


function seoke_word_highligher_install()
{
	
	 $pluginOptions = get_option('highlightedtext_options');

    if ( false === $pluginOptions ) {
        // Install plugin
		
		$highlightedtext_options['highlightedtext_bgcolor']='#ffff00';
		$highlightedtext_options['highlightedtext_fgcolor']='#000000';
		$highlightedtext_options['highlightedtext_active']='1';
		$highlightedtext_options['highlightedtext_type']='both';
		$highlightedtext_options['highlightedtext_case']='1';
		
		$default_css='.wh_highlighted
					  {
					   {background_color}
					   {foreground_color}
					   {font-style}
					   {font-weight}
					   {text-decoration}
					  }';


		$highlightedtext_options['highlightedtext_css']=$default_css;

		add_option('highlightedtext_options',$highlightedtext_options);



    } 

}

function seoke_word_highligher_uninstall()
{
	delete_option('highlightedtext_options');
}

register_activation_hook(__FILE__, 'seoke_word_highligher_install');

register_deactivation_hook(__FILE__, 'seoke_word_highligher_uninstall');


function text_setting_menu(){
	add_options_page('Keywords Settings', 'Emphasis keywords', 'manage_options', 'manage_settings', 'get_text_highlighted_settings');

}
function highlighted_text_register_settings(){
  register_setting( 'highlightedtext-options', 'highlightedtext_options' );
}

function get_text_highlighted_settings(){
?>
	<div class="wrap">
			<h2>Settings</h2>
    		 

             <form method="post" action="options.php"> 
				<?php settings_fields('highlightedtext-options'); ?>
				<?php $options = get_option('highlightedtext_options'); ?>
            <table class="form-table">
                <tr valign="top"><th scope="row">Emphasis keywords:</th>
                  <td><textarea rows="4" cols="80" name="highlightedtext_options[highlightedtext_name]"><?php  echo trim(stripslashes($options['highlightedtext_name'])); ?> </textarea><br /><span class="description">Insert the keywords comma separated. Es: seo, seo word, words seo.</span></td>
                </tr>
                <?php
				if($options['highlightedtext_bold']){
				$boldcheck="checked='checked'";
				}
				else
				{
					$boldcheck="";
				}
				
				if($options['highlightedtext_italic']){
				$italiccheck="checked='checked'";
				}
				else
				{
					$italiccheck="";
				}
				
				
				if($options['highlightedtext_underline']){
				$underlinecheck="checked='checked'";
				}
				else
				{
					$underlinecheck="";
				}
				
				?>
				<tr valign="top"><th scope="row">Apply</th>
                <td><input name="highlightedtext_options[highlightedtext_bold]" value="1" type="checkbox" <?php echo $boldcheck;?>  /> Bold 
                <input name="highlightedtext_options[highlightedtext_italic]" value="1" type="checkbox" <?php echo $italiccheck;?>  /> Italic 
                <input name="highlightedtext_options[highlightedtext_underline]" value="1" type="checkbox" <?php echo $underlinecheck;?>  /> Underline
                
                </td>
                </tr>
                
                <tr valign="top"><th scope="row">Background color</th>
                  <td><input class="regular-text" name="highlightedtext_options[highlightedtext_bgcolor]" type="text" value="<?php echo $options['highlightedtext_bgcolor']; ?>" /></td>
                </tr>
				<tr valign="top"><th scope="row">Foreground color</th>
                  <td><input class="regular-text" name="highlightedtext_options[highlightedtext_fgcolor]" type="text" value="<?php echo $options['highlightedtext_fgcolor']; ?>" /></td>
                </tr>
				<?php
				if($options['highlightedtext_active']){
				$check="checked='checked'";
				}
				else
				{
					$check="";
				}
				?>
				<tr valign="top"><th scope="row">Activate</th>
                <td><input name="highlightedtext_options[highlightedtext_active]" value="1" type="checkbox" <?php echo $check;?>  /><span class="description"> verify if it apply to pages and posts.</span></td>
                </tr>
            
            	<?php
				if($options['highlightedtext_case']){
				$check="checked='checked'";
				}
				else
				{
					$check="";
				}
				?>
				<tr valign="top"><th scope="row">Case Sensetive</th>
                <td><input name="highlightedtext_options[highlightedtext_case]" value="1" type="checkbox" <?php echo $check;?>  /><span class="description"> verify if it is case sensitive. (raccomanded)</span></td>
                </tr>
             
				<tr valign="top"><th scope="row">Apply for </th>
                <td>
                <select name="highlightedtext_options[highlightedtext_type]" id="highlightedtext_options[highlightedtext_type]">
                <option value="post" <?php if($options['highlightedtext_type']=='post') echo "selected='selected'" ?>>Articles</option>
                <option value="page" <?php if($options['highlightedtext_type']=='page') echo "selected='selected'" ?>>Pages</option>
                <option value="both" <?php if($options['highlightedtext_type']=='both') echo "selected='selected'" ?>>Both</option>
                </select>
                </td>
                </tr>
                 <tr valign="top"><th scope="row">Customised CSS Class</th>
                  <td><textarea rows="8" cols="80" name="highlightedtext_options[highlightedtext_css]"><?php  echo trim(stripslashes($options['highlightedtext_css'])); ?></textarea><br /><span class="description">Customised CSS Class apply to keywords.</span></td>
                </tr>
			</table>
                <p class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Save changes') ?>" />
				</p>
		</form>
	</div>
<?php
}


add_filter( 'the_content', 'apply_seoke_word_highligher' );

function apply_seoke_word_highligher( $content ) {
    
	 global $post;
		
	$post_type=get_post_type($post->ID);

	$options = get_option('highlightedtext_options');
	
	if($options['highlightedtext_type']!=$post_type and $options['highlightedtext_type']!='both')
	return $content;
	//echo "<pre>";print_r($options);
	if($options['highlightedtext_active'])
	{
	
	//echo "here=".$text."<br />";
	$text_name=explode(',',trim($options['highlightedtext_name']));
	//echo "<pre>";print_r($text_name);
	if(!empty($text_name)){
	for($i=0;$i<count($text_name);$i++){
	if(trim($text_name[$i])!=''){
	
		if(preg_match('~\b' . preg_quote($text_name[$i], '~') . '\b(?![^<]*?>)~',$content,$result))
		{
			$rep_html='<label class="wh_highlighted">'.$text_name[$i].'</label>';
		 	if($options['highlightedtext_case'])
			{
		
				$content = preg_replace('~\b' . preg_quote($text_name[$i], '~') . '\b(?![^<]*?>)~',$rep_html,$content);
				
			}
			else
			{
					$content = preg_replace('~\b' . preg_quote($text_name[$i], '~') . '\b(?![^<]*?>)~i',$rep_html,$content);
			
			}
		}
		
	}
	}
	}
	}

   
    return $content;
}

function seoke_word_highligher_css()
{
	
$options = get_option('highlightedtext_options');

$css=$options['highlightedtext_css'];
//{background_color}

if($options['highlightedtext_bgcolor']!='')
$css=str_replace('{background_color}',"background-color :".$options['highlightedtext_bgcolor'].";",$css);
else
$css=str_replace('{background_color}',"",$css);

if($options['highlightedtext_fgcolor']!='')
$css=str_replace('{foreground_color}',"color :".$options['highlightedtext_fgcolor'].";",$css);
else
$css=str_replace('{foreground_color}',"",$css);

if($options['highlightedtext_bold']!='')
$css=str_replace('{font-weight}',"font-weight:bold;",$css);
else
$css=str_replace('{font-weight}',"",$css);

if($options['highlightedtext_italic']!='')
$css=str_replace('{font-style}',"font-style : italic;",$css);
else
$css=str_replace('{font-style}',"",$css);

if($options['highlightedtext_underline']!='')
$css=str_replace('{text-decoration}',"text-decoration:underline;",$css);
else
$css=str_replace('{text-decoration}',"",$css);

?>
<style>
<?php
echo $css;
?>

</style>
<?php

}

add_action('wp_head','seoke_word_highligher_css');

?>