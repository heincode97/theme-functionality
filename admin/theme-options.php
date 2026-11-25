<?php
/*  Digital DOT theme framework options */

class TTD_Options {

    private $themename = "DOTs Framework";
    private $shortname = THEME_PREFIX;
    private $version = "1";
    private $option_group = THEME_OPTION_GROUP;
    private $option_name = THEME_OPTIONS;
    private $pagehook;

    function __construct() {
        add_action('admin_menu', array(&$this, 'admin_menu'));
        add_action('admin_init', array(&$this, 'admin_init'));
    }

    function admin_menu() {
        $this->pagehook = add_menu_page(__($this->themename . ' Options', 'roulette'), __($this->themename, 'roulette'), 'manage_options', basename(__FILE__), array(&$this, 'load_settings_page'), THEME_FUNCTIONALITY_URL . 'assets/images/dashboard.png', 3);
        add_action("admin_head-" . $this->pagehook, array(&$this, 'on_load_page'));
    }

    function admin_init() {
        register_setting($this->option_group, $this->option_name);
    }

    function on_load_page() {
        wp_enqueue_style('theme-options-css',  THEME_FUNCTIONALITY_URL . 'assets/css/theme-options.css', '', ASSET_VERSION, 'all');
        wp_register_script('jscolor', THEME_FUNCTIONALITY_URL . 'assets/js/jscolor/jscolor.js', 'jquery', '1.3', false);
        wp_enqueue_script('jscolor');
        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('theme-option-js', THEME_FUNCTIONALITY_URL . 'assets/js/theme-options.js', 'jquery', ASSET_VERSION, true);                
    }

    function get_theme_option_list() {
        $poll_list = $this->get_polls_list();
        $roulette_options = array(
            //General settings            
            array(
                "name" => __('Site Settings', 'roulette'),
                "type" => "section"
            ),
            array(
                "name" => __('Site settings.', 'roulette'),
                "type" => "section-desc"
            ),
            array(
                "type" => "open"
            ),
            
//           array(
//                "name" => __('Number of slides for Carousel', 'roulette'),
//                "desc" => 'It is only applicable if the featured articles are displayed as slider.',
//                "id" => "no_of_carousel_slide",
//                "type" => "text",
//                "std" => "3"
//            ),
            
//            array(
//                "name" => __('Page IDs for Home Page', 'roulette'),
//                "desc" => 'It will show the page ids in home page.',
//                "id" => "page_ids_for_home",
//                "type" => "text",
//                "std" => ""
//            ),
            array(
                "name" => __('Add Your Logo Here', 'roulette'),
                "desc" => 'It will show custom logo.',
                "id" => "logo",
                "type" => "image",
                "std" => ""
            ),
            array(
                "name" => __('Add Address Here', 'roulette'),
                "desc" => 'It will show address.',
                "id" => "info_address",
                "type" => "textarea",
                "std" => ""
            ),
            array(
                "name" => __('Add Hotline Number Here', 'roulette'),
                "desc" => 'It will show hotline number.',
                "id" => "info_hotline",
                "type" => "text",
                "std" => ""
            ),
            
            array(
                "name" => __('Add Phone Number Here', 'roulette'),
                "desc" => 'It will show phone number.',
                "id" => "info_phone",
                "type" => "text",
                "std" => ""
            ),
            
            array(
                "name" => __('Add Fax Number Here', '   roulette'),
                "desc" => 'It will show fax number.',
                "id" => "info_fax",
                "type" => "text",
                "std" => ""
            ),

            array(
                "name" => __('Add Viber Number Here', '   roulette'),
                "desc" => 'It will show Viber number.',
                "id" => "info_viber",
                "type" => "text",
                "std" => ""
            ),
            
            array(
                "name" => __('Add Email Address Here', 'roulette'),
                "desc" => 'It will show email address.',
                "id" => "info_email",
                "type" => "text",
                "std" => ""
            ),
                       
            array(
                "type" => "close"
            ),
            array(
                "name" => __('Social Media Links', 'roulette'),
                "type" => "section"
            ),
            array(
                "name" => __('Set up social links.', 'roulette'),
                "type" => "section-desc"
            ),
            array(
                "type" => "open"
            ),
            array(
                "name" => __('Facebook Page', 'roulette'),
                "desc" => __('Link to your Facebook page, <strong>with http://</strong>.', 'roulette'),
                "id" => "facebookid",
                "type" => "text",
                "std" => ""
            ),
            array(
                "name" => __('Golden Place Profile', 'roulette'),
                "desc" => __('Link to your Golden Place Profile page, <strong>with http://</strong>.', 'roulette'),
                "id" => "goldenplaceid",
                "type" => "text",
                "std" => ""
            ),
//            array(
//                "name" => __('Twitter Username', 'roulette'),
//                "desc" => __('Your Twitter username, please. It will be shown in the navigation bar. Leaving it blank will keep the Twitter icon supressed.'),
//                "id" => "twitterid",
//                "type" => "text",
//                "std" => ""
//            ),
//            array(
//                "name" => __('Google+ URL', 'roulette'),
//                "desc" => __('Link to Google+ page, <strong>with http://</strong>. It will be shown in the navigation bar. Leaving it blank will keep the Google+ icon supressed.'),
//                "id" => "googleplus",
//                "type" => "text",
//                "std" => ""
//            ),
//            array(
//                "name" => __('Flickr Username', 'roulette'),
//                "desc" => __('Flickr username, please. It will be shown in the navigation bar. Leaving it blank will keep the Flickr icon supressed.'),
//                "id" => "flickr",
//                "type" => "text",
//                "std" => ""
//            ),
//            array(
//                "name" => __('YouTube Username', 'roulette'),
//                "desc" => __('Your YouTube username, please. It will be shown in the navigation bar. Leaving it blank will keep the YouTube icon supressed.'),
//                "id" => "youtubeid",
//                "type" => "text",
//                "std" => ""
//            ),
//            array(
//                "name" => __('Tumblr URL', 'roulette'),
//                "desc" => __('Link to Tumblr blog, <strong>with http://</strong>. It will be shown in the navigation bar. Leaving it blank will keep the Tumblr icon supressed.'),
//                "id" => "tumblrurl",
//                "type" => "text",
//                "std" => ""
//            ),
            array(
                "type" => "close"
            ),
            array(
                "name" => __('API Key & Other Settings', 'roulette'),
                "type" => "section"
            ),
            array(
                "name" => __('Setting up API Key(s)', 'roulette'),
                "type" => "section-desc"
            ),
            array(
                "type" => "open"
            ),           
//            array(
//                "name" => __('Facebook API Key', 'roulette'),
//                "desc" => '',
//                "id" => "facebook_api_key",
//                "type" => "text",
//                "std" => "",
//                "class" => "full"
//            ),
//            array(
//                "name" => __('Linkedin API Key', 'roulette'),
//                "desc" => '',
//                "id" => "linkedin_api_key",
//                "type" => "text",
//                "std" => "",
//                "class" => "full"
//            ),
            array(
                "name" => __('Google Analytics <br />(UA-XXXXXX-X)', 'roulette'),
                "desc" => '',
                "id" => "google_analytics",
                "type" => "text",
                "std" => "",
                "class" => 'full'
            ),
            array(
                "name" => __('Decompress HTML', 'roulette'),
                "desc" => 'Tick to de-compress HTML file',
                "id" => "compress_html",
                "type" => "checkbox",
                "std" => ""
            ),
            
            array(
                "type" => "close"
            ),
        );
        return $roulette_options;
    }

//end functoin

    function get_polls_list() {
        global $wpdb;
        $polls = $wpdb->get_results("SELECT * FROM $wpdb->pollsq  ORDER BY pollq_timestamp DESC");
        $o = "<select name='poll_selection' class='poll_selection'>" . "\n";
        $o .= "<option value=''>-- Select --</option>" . "\n";
        foreach ($polls as $p) {
            $o .= "<option value='" . $p->pollq_id . "'>" . $p->pollq_question . "</option>" . "\n";
        }
        $o .= "</select>" . "\n";
        return $o;
    }

    function load_settings_page() {
        $option_group = $this->option_group;
        $option_name = $this->option_name;
        $roulette_options = $this->get_theme_option_list();
        ?>
        <div class="wrap">
            <div class="options_wrap">
                <div class="icon32" id="icon-themes"><br></div>
                <h2><?php echo $this->themename; ?>
        <?php _e('Theme Options', 'roulette'); ?>
                </h2>
                <p class="top-notice">
        <?php _e('Customize your WordPress blog with these settings. ', 'roulette'); ?>
                </p>
                    <?php if (isset($_POST['reset'])): ?>
                        <?php
                        // Delete Settings

                        delete_option($option_name);
                        wp_cache_flush();
                        ?>
                    <div class="updated fade">
                        <p><strong>
                    <?php _e($this->themename . ' options reset.'); ?>
                            </strong> </p>
                    </div>
        <?php elseif (isset($_REQUEST['settings-updated'])): ?>
                    <div class="updated fade">
                        <p><strong>
            <?php _e($this->themename . ' options saved.'); ?>
                            </strong></p>
                    </div>
        <?php endif; ?>
                <form method="post" action="options.php">
                            <?php settings_fields($option_group); ?>
        <?php $options = get_option($option_name); ?>
                <?php
                foreach ($roulette_options as $value) {
                    if (isset($value['id'])) {
                        $valueid = $value['id'];
                    }
                    switch ($value['type']) {
                        case "section":
                            ?>
                                <div class="section_wrap">
                                    <h3 class="section_title"><?php echo $value['name']; ?>
                                <?php
                                break;
                            case "section-desc":
                                ?>
                                        <span><?php echo $value['name']; ?></span></h3>
                                    <div class="section_body">
                                        <?php
                                        break;
                                    case "line-break":
                                        ?>
                                        <div class="line-break"></div>
                                        <?php
                                        break;
                                    case 'text':
                                        ?>
                                        <div class="options_input options_text" id="option_<?php echo $valueid; ?>">
                                        <?php if ($value['desc'] != '') : ?>
                                                <div class="options_desc"><?php echo $value['desc']; ?></div>
                                            <?php endif; ?>
                                            <span class="labels<?php echo $value['class'] ? ' ' . $value['class'] : ''; ?>">
                                                <label for="<?php echo $option_name . '[' . $valueid . ']'; ?>"><?php echo $value['name']; ?></label>
                                            </span>
                                            <input<?php echo $value['class'] ? ' class="full"' : ''; ?> name="<?php echo $option_name . '[' . $valueid . ']'; ?>" id="<?php echo $option_name . '[' . $valueid . ']'; ?>" type="<?php echo $value['type']; ?>" value="<?php if (isset($options[$valueid])) {
                            esc_attr_e($options[$valueid]);
                        } else {
                            esc_attr_e($value['std']);
                        } ?>" />
                                        </div>
                                        <?php
                                        break;
                                    case 'readonly':
                                        ?>
                                        <div class="options_input options_text" id="option_<?php echo $valueid; ?>">
                                            <?php if ($value['desc'] != '') : ?>
                                                <div class="options_desc"><?php echo $value['desc']; ?></div>
                    <?php endif; ?>
                                            <span class="labels<?php echo $value['class'] ? ' ' . $value['class'] : ''; ?>">
                                                <label for="<?php echo $option_name . '[' . $valueid . ']'; ?>"><?php echo $value['name']; ?></label>
                                            </span>
                                            <input<?php echo $value['class'] ? ' class="full"' : ''; ?> name="<?php echo $option_name . '[' . $valueid . ']'; ?>" id="<?php echo $option_name . '[' . $valueid . ']'; ?>" type="text" readonly value="<?php if (isset($options[$valueid])) {
                        esc_attr_e($options[$valueid]);
                    } else {
                        esc_attr_e($value['std']);
                    } ?>" />
                                        </div>
                                            <?php
                                            break;
                                        case 'colour':
                                            ?>
                                        <div class="options_input options_text" id="option_<?php echo $valueid; ?>">
                    <?php if ($value['desc'] != '') : ?>
                                                <div class="options_desc"><?php echo $value['desc']; ?></div>
                    <?php endif; ?>
                                            <span class="labels<?php echo $value['class'] ? ' ' . $value['class'] : ''; ?>">
                                                <label for="<?php echo $option_name . '[' . $valueid . ']'; ?>"><?php echo $value['name']; ?></label>
                                            </span>
                                            <input class="color {hash:true,pickerClosable:true}<?php echo $value['class'] ? ' full' : ''; ?>" name="<?php echo $option_name . '[' . $valueid . ']'; ?>" id="<?php echo $option_name . '[' . $valueid . ']'; ?>" type="<?php echo $value['type']; ?>" value="<?php if (isset($options[$valueid])) {
                        esc_attr_e($options[$valueid]);
                    } else {
                        esc_attr_e($value['std']);
                    } ?>" />

                                        </div>
                    <?php
                    break;
                case 'image':
                    ?>
                                        <div class="options_input_image options_input options_text" id="option_<?php echo $valueid; ?>">                               
                                            <div class="options_desc image_holder">
                    <?php if ($hint = $value['hint']) : ?>
                        <?php echo $hint; ?><br />
                                        <?php endif; ?>
                                            </div>


                                            <span class="labels">
                                                <label for="<?php echo $option_name . '[' . $valueid . ']'; ?>"><?php echo $value['name']; ?></label>
                                            </span>
                                            <input class="upload_image" name="<?php echo $option_name . '[' . $valueid . ']'; ?>" id="<?php echo $option_name . '[' . $valueid . ']'; ?>" type="text" value="<?php if (isset($options[$valueid])) {
                                            esc_attr_e($options[$valueid]);
                                        } else {
                                            esc_attr_e($value['std']);
                                        } ?>" />
                                            <input class="upload_image_button button-secondary" type="button" value="Upload Image" />
                                        </div>
                                        <?php
                                        break;
                                    case 'textarea':
                                        ?>
                                        <div class="options_input options_textarea">							
                    <?php if ($value['desc'] != '') : ?>
                                                <div class="options_desc"><?php echo $value['desc']; ?></div>
                    <?php endif; ?>
                                            <span class="labels<?php echo $value['class'] ? ' ' . $value['class'] : ''; ?>">
                                                <label for="<?php echo $option_name . '[' . $valueid . ']'; ?>"><?php echo $value['name']; ?></label>
                                            </span>
                                            <textarea<?php echo $value['class'] ? ' class="full" ' : ''; ?> name="<?php echo $option_name . '[' . $valueid . ']'; ?>" type="<?php echo $option_name . '[' . $valueid . ']'; ?>" cols="" rows=""><?php if (isset($options[$valueid])) {
                                esc_attr_e($options[$valueid]);
                            } else {
                                esc_attr_e($value['std']);
                            } ?>
                                            </textarea>
                                        </div>
                                        <?php
                                        break;
                                    case 'select':
                                        ?>
                                        <div class="options_input options_select">
                                            <div class="options_desc"><?php echo $value['desc']; ?></div>
                                            <span class="labels"<?php echo $value['class'] ? ' ' . $value['class'] : ''; ?>>
                                                <label for="<?php echo $option_name . '[' . $valueid . ']'; ?>"><?php echo $value['name']; ?></label>
                                            </span>
                                            <select name="<?php echo $option_name . '[' . $valueid . ']'; ?>" id="<?php echo $option_name . '[' . $valueid . ']'; ?>">
                                            <?php foreach ($value['options'] as $option) { ?>
                                                    <option <?php if ($options[$valueid] == $option) {
                            echo 'selected="selected"';
                        } ?>><?php echo $option; ?></option>
                                            <?php } ?>
                                            </select>
                                        </div>
                                            <?php
                                            break;
                                        case "radio":
                                            ?>
                                        <div class="options_input options_select">
                                            <div class="options_desc"><?php echo $value['desc']; ?></div>
                                            <span class="labels">
                                                <label for="<?php echo $option_name . '[' . $valueid . ']'; ?>"><?php echo $value['name']; ?></label>
                                            </span>
                                        <?php
                                        foreach ($value['options'] as $key => $option) {
                                            if (isset($options[$valueid])) {
                                                if ($key == $options[$valueid]) {
                                                    $checked = "checked=\"checked\"";
                                                } else {
                                                    $checked = "";
                                                }
                                            } else {
                                                if ($key == $value['std']) {
                                                    $checked = "checked=\"checked\"";
                                                } else {
                                                    $checked = "";
                                                }
                                            }
                                            ?>
                                                <input type="radio" id="<?php echo $option_name . '[' . $valueid . ']'; ?>" name="<?php echo $option_name . '[' . $valueid . ']'; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?> />
                        <?php echo $option; ?><br />
                                <?php } ?>
                                        </div>
                                <?php
                                break;
                            case "checkbox":
                                ?>
                                        <div class="options_input options_checkbox">
                                            <div class="options_desc"><?php echo $value['desc']; ?></div>
                    <?php if (isset($options[$valueid])) {
                        $checked = "checked=\"checked\"";
                    } else {
                        $checked = "";
                    } ?>
                                            <input type="checkbox" name="<?php echo $option_name . '[' . $valueid . ']'; ?>" id="<?php echo $option_name . '[' . $valueid . ']'; ?>" value="true" <?php echo $checked; ?> />
                                            <label for="<?php echo $option_name . '[' . $valueid . ']'; ?>"><?php echo $value['name']; ?></label>
                                        </div>
                                        <?php
                                        break;
                                    case "close":
                                        ?>
                                    </div>
                                    <!--#section_body--> 				
                                </div>
                                <!--#section_wrap-->			
                                        <?php
                                        break;
                                } //end switch
                            } //end for loopo
                            ?>
                    <span class="submit">
                        <input class="button button-primary" type="submit" name="save" value="<?php _e('Save All Changes', 'roulette') ?>" />
                        <input type="hidden" name="updated" value="true" />
                    </span>
                </form>
                <form method="post" action="">
                    <span class="button-right" class="submit">
                        <input class="button button-secondary" type="submit" name="reset" value="<?php _e('Reset/Delete Settings', 'roulette') ?>" />
                        <input type="hidden" name="action" value="reset" />
                        <span>
        <?php _e('Caution: All entries will be deleted from database. ', 'roulette') ?>
                            </form>
                            </div><!-- /options-wrap--> 
                            </div> <!-- /wrap -->
    <?php
    }

// roulette_settings_page()					
}

$TTD_Options = new TTD_Options();