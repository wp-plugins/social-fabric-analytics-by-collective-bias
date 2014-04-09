<?php
if ( !class_exists( 'CB_Analytics_Settings' ) ) {
    class CB_Analytics_Settings {
        const UID_FIELD_NAME = 'cb_ga_uid';
        const HOME_PAGE_URL_FIELD_NAME = 'cb_ga_url';

        const PLUGIN_NAME = 'cb_analytics';

        /**
         * Construct the plugin object
         */
        public function __construct() {
            // register actions
            add_action( 'admin_init', array( &$this, 'admin_init' ) );
            add_action( 'admin_menu', array( &$this, 'add_menu' ) );
        }

        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init() {
            // register your plugin's settings
            register_setting( self::PLUGIN_NAME.'-group', self::UID_FIELD_NAME );
            register_setting( self::PLUGIN_NAME.'-group', self::HOME_PAGE_URL_FIELD_NAME );

            // add your settings section
            add_settings_section(
                self::PLUGIN_NAME.'-section',
                'Collective Bias Analytics Settings',
                array( &$this, 'settings_section_cb_analytics' ),
                self::PLUGIN_NAME
            );

            // add your setting's fields
            add_settings_field(
                self::PLUGIN_NAME.'-'.self::HOME_PAGE_URL_FIELD_NAME,
                'Site URL',
                array( &$this, 'settings_field_input_text' ),
                self::PLUGIN_NAME,
                self::PLUGIN_NAME.'-section',
                array(
                    'field' => self::HOME_PAGE_URL_FIELD_NAME,
                    'default' => home_url(),
                    'size' => "50",
                    'description' => 'This populates from your site settings (see <a href="'.get_admin_url().'options-general.php">here</a>) change only if you need to or if your site changes'
                )
            );

            add_settings_field(
                self::PLUGIN_NAME.'-'.self::UID_FIELD_NAME,
                'Google Analytics Property ID',
                array( &$this, 'settings_field_input_text' ),
                self::PLUGIN_NAME,
                self::PLUGIN_NAME.'-section',
                array(
                    'field' => self::UID_FIELD_NAME
                )
            );
        }

        public function settings_section_cb_analytics() {
            // Think of this as help text for the section.
            echo "<h4>Instructions</h4>";
            echo <<<INSTRUCTIONS
<p>Please insert the code you received via email in the space below. Thanks.</p>
INSTRUCTIONS;
        }

        /**
         * This function provides text inputs for settings fields
         */
        public function settings_field_input_text( $args ) {
            // Get the field name from the $args array
            $field = $args['field'];
            $default = $args['default'];
            $size = $args['size'];
            $description = $args['description'];
            // Get the value of this setting
            $value = get_option( $field );
            if ( empty( $value ) ) {
                $value = $default;
            }
            // echo a proper input type="text"
            echo sprintf( '<input type="text" name="%s" id="%s" value="%s" size="%s" />', $field, $field, $value, $size );
            if ( !empty( $description ) ) {
?>
                <br/><span style='color:red;font-style:italic'><?php echo $description; ?></span>
                <?php
            }
        }

        /**
         * add a menu
         */
        public function add_menu() {
            // Add a page to manage this plugin's settings
            add_options_page(
                'Colective Bias Analytics Settings',
                'Collective Bias Analytics',
                'manage_options',
                self::PLUGIN_NAME,
                array( &$this, 'plugin_settings_page' )
            );
        }

        /**
         * Menu Callback
         */
        public function plugin_settings_page() {
            if ( !current_user_can( 'manage_options' ) ) {
                wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
            }

?>
                <div class="wrap">
                    <form method="post" action="options.php">
                        <?php @settings_fields( self::PLUGIN_NAME.'-group' ); ?>
                        <?php @do_settings_fields( self::PLUGIN_NAME.'-group' ); ?>

                        <?php do_settings_sections( self::PLUGIN_NAME ); ?>

                        <?php @submit_button(); ?>
                    </form>
                </div>
            <?php
        }
    }
}
