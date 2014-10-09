<?php
/*
Plugin Name: Social Fabric Analytics From Collective Bias
Description: Plugin used for Collective Bias Analytics
Version: 1.7
Author: Chris Whittle
Author URI: http://www.collectivebias.com
*/

if ( !class_exists( 'CB_Analytics' ) ) {
	class CB_Analytics {
		public function __construct() {
			// Initialize Settings
			require_once plugin_dir_path( __FILE__ )."/settings.php";
			$CB_Analytics_Settings = new CB_Analytics_Settings();
			add_action( 'wp_footer', array(  &$this, 'display_footer' ) , 99999 );
			add_filter( "plugin_action_links_".plugin_basename( __FILE__ ), array(  &$this, 'settings_link' ) );
		}
		// Add settings link on plugin page
		function settings_link( $links ) {
			$settings_link = '<a href="options-general.php?page='.CB_Analytics_Settings::PLUGIN_NAME.'">Settings</a>';
			array_push( $links, $settings_link );
			return $links;
		}

		function display_footer() {
			$uid = get_option( CB_Analytics_Settings::UID_FIELD_NAME , "" );
			$home_page = get_option( CB_Analytics_Settings::HOME_PAGE_URL_FIELD_NAME , home_url() );

			$template = self::UNIVERSAL_TEMPLATE;

			if ( !empty( $uid ) && !empty( $template ) ) {
				echo str_replace(
					array( "[##UID##]", "[##URL##]" ),
					array(  $uid , $home_page ),
					$template
				);
			}
		}
		const UNIVERSAL_TEMPLATE = "
<script type='text/javascript'>
    /*Google Tag Manager for Collective Bias*/

    dataLayerCBias = [{
        'trackingID': '[##UID##]',
        'javaScriptVersion': 'analytics.js',
        'homePageURL': '[##URL##]'
    }];
</script>
<noscript>
    <iframe src='//www.googletagmanager.com/ns.html?id=GTM-PBN79J' height='0' width='0' style='display:none;visibility:hidden'></iframe>
</noscript>
<script type='text/javascript'>
    /*<![CDATA[*/
    (function(w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
            'gtm.start': new Date().getTime(),
            event: 'gtm.js'
        });
        var f = d.getElementsByTagName(s)[0],
            j = d.createElement(s),
            dl = l != 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src = '//www.googletagmanager.com/gtm.js?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayerCBias', 'GTM-PBN79J');
    /*]]>*/

    /*End Google Tag Manager for Collective Bias*/
</script>";
	}
	new CB_Analytics();
}
