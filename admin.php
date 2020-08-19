<?php

class BigRadarSettings {
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        $this->options = get_option('bigradar') ?: ['app_id' => ''];
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
        if ($this->options['app_id']) {
            add_action('wp_footer', [ $this, 'add_footer' ]);
        }
    }

    public function add_footer()
    {
        ?>
    <!-- BigRadar --><script type="text/javascript">(function(d,c) {
            var b = d.body.appendChild(d.createElement('div')),
                f=b.appendChild(d.createElement('iframe'));
                b.style.display='none';f.src="";
            f.onload = function() {
                var fw=f.contentWindow,
                fd=f.contentDocument,
                s=fd.body.appendChild(fd.createElement('script'));
                fw.widget={frame:f,container:b,config:c};s.src='https://app.bigradar.io/widget.js';
            };
            return b;
        })(document, {
            app_id: '<?= $this->options['app_id'] ?>',
            // name: '<name>',
            // email: '<email>',
        });</script><!-- End BigRadar -->
        <?php
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'BigRadar Settings', 
            'BigRadar', 
            'manage_options', 
            'bigradar-setting', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        ?>
        <div class="wrap">
            <h1>BigRadar Settings</h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'bigradar' );
                do_settings_sections( 'my-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'bigradar', // Option group
            'bigradar', // Option name
        );

        add_settings_section(
            'setting_section_id', // ID
            'Widget Integration', // Title
            array( $this, 'print_section_info' ), // Callback
            'my-setting-admin' // Page
        );  

        add_settings_field(
            'app_id', 
            'App Id', 
            array( $this, 'title_callback' ), 
            'my-setting-admin', 
            'setting_section_id'
        );      
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function title_callback()
    {
        printf(
            '<input type="text" id="title" name="bigradar[app_id]" value="%s" placeholder="App ID" />',
            $this->options['app_id']
        );
    }
}
