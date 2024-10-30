<?php
/*
Plugin Name: Popup - WordPress Popups
Plugin URI: http://www.socialintents.com
Description: Convert your site visitors into leads, subscribers, and sales with a popup. To get started: 1) Click the "Activate" link to the left of this description, 2) Go to your Popup plugin settings page, and register for a new account.
Version: 1.4.66
Author: Social Intents
Author URI: http://www.socialintents.com/
*/

$sicp_domain = plugins_url();
add_action('init', 'sicp_init');
add_action('admin_notices', 'sicp_notice');
add_filter('plugin_action_links', 'sicp_plugin_actions', 10, 2);
add_action('wp_footer', 'sicp_insert',4);
add_action('admin_footer', 'sicpRedirect');
define('SICP_DASHBOARD_URL', "https://www.socialintents.com/dashboard.do");
define('SICP_SMALL_LOGO',plugin_dir_url( __FILE__ ).'si-small.png');
function sicp_init() {
    if(function_exists('current_user_can') && current_user_can('manage_options')) {
        add_action('admin_menu', 'sicp_add_settings_page');
        add_action('admin_menu', 'sicp_create_menu');
    }
}
function sicp_insert() {

    global $current_user;
    if(strlen(get_option('sicp_widgetID')) == 32 ) {
	echo("\n<!-- Conversion Popup by www.socialintents.com -->\n<script type='text/javascript'>");
        echo("(function() {function socialintents(){\n");
        echo("    var siJsHost = ((\"https:\" === document.location.protocol) ? \"https://\" : \"http://\");\n");
        echo("    var s = document.createElement('script');s.type = 'text/javascript';s.async = true;s.src = siJsHost+'www.socialintents.com/api/unbounce/socialintents.js#".get_option('sicp_widgetID')."';\n");
        
        echo("    var x = document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s, x);};\n");
        echo("if (window.attachEvent)window.attachEvent('onload', socialintents);else window.addEventListener('load', socialintents, false);})();\n");
        echo("</script>\n");
    }
}

function sicp_notice() {
    if(!get_option('sicp_widgetID')) echo('<div class="error"><p><strong>'.sprintf(__('Your Conversion Popup Plugin is disabled. Please go to the <a href="%s">plugin settings</a> to register your free account.' ), admin_url('options-general.php?page=conversion-popup')).'</strong></p></div>');
}

function sicp_plugin_actions($links, $file) {
    static $this_plugin;
    $sicp_domain = plugins_url();
    if(!$this_plugin) $this_plugin = plugin_basename(__FILE__);
    if($file == $this_plugin && function_exists('admin_url')) {
        $settings_link = '<a href="'.admin_url('options-general.php?page=conversion-popup').'">'.__('Settings', $sicp_domain).'</a>';
        array_unshift($links, $settings_link);
    }
    return($links);
}

function sicp_add_settings_page() {
    function sicp_settings_page() {
        global $sicp_domain ?>
	<div class="wrap">
        <?php screen_icon() ?>
    <h2><?php _e('Conversion Popup by Social Intents', $sicp_domain) ?></h2>
    <div class="metabox-holder meta-box-sortables ui-sortable pointer">
        
        <div class="postbox" style="float:left;width:55%">
            <h3 class="hndle"><span id="sicp_noAccountSpan"><?php _e('Conversion Popup Free Registration', $sicp_domain) ?></span></h3>
           <p style="text-align:center"><?php wp_nonce_field('update-options') ?>
			<a href="http://www.socialintents.com/" title="Conversion Popup">
			<?php echo '<img src="'.plugins_url( 'socialintents.png' , __FILE__ ).'" height="100" "/> ';?></a></p>
<div id="sicp_register" class="inside" style="padding: -30px 10px">			
		<p><?php printf(__('Join 25,000+ companies using our plugins to grow sales, improve customer service, build subscribers, and reduce bounce rates. Visit %1$sSocial Intents%2$ssocialintents.com%3$s to 
				see how we can help you.', $sicp_domain), '<a href="
http://www.socialintents.com/" title="', '">', '</a>') ?></p>
 
			<b>Free Registration</b> <br>
			<input type="text" name="sicp_email" id="sicp_email" value="<?php echo(get_option('admin_email')) ?>" placeholder="Your Email" style="width:50%;margin:3px;" />
			<input type="text" name="sicp_name" id="sicp_name" value="<?php echo(get_option('user_nicename')) ?>" placeholder="Your Name" style="width:50%;margin:3px;" />
			<input type="password" name="sicp_password" id="sicp_password" value="" placeholder="Your Password" style="width:50%;margin:3px;" />
			<br><input type="button" name="sicp_inputRegister" id="sicp_inputRegister" value="Register" class="button-primary" style="margin:3px;" /> 
			
			
               
            </div>


<div id="sicp_registerComplete" class="inside" style="padding: -20px 10px;display:none;">
<p>Customize your popup with one of our 6 conversion templates by selecting the customize settings button below. </p><p>By default your popup is triggered when a visitor is leaving your site.  You can change this to trigger immediately, or after a configurable amount of time.</p>
		<p>
		<div style="text-align:center">
		<a href='https://www.socialintents.com/widgetCP.do?id=<?php echo(get_option('sicp_widgetID')) ?>' class="button button-primary" target="_blank">Customize My Settings</a>&nbsp;
		<a href='https://www.socialintents.com/dashboard.do' class="button button-primary" target="_blank">Dashboard</a>&nbsp;
		<a href='https://www.socialintents.com/emailReport.do' class="button button-primary" target="_blank">Reports</a>&nbsp;
<a href='https://www.socialintents.com/preview.do?wid=<?php echo(get_option('sicp_widgetID')) ?>' class="button button-primary" target="_blank">Preview Popup</a>&nbsp;
		<br><br><a id="changeWidget" class="" target="_blank">Enter Different Widget Key</a>&nbsp;
		</div>
		</p>* The popup is only triggered once per browser session.  Open a new browser window to test multiple times.

	    </div>
</div>

    <div id="sicp_enterwidget">
        <div class="postbox" style="float:left;width:40%;margin-right:10px">
            <h3 class="hndle"><span><?php _e('Already Registered?  Enter your Widget Key', $sicp_domain) ?></span></h3> 
            <div class="inside" style="padding: 0 10px">
                <form id="saveSettings" method="post" action="options.php">
                   <?php wp_nonce_field('update-options') ?>
                    <p><label for="sicp_widgetID"><?php printf(__('Enter your Key below to activate the plugin. <br><br> If you\'ve already signed up, <a href=\'https://www.socialintents.com/login.do\' target=\'_blank\'>login here</a> to grab your widget key under Apps --> Conversion Popup --> Edit Settings.  Then select the App Key tab.<br>', $sicp_domain), '<strong><a href="http://www.socialintents.com/" title="', '">', '</a></strong>') ?></label></p><br />
			<input type="text" name="sicp_widgetID" id="sicp_widgetID" placeholder="Your App Key" value="<?php echo(get_option('sicp_widgetID')) ?>" style="width:100%" />
                    <p class="submit" style="padding:0"><input type="hidden" name="action" value="update" />
                        <input type="hidden" name="page_options" value="sicp_widgetID" />
                        <input type="submit" name="sicp_submit" id="sicp_submit" value="<?php _e('Save Settings', $sicp_domain) ?>" class="button-primary" /> 
			<a href='https://www.socialintents.com/apps.do' class="button button-primary" target="_blank">Find My Key</a>
			
			</p>
<br><?php echo '<img src="'.plugins_url( 'app-key.png' , __FILE__ ).'" width="250" "/> ';?>
                 </form>
            </div>
        </div>
	    
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function($) {

var sicp_wid= $('#sicp_widgetID').val();
if (sicp_wid=='') 
{}
else
{
	$( "#sicp_enterwidget" ).hide();
	$( "#sicp_register" ).hide();
	$( "#sicp_registerComplete" ).show();
	$( "#sicp_noAccountSpan" ).html("Conversion Popup Plugin Settings");

}
$(document).on("click", "#sicp_inputSaveSettings", function () {
	$( "#saveDetailSettings" ).submit();
});
$(document).on("click", "#changeWidget", function () {
$( "#sicp_enterwidget" ).show();
});

$(document).on("click", "#sicp_inputRegister", function () {
var sicp_email= $('#sicp_email').val();
var sicp_name= $('#sicp_name').val();
var sicp_password= $('#sicp_password').val();
var url = 'https://www.socialintents.com/json/jsonSignup.jsp?type=conversionpopup&name='+sicp_name+'&email='+sicp_email+'&pw='+sicp_password+'&callback=?';
$.ajax({
   type: 'GET',
    url: url,
    async: false,
    jsonpCallback: 'jsonCallBack',
    contentType: "application/json",
    dataType: 'jsonp',
    success: function(json) {
	if (json.msg=='') {
         	$('#sicp_widgetID').val(json.key);
		alert("Thanks for registering!  Next, go ahead and customize your popup settings.");
		$( "#saveSettings" ).submit();
		
	}
	else {
		alert(json.msg);
	}
    },
    error: function(e) {
       
    }
});

});
});;
</script>
<?php }
$sicp_domain = plugins_url();
add_submenu_page('options-general.php', __('Conversion Popup', $sicp_domain), __('Conversion Popup', $sicp_domain), 'manage_options', 'conversion-popup', 'sicp_settings_page');
}
function addSicpLink() {
$dir = plugin_dir_path(__FILE__);
include $dir . 'options.php';
}
function sicp_create_menu() {
  $optionPage = add_menu_page('Conversion Popup', 'Conversion Popup', 'administrator', 'sicp_dashboard', 'addSicpLink', plugins_url('conversion-popup/si-small.png'));
}
function sicpRedirect() {
$redirectUrl = "https://www.socialintents.com/dashboard.do";
echo "<script> jQuery('a[href=\"admin.php?page=sicp_dashboard\"]').attr('href', '".$redirectUrl."').attr('target', '_blank') </script>";}
?>