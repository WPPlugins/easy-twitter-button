<?php
/*
Plugin Name: Easy Twitter Button
Plugin URI: http://dolcepixel.com/easy-twitter-button/
Description: Add the official twitter button in your articles. Select the position, on what pages to show it and add custom css to the twitter div box.
Author: DolcePixel
Version: 1.2.2
Author URI: http://dolcepixel.com/
*/


$etb_default = get_option('etb_defaultset');
if ($etb_default != "yes") {
	update_option('etb_defaultset', "yes");
	update_option('etb_display', "vertical");
	update_option('etb_tweet_text', "1");
	update_option('etb_lang', "en");
	update_option('etb_position', "0");
	update_option('etb_showhome', "1");
	update_option('etb_showpages', "0");
	update_option('etb_showfeed', "1");
	update_option('etb_showexcerpt', "0");
	update_option('etb_showtwice', "0");
	update_option('etb_showfullline', "0");
	update_option('etb_jsposition', "2");
}

$etb_related_default = get_option('etb_related_default');
if ($etb_related_default != "yes") {
	update_option('etb_related_default', "yes");
	update_option('etb_related', "DolcePixel");
	update_option('etb_relateddesc', "We make beautiful and sweet WordPress Themes");
}



function etb_menu() {
	add_options_page('Easy Twitter Button Settings', 'Twitter Button', 'manage_options', 'easy-twitter-button', 'etb_settings');
}
add_action('admin_menu', 'etb_menu');


function etb_insert_scripts() {
	//I'm making sure the plugin will work on both windows and linux systems
	//I am also making sure the plugin will still work if you change the directory name
	$path = str_replace("\\", "/", __FILE__);
	$path = explode("/", $path);
array_pop($path);
	$path = array_reverse($path);
	$plugin_dir = $path[0];
	if ($plugin_dir != "plugins") {
		$plugin_dir = "/".$plugin_dir;
	} else {
		$plugin_dir = "";
	}
	echo "<link rel='stylesheet' href='".WP_PLUGIN_URL.$plugin_dir."/css/etb_style.css' type='text/css' media='all' />\n";
	echo "<script src='".WP_PLUGIN_URL.$plugin_dir."/js/easy-twitter-button.js' type='text/javascript'></script>";
}
add_action('admin_head', 'etb_insert_scripts');


function etb_settings() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	if (isset($_POST['action']) && $_POST['action'] == 'sent') {
		$display = (int)$_POST['display'];
		$display_array = array("vertical", "horizontal", "none");
		$display = $display_array[$display];
		$tweet_text = (int)$_POST['tweet_text'];
		$lang = (int)$_POST['lang'];
		$lang_array = array("en", "fr", "de", "es", "ja");
		$lang = $lang_array[$lang];
		$twitter_account = strip_tags($_POST['twitter_account']); $twitter_account = str_replace("@", "", $twitter_account);
		$related = strip_tags($_POST['related']); $related = str_replace("@", "", $related);
		$relateddesc = strip_tags($_POST['relateddesc']);
		$position = (int)$_POST['position'];
		$showhome = (int)$_POST['showhome'];
		$showpages = (int)$_POST['showpages'];
		$showfeed = (int)$_POST['showfeed'];
		$showexcerpt = (int)$_POST['showexcerpt'];
		$showtwice = (int)$_POST['showtwice'];
		$showfullline = (int)$_POST['showfullline'];
		$jsposition = (int)$_POST['jsposition'];
		$exclude = $_POST['exclude'];
		$exclude = preg_replace("/([^0-9,])/", "", $exclude);
		$exclude = explode(",", $exclude);
		$exclude = array_unique($exclude);
		sort($exclude);
		foreach ($exclude as $key=>$one) {
			if (!$one) {
				unset($exclude[$key]);
			}
		}
		$exclude = implode(",", $exclude);
		$custom_css = strip_tags($_POST['custom_css']);


		update_option('etb_display', $display);
		update_option('etb_tweet_text', $tweet_text);
		update_option('etb_lang', $lang);
		update_option('etb_twitter_account', $twitter_account);
		update_option('etb_related', $related);
		update_option('etb_relateddesc', $relateddesc);
		update_option('etb_position', $position);
		update_option('etb_showhome', $showhome);
		update_option('etb_showpages', $showpages);
		update_option('etb_showfeed', $showfeed);
		update_option('etb_showexcerpt', $showexcerpt);
		update_option('etb_showtwice', $showtwice);
		update_option('etb_showfullline', $showfullline);
		update_option('etb_jsposition', $jsposition);
		update_option('etb_exclude', $exclude);
		update_option('etb_custom_css', $custom_css);
		$ok = "ok";
	} //if action=sent
	$etb_display = get_option('etb_display');
	$etb_tweet_text = get_option('etb_tweet_text');
	$etb_lang = get_option('etb_lang');
	$etb_twitter_account = get_option('etb_twitter_account');
	$etb_related = get_option('etb_related');
	$etb_relateddesc = get_option('etb_relateddesc');
	$etb_position = get_option('etb_position');
	$etb_showhome = get_option('etb_showhome');
	$etb_showpages = get_option('etb_showpages');
	$etb_showfeed = get_option('etb_showfeed');
	$etb_showexcerpt = get_option('etb_showexcerpt');
	$etb_showtwice = get_option('etb_showtwice');
	$etb_showfullline = get_option('etb_showfullline');
	$etb_jsposition = get_option('etb_jsposition');
	$etb_exclude = get_option('etb_exclude');
	$etb_custom_css = get_option('etb_custom_css');


	if (isset($_POST['email']) && $_POST['email'] == 'sent') {
		$from = $_POST['etb_fromemail'];
		$etb_emailtext = $_POST['etb_emailtext'];
		$headers = 'From: '.$etb_emailtext.' <'.$etb_emailtext.'>' . "\r\n\\";
		$email = wp_mail('gaby@dolcepixel.com', 'Easy Twitter Button Message', $etb_emailtext, $headers);
		if ($email) {
			$emailok = "ok";
			unset($etb_emailtext);
		}
	}

	global $current_user;
	get_currentuserinfo();
?>
<div class="etbclear"></div>
<div class="etb_all">
<form action="options-general.php?page=easy-twitter-button" method="post">
<input type="hidden" name="action" value="sent" />
	<div class="etb_left">
	    <h3><span>1</span>Choose your button and customize it</h3>
        <div class="etb_tabs">
        	<ul>
	        	<li class="t1 active"><span>Button</span></li>
                <li class="t2"><span>Tweet text</span></li>
                <li class="t3"><span>Language</span></li>
			</ul>
        </div> <!-- TABS-->
		<div class="etbclear"></div>
        <div class="etb_content"><div class="etb_content1"><div class="etb_content2"><div class="etb_content3">
        	<div class="etb_buttons">
				<div class="etb_option etb_v">
		            <input type="radio" name="display" value="0" id="etb_vertical" <?php if($etb_display == "vertical") { echo 'checked="checked" '; } ?>/>
	    	        <label for="etb_vertical">Vertical count<br /><span class="etb_option1"></span></label>
	            </div>
				<div class="etb_option etb_h">
	    	        <input type="radio" name="display" value="1" id="etb_horizontal" <?php if($etb_display == "horizontal") { echo 'checked="checked" '; } ?>/>
	        	    <label for="etb_horizontal">Horizontal count<br /><span class="etb_option2"></span></label>
	            </div>
				<div class="etb_option last etb_n">
	    	        <input type="radio" name="display" value="2" id="etb_none" <?php if($etb_display == "none") { echo 'checked="checked" '; } ?>/>
	        	    <label for="etb_none">No count<br /><span class="etb_option3"></span></label>
	            </div>
                <input type="hidden" class="etb_displayhidden" value="<?php echo "et".substr($etb_display, 0, 1); ?>" />
			</div> <!-- BUTTONS -->
        	<div class="etb_tweet_text">
	            The text that will be included in the Tweet when a visitor clicks the button:<br /><br />
				<input type="radio" name="tweet_text" value="1" id="tweet_text1" <?php if($etb_tweet_text == "1") { echo 'checked="checked" '; } ?>/>
                <label for="tweet_text1">The title of the article or page (80 chars max)</label><br />
				<input type="radio" name="tweet_text" value="2" id="tweet_text2" <?php if($etb_tweet_text == "2") { echo 'checked="checked" '; } ?>/>
                <label for="tweet_text2">The first 80 characters of the article's content</label>
			</div> <!-- TWEET TEXT -->
        	<div class="etb_language">
				This is the language that the button will render in on your website.<br />
				People will see the Tweet dialog in their selected language for Twitter.com.<br /><br />
				<label for="lang">Language: </label>
				<select id="lang" name="lang">
					<option value="0"<?php if($etb_lang == "en") { echo ' selected="selected"'; } ?>>English</option>
					<option value="1"<?php if($etb_lang == "fr") { echo ' selected="selected"'; } ?>>French</option>
					<option value="2"<?php if($etb_lang == "de") { echo ' selected="selected"'; } ?>>German</option>
					<option value="3"<?php if($etb_lang == "es") { echo ' selected="selected"'; } ?>>Spanish</option>
					<option value="4"<?php if($etb_lang == "ja") { echo ' selected="selected"'; } ?>>Japanese</option>
				</select>
			</div> <!-- LANGUAGE -->
            <div class="etbclear"></div>
        </div></div></div></div> <!-- CONTENT-->
    	<h3><span>2</span>Recommend people to follow</h3>
        <div class="etb_content etb_recommend"><div class="etb_content1"><div class="etb_content2"><div class="etb_content3">
			Recommend up to two Twitter accounts for users to follow after they share content from your website. These accounts could include your own, or that of a contributor or a partner.<br /><br />
			<label for="twitter_account">Your Twitter account</label>
			<input type="text" name="twitter_account" id="twitter_account" value="<?php echo $etb_twitter_account; ?>">
            <br />
			<label for="related">Related account</label>
			<input type="text" id="related" name="related" value="<?php echo $etb_related; ?>">
            <br />
			<label for="relateddesc">Related account description</label>
			<input type="text" id="relateddesc" name="relateddesc" value="<?php echo $etb_relateddesc; ?>">
        </div></div></div></div> <!-- CONTENT-->
	    <h3><span>3</span>Choose button position and display options</h3>
        <div class="etb_content etb_position"><div class="etb_content1"><div class="etb_content2"><div class="etb_content3">
			<div class="etb_option">
	            <input type="radio" name="position" value="0" id="tr" <?php if($etb_position == "0") { echo 'checked="checked" '; } ?>/>
    	        <label for="tr">Top right<br /><span class="etb_option1"></span></label>
            </div>
			<div class="etb_option">
    	        <input type="radio" name="position" value="1" id="tl" <?php if($etb_position == "1") { echo 'checked="checked" '; } ?>/>
        	    <label for="tl">Top left<br /><span class="etb_option2"></span></label>
            </div>
			<div class="etb_option">
    	        <input type="radio" name="position" value="2" id="br" <?php if($etb_position == "2") { echo 'checked="checked" '; } ?>/>
        	    <label for="br">Bottom right<br /><span class="etb_option3"></span></label>
            </div>
			<div class="etb_option last">
    	        <input type="radio" name="position" value="3" id="bl" <?php if($etb_position == "3") { echo 'checked="checked" '; } ?>/>
        	    <label for="bl">Bottom left<br /><span class="etb_option4"></span></label>
            </div>
            <div class="etbclear"></div>
            <p><input type="checkbox" name="showhome" value="1" <?php if($etb_showhome == "1") { echo 'checked="checked" '; } ?>/> Display on homepage?</p>
            <p><input type="checkbox" name="showpages" value="1" <?php if($etb_showpages == "1") { echo 'checked="checked" '; } ?>/> Display on pages?</p>
            <p><input type="checkbox" name="showfeed" value="1" <?php if($etb_showfeed == "1") { echo 'checked="checked" '; } ?>/> Display in feed?</p>
            <p><input type="checkbox" name="showexcerpt" value="1" <?php if($etb_showexcerpt == "1") { echo 'checked="checked" '; } ?>/> Display in excerpts?</p>
            <p><input type="checkbox" name="showtwice" value="1" <?php if($etb_showtwice == "1") { echo 'checked="checked" '; } ?>/> Display above and under the article?</p>
            <p><input type="checkbox" name="showfullline" value="1" <?php if($etb_showfullline == "1") { echo 'checked="checked" '; } ?>/> Should the button occupy a full line?</p>
            <p>Place the javascript file from twitter.com in the 
				<select id="jsposition" name="jsposition">
					<option value="1"<?php if($etb_jsposition == "1") { echo ' selected="selected"'; } ?>>Header</option>
					<option value="2"<?php if($etb_jsposition == "2") { echo ' selected="selected"'; } ?>>Footer</option>
				</select><br />
					<small>If you see a "Tweet This!" link instead of the button it's because you don't have the tag wp_footer() in your footer file. Choosing "header" might solve this.</small></p>

			<p>
Insert the IDs of the pages/posts/custom_post_types that you would like to exclude the twitter button from
<input type="text" size="50" name="exclude" value="<?php echo $etb_exclude; ?>"><br />
<small>separate each id by comma. letters and spaces will be stripped</small>
			</p>
			<p>
Enter your custom css style for the div box where the button will pe placed
<input type="text" size="50" name="custom_css" value="<?php echo $etb_custom_css; ?>"><br />
<small>EX: padding: 3px; border: 1px #000 solid; margin: 2px</small>
			</p>
        </div></div></div></div> <!-- CONTENT-->
    </div> <!-- LEFT -->
	<div class="etb_right">
	    <h3><span>4</span>Preview button</h3>
        <div class="etb_content etb_position"><div class="etb_content1"><div class="etb_content2"><div class="etb_content3">
        	<?php $etb_lang_css_array = array("en", "fr", "de", "es", "ja"); $etb_lang_css = array_search($etb_lang, $etb_lang_css_array); ?>
	        <div class="etb_preview"><span class="<?php echo "et".substr($etb_display, 0, 1).$etb_lang_css; ?>"></span></div> <!-- PREVIEW -->
        </div></div></div></div> <!-- CONTENT-->
        <div style="float: left">
        	<input type="submit" name="submit" class="save-button" value="Save" /><?php if ($ok == "ok") { echo '<div class="etb_ok">saved</div>'; } ?>
        </div>


		<div style="float: right; text-align: right;">
        	Check our website at <a href="http://dolcepixel.com" target="_blank">DolcePixel.com</a>
		</div>

    </div> <!-- RIGHT -->
</form>
	<div class="etb_send_email">
		<div class="etb_email_buttons">
			<div class="button-secondary etb_feedback etb_emailbutton">Send me feedback or bugs</div>
			<div class="button-secondary etb_ideea etb_emailbutton">Got a plugin ideea? I'd love to hear it</div>
			<div class="etbclear"></div>
        	<?php
	        if (isset($_POST['email']) && $_POST['email'] == 'sent') {
				if ($emailok == "ok") {
					echo "<div class='etb_ok'>Your message was sent!</div>";
				} else {
					echo "<div class='etb_err'>The message has not been sent. Please try again.</div>";
				}
        	}
			?>
    	</div> <!-- EMAIL BUTTONS -->
		<div class="etb_email_form">
			<div class="etb_closeemail">&nbsp;</div>
        	<div class="etbclear"></div>
		    <form action="options-general.php?page=easy-twitter-button" method="post">
    	    <input type="hidden" name="email" value="sent" />
    			<div class="alignleft">
	    			Email to: gaby@dolcepixel.com<div class="etbclear5"></div>
    	    		Email from: <input type="text" name="etb_fromemail" value="<?php bloginfo('admin_email'); ?>" style="color: red;" /><div class="etbclear5"></div>
		        </div>
    		    <div class="alignright">
        			<input type="submit" name="etb_sendemail" class="button-secondary" value="send email" />
	        	</div>
				<textarea name="etb_emailtext" cols="43" rows="10"><?php echo $etb_emailtext; ?></textarea>
		    </form>
		</div> <!-- EMAIL FORM -->
	</div> <!-- SEND EMAIL-->
	<div class="etbclear"></div>
</div> <!-- ALL -->
<?php
}

function etb_add_button($text) {
	$etb_display = get_option('etb_display');
	$etb_tweet_text = get_option('etb_tweet_text');
	$etb_lang = get_option('etb_lang');
	$etb_twitter_account = get_option('etb_twitter_account');
	$etb_related = get_option('etb_related');
	$etb_relateddesc = get_option('etb_relateddesc'); if ($etb_relateddesc != "") { $etb_relateddesc = ':'.$etb_relateddesc; }
	$etb_position = get_option('etb_position');
	$etb_showhome = get_option('etb_showhome');
	$etb_showpages = get_option('etb_showpages');
	$etb_showfeed = get_option('etb_showfeed');
	$etb_showexcerpt = get_option('etb_showexcerpt');
	$etb_showtwice = get_option('etb_showtwice');
	$etb_showfullline = get_option('etb_showfullline');

	$chars = "80";
	if ($etb_tweet_text == "1") {
		$tweettext = strip_tags(get_the_title());
		if ( strlen($tweettext) > $chars ) { $dots = "..."; }
		$etb_datatext = substr(trim($tweettext), 0, $chars).$dots;
	} else if ($etb_tweet_text == "2") {
		$tweettext = strip_tags(get_the_content());
		if ( strlen($tweettext) > $chars ) { $dots = "..."; }
		$etb_datatext = substr(trim($tweettext), 0, $chars).$dots;
	}

	if ($etb_position == "1" || $etb_position == "3") {
		$style = "float: left; padding-right: 5px;".get_option('etb_custom_css');
		$align = "left";
	}
	if ($etb_position == "0" || $etb_position == "2") {
		$style = "float: right; padding-left: 5px;".get_option('etb_custom_css');
		$align = "right";
	}
	if ($etb_showfullline == "1") {
		$style = "display: block;".get_option('etb_custom_css');
		if ($etb_position == "1" || $etb_position == "3") {
			$style = $style." text-align: left;";
		}
		if ($etb_position == "0" || $etb_position == "2") {
			$style = $style." text-align: right;";
		}
	}

	$path = str_replace("\\", "/", __FILE__);
	$path = explode("/", $path);
array_pop($path);
	$path = array_reverse($path);
	$plugin_dir = $path[0];
	if ($plugin_dir != "plugins") {
		$plugin_dir = "/".$plugin_dir;
	} else {
		$plugin_dir = "";
	}

	$button = "\n".'<div class="twitterbutton" style="'.$style.'"><a href="http://twitter.com/share" class="twitter-share-button" data-count="'.$etb_display.'" data-text="'.$etb_datatext.'" data-via="'.$etb_twitter_account.'" data-url="'.get_permalink().'" data-lang="'.$etb_lang.'" data-related="'.$etb_related.$etb_relateddesc.'"></a></div>';

	if (is_feed()) {
		$button = "\n".'<div class="twitterbutton" style="'.$style.'"><a href="http://twitter.com/share?url='.get_permalink().'&amp;text='.get_the_title().'&amp;via='.$etb_twitter_account.'&amp;related='.$etb_related.'"><img align="'.$align.'" src="'.WP_PLUGIN_URL.'/'.$plugin_dir.'/i/buttons/'.$etb_lang.'/tweetn.png" style="border: none;" alt="" /></a></div>'."\n";
	}

	$etb_exclude = get_option('etb_exclude');
	$etb_exclude = explode(",", $etb_exclude);
	if (is_front_page() && $etb_showhome == "0") {
		$button = "";
	} elseif (is_page() && $etb_showpages == "0") {
		$button = "";
	} elseif (is_feed() && $etb_showfeed == "0") {
		$button = "";
	} elseif (in_array(get_the_ID(), $etb_exclude)) {
		$button = "";
	}

	if ($etb_position == "0" || $etb_position == "1") {
		$text = $button.$text;
		if ($etb_showtwice == "1") {
			$text = $text.$button;
		}
	}
	if ($etb_position == "2" || $etb_position == "3") {
		$text = $text.$button;
		if ($etb_showtwice == "1") {
			$text = $button.$text;
		}
	}
	return $text;
}


function etb_twitter_js() {
	echo "\n".'<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>'."\n";
}
function etb_removetw($text) {
	$share = substr($text, 0, 5);
	if ($share == "Share") {
		$text = substr($text, 5);
		$text = trim($text);
	}
	echo $text;
}

	$etb_showfeed = get_option('etb_showfeed');
	$etb_showexcerpt = get_option('etb_showexcerpt');
	$etb_jsposition = get_option('etb_jsposition');

add_filter('the_content', 'etb_add_button');
if ($etb_showexcerpt == "1") {
	add_filter('the_excerpt', 'etb_add_button');
	add_filter('the_excerpt', 'etb_removetw', 99);
}
add_filter('the_excerpt_rss', 'etb_add_button');

if ($etb_jsposition == "1") {
	add_action('wp_head', 'etb_twitter_js');
} else {
	add_action('wp_footer', 'etb_twitter_js');
}



function etb_settingslink($links, $file) {
	static $this_plugin;
	if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);

	if ($file == $this_plugin){
		$settings_link = '<a href="options-general.php?page=easy-twitter-button"><b>Settings</b></a>';
		array_push($links, $settings_link);
	}
	return $links;
}
add_filter('plugin_action_links', 'etb_settingslink', 10, 10);


/*
Now, until the break of day,
Through this house each fairy stray.
To the best bride-bed will we,
Which by us shall blessed be;
*/
?>