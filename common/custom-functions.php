<?php
if (!get_option("medium_crop"))
    add_option("medium_crop", "1");
else
    update_option("medium_crop", "1");

if (!get_option("large_crop"))
    add_option("large_crop", "1");
else
    update_option("large_crop", "1");

// remove inline css style of wordpress default widget
function twentyten_remove_recent_comments_style() {
    global $wp_widget_factory;
    remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
}

add_action('widgets_init', 'twentyten_remove_recent_comments_style');

/* Add last_item class to last li in wp_nav_menu lists */

function add_first_and_last($items) {
    $items[1]->classes[] = 'first-menu-item';
    $items[count($items)]->classes[] = 'last-menu-item';
    return $items;
}

add_filter('wp_nav_menu_objects', 'add_first_and_last');

function convert_to_sql_date_string($date_str, $format) {
    if (empty($date_str))
        return null;
    list($d, $m, $y) = explode('/', $date_str);
    $date_str = $y . '-' . $m . '-' . $d;
    $date_str = date($format, strtotime($date_str));
    return $date_str;
}

function the_theme_option($key) {
    echo get_theme_option($key);
}

function get_theme_option($key) {
    $options = get_option(THEME_OPTIONS);
    if ($options[$key] != '')
        return $options[$key];
    return false;
}

function has_theme_option($key) {
    $options = get_option(THEME_OPTIONS);
    if ($options[$key] != '')
        return true;
    return false;
}

function the_custom_post_meta($key, $single = true) {
    $value = get_custom_post_meta($key, $single);
    echo $value;
}

function get_custom_post_meta($key, $single = true, $id = '') {
    global $post;
    if ($id) {
        $values = get_post_meta($id, THEME_PREFIX . $key, $single);
    } else {

        $values = get_post_meta($post->ID, THEME_PREFIX . $key, $single);
    }
    return $values;
}

function get_multiple_custom_post_meta($key, $single = true, $id = '') {
    global $post;
    if ($id) {
        foreach ($key as $k) {
            if ($val = get_post_meta($post->ID, THEME_PREFIX . $k, $single)) {
                $values[] = $val;
            }
        }
    } else {
        foreach ($key as $k) {
            if ($val = get_post_meta($post->ID, THEME_PREFIX . $k, $single)) {
                $values[] = $val;
            }
        }
    }
    return $values;
}

if (!function_exists('show_attachment_image')) :

    function show_attachment_image($id, $image_name = '', $blog_id = '') {
        if ($blog_id > 0) {
            switch_to_blog($blog_id);
        }
        $image_id = get_post_thumbnail_id($id);
        if ($image_id) {
            echo wp_get_attachment_image(
                    $image_id, $image_name, false, array(
                'class' => 'attachment-' . $image_name,
                'alt' => get_the_title($id),
                'title' => get_the_title($id))
            );
        } else {
            if ($image_name = 'thumbnail' && get_post_type($id) == EM_POST_TYPE_EVENT) {
                echo '<img src="' . TEMPLATE_URL . '/images/thumb-default.png" alt="' . get_the_title($id) . '" title="' . get_the_title($id) . '">';
            }
        }
        if ($blog_id > 0) {
            restore_current_blog();
        }
    }

endif;

function get_attachment_image_src($id, $image_name) {
    $image_id = get_post_thumbnail_id($id);
    return wp_get_attachment_image_src($image_id, $image_name);
}

function get_the_best_image_src($id, $image_name, $w, $h, $zc) {
    if ($src = get_attachment_image_src($id, $image_name)) {
        return $src;
    } elseif ($src = get_attachment_image_src($id, 'full')) {
        return $src;
    } else {
        $src = array(get_image_src($w, $h, $id, $zc));
        return $src;
    }
}

function get_image_src($w = null, $h = null , $post_id = null , $zc = 1) {

    global $blog_id;
    $image_id = get_post_thumbnail_id($post_id);
    $src = wp_get_attachment_image_src($image_id, 'full');

    if (isset($blog_id) && $blog_id > 0) {
        if (isset($src)) {
            //$theImageSrc = '/blogs.dir/' . $blog_id . '/files/' . $src;
            return TEMPLATE_URL . '/lib/thumb.php?src=' . $src[0] . '&h=' . $h . '&w=' . $w . '&zc=' . $zc . '&q=95';
        }
    }
}

// get all of the images attached to the current post
function get_original_full_images($size = 'thumbnail') {
    global $post, $wpdb;

    $upload_dir = wp_upload_dir();
    $thumb_id = get_post_thumbnail_id($post->ID);
    if ($thumb_id) {
        //if there is backup sizes
        $backup_images = get_post_meta($thumb_id, _wp_attachment_backup_sizes);

        if ($backup_images != null) {
            $backup_full = $upload_dir['baseurl'] . '/' . $backup_images[0]['full-orig']['file'];
            if ($backup_full)
                return $backup_full;
        }
    }
    return '';
}

function catch_that_image() {
    global $post, $posts;
    $first_img = '';
    ob_start();
    ob_end_clean();
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);

    $first_img = $matches [1] [0];

    if (empty($first_img)) { //Defines a default image
        //$first_img = get_bloginfo('template_url') . "/images/default.jpg";
    }
    return $first_img;
}

add_filter('the_content', 'remove_img_from_content');

function remove_img_from_content($content) {
    global $post;
    if (!is_singular(defined('EM_POST_TYPE_EVENT') ? EM_POST_TYPE_EVENT : 'event') && !is_singular('DEAL_TYPE')) {
        return $content;
    }
    // $content = preg_replace('#(<[/]?img.*>)#U', '', $content); //removing all images
    if (preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches)) {
        $images = $matches[0];
        $content = str_replace($images[0], '', $content); //removing only first image
    }
    return $content;
}

add_filter('the_content', 'remove_domain_from_path');

function remove_domain_from_path($content) {
    $content = str_ireplace(WP_SITEURL, '', $content);
    return $content;
}

function the_share_button_deal($id, $title, $excerpt = '') {
    //if there is no excerpt, the content will be passed - so strip off any image from the content just in case
    $body = string_limit_words(remove_img_from_content($excerpt), 150);
    $body .= "%0D%0AMore information at:%0D%0A";
    $body .= get_permalink($id);
    global $post_type;
    ?>
    <div class="sharepanel clearfix">

        <div id="single-social" class="shareicons">
            <ul>
                <li class="text">Share:</li>
                <?php if (is_tax() || $post_type == DEAL_TYPE) : ?>
                    <li class="email"><a href="mailto:?subject=Vivid Sydney: <?php echo $title; ?>&body=<?php echo $body; ?>">Email</a></li>
    <?php endif; ?>
                <li class="facebook">
                    <a rel="external" href="http://www.facebook.com/share.php?u=<?php echo get_permalink($id); ?>" onclick="javascript:_gaq.push(['_trackSocial', 'Facebook', 'Visit']);" >Facebook</a></li>
                <li class="twitter"><a rel="external" href="http://twitter.com/share?text=<?php echo $title ?>&url=<?php echo get_permalink($id); ?>" onclick="javascript:_gaq.push(['_trackSocial', 'Twitter', 'Visit']);" >Twitter</a></li>
                <li class="googleplus"><a rel="external" href="https://plus.google.com/share?url=<?php echo get_permalink($id); ?>" onclick="javascript:_gaq.push(['_trackSocial', 'Google+', 'Visit']);" >Google+</a></li>
                <?php if (!is_tax() && $post_type == DEAL_TYPE) : ?>
                    <li class="print"><a href="#" onClick="window.print();
                            return false">Print</a></li>
    <?php endif; ?>
            </ul>
        </div>
        <div class="cleared fb-like" data-href="<?php echo get_permalink($id); ?>" data-send="false" data-width="130" data-show-faces="false" data-colorscheme="dark"></div>
    </div>	 
    <?php
}

function the_share_button($id, $title, $excerpt = '') {
    //if there is no excerpt, the content will be passed - so strip off any image from the content just in case
    $body = string_limit_words(remove_img_from_content($excerpt), 150);
    $body .= "%0D%0AMore information at:%0D%0A";
    $body .= get_permalink($id);
    $post_type = get_post_type($id);
    $title = urlEncodeRFC3986($title);
    $body = urlEncodeRFC3986($body);
    ?>
    <div class="sharepanel clearfix">

        <div class="fb-like" data-href="<?php echo get_permalink($id); ?>" data-send="false" data-width="270" data-show-faces="false" data-colorscheme="dark"></div>
        <div id="single-social" class="shareicons">
            <ul>
                <li class="text">Share:</li>
    <?php if (is_tax() || $post_type == EM_POST_TYPE_EVENT || (is_single() && $post_type == 'post')) : ?>
                    <li class="email"><a href="mailto:?subject=Vivid Sydney: <?php echo $title; ?>&body=<?php echo $body; ?>">Email</a></li>
    <?php endif; ?>
                <li class="facebook">
                    <a rel="external" href="http://www.facebook.com/share.php?u=<?php echo get_permalink($id); ?>" onclick="javascript:_gaq.push(['_trackSocial', 'Facebook', 'Visit']);" >Facebook</a></li>
                <li class="twitter"><a rel="external" href="http://twitter.com/share?text=<?php echo $title ?>&url=<?php echo get_permalink($id); ?>" onclick="javascript:_gaq.push(['_trackSocial', 'Twitter', 'Visit']);" >Twitter</a></li>
                <li class="googleplus"><a rel="external" href="https://plus.google.com/share?url=<?php echo get_permalink($id); ?>" onclick="javascript:_gaq.push(['_trackSocial', 'Google+', 'Visit']);" >Google+</a></li>
    <?php if (is_singular(EM_POST_TYPE_EVENT) || (is_single() && $post_type == 'post')) : ?>
                    <li class="print"><a href="#" onClick="window.print();
                            return false">Print</a></li>
    <?php endif; ?>
            </ul>
        </div>
    </div>	 
    <?php
}

function the_share_button_photo($id, $title, $excerpt = '') {
    //if there is no excerpt, the content will be passed - so strip off any image from the content just in case
    $body = string_limit_words(remove_img_from_content($excerpt), 150);
    $body .= "%0D%0AMore information at:%0D%0A";
    $body .= get_permalink($id);
    $title = urlEncodeRFC3986($title);
    $body = urlEncodeRFC3986($body);
    global $post_type;
    ?>
    <div class="sharepanel clearfix">


        <div id="single-social" class="shareicons" style="float:rigth;">
            <ul>
                <li class="text">Share:</li>
                <li class="email"><a href="mailto:?subject=Vivid Sydney: <?php echo $title; ?>&body=<?php echo $body; ?>">Email</a></li>
                <li class="facebook">
                    <a rel="external" href="http://www.facebook.com/share.php?u=<?php echo get_permalink($id); ?>" onclick="javascript:_gaq.push(['_trackSocial', 'Facebook', 'Visit']);" >Facebook</a></li>
                <li class="twitter"><a rel="external" href="http://twitter.com/share?text=<?php echo $title ?>&url=<?php echo get_permalink($id); ?>" onclick="javascript:_gaq.push(['_trackSocial', 'Twitter', 'Visit']);" >Twitter</a></li>
                <li class="googleplus"><a rel="external" href="https://plus.google.com/share?url=<?php echo get_permalink($id); ?>" onclick="javascript:_gaq.push(['_trackSocial', 'Google+', 'Visit']);" >Google+</a></li>

            </ul>
        </div>
        <div class="fb-like" data-send="false" data-width="310" data-show-faces="false" data-colorscheme="dark" data-href="<?php echo get_permalink($id); ?>"></div>
    </div>	 
    <?php
}

function custom_excerpt($text) {
    global $post;
    if (get_post_type($post->ID) == 'post')
    //return str_replace('[...]', '<p class="more"><a href="' . get_permalink($post->ID) . '" class="cta">' . 'Read More&nbsp;&rarr;' . '</a><p>', $text);
        return str_replace('[...]', '', $text);
}

add_filter('excerpt_more', 'custom_excerpt');

function custom_excerpt_length($length) {
    global $post;
    if (get_post_type($post->ID) != 'post') {
        return $length;
    }
    return 30;
}

add_filter('excerpt_length', 'custom_excerpt_length', 999);

function get_excerpt_by_chars($excerpt, $len) {
    if (strlen($excerpt) > $len) {
        $excerpt = substr(strip_tags($excerpt), 0, $len);
        $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
        $excerpt = trim(preg_replace('/\s+/', ' ', $excerpt));
    }
    return $excerpt;
}

function get_the_excerpt_only($post_id) {
    global $wpdb;
    $query = "SELECT post_excerpt FROM $wpdb->posts WHERE ID = $post_id LIMIT 1";
    $result = $wpdb->get_results($query, ARRAY_A);
    return $result[0]['post_excerpt'];
}

function excerpt($limit) {
    $excerpt = explode(' ', get_the_excerpt(), $limit);
    if (count($excerpt) >= $limit) {
        array_pop($excerpt);
        $excerpt = implode(" ", $excerpt);
    } else {
        $excerpt = implode(" ", $excerpt);
    }
    $excerpt = preg_replace('`\[[^\]]*\]`', '', $excerpt);
    return $excerpt;
}

function content($limit) {
    $content = explode(' ', get_the_content(), $limit);
    if (count($content) >= $limit) {
        array_pop($content);
        $content = implode(" ", $content) . '...';
    } else {
        $content = implode(" ", $content);
    }
    $content = preg_replace('/\[.+\]/', '', $content);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    return $content;
}

function string_limit_words($string, $word_limit) {
    $words = explode(' ', $string, ($word_limit + 1));
    if (count($words) > $word_limit)
        array_pop($words);
    return implode(' ', $words);
}

function set_default_site_prefix() {
    global $wpdb;
    $current_blog_id = get_current_blog_id();
    if ($current_blog_id !== BLOG_ID_CURRENT_SITE) {
        $wpdb->set_blog_id(BLOG_ID_CURRENT_SITE);
    }
}

function restore_current_blog_prefix() {
    global $wpdb;
    $current_blog_id = get_current_blog_id();
    if ($current_blog_id !== BLOG_ID_CURRENT_SITE) {
        $wpdb->set_blog_id($current_blog_id);
    }
}

function set_business_site_prefix() {
    global $wpdb;
    $current_blog_id = get_current_blog_id();
    if ($current_blog_id !== BLOG_ID_BUSINESS_SITE) {
        $wpdb->set_blog_id(BLOG_ID_BUSINESS_SITE);
    }
}

function restore_business_blog_prefix() {
    global $wpdb;
    $current_blog_id = get_current_blog_id();
    if ($current_blog_id !== BLOG_ID_BUSINESS_SITE) {
        $wpdb->set_blog_id($current_blog_id);
    }
}

function get_youtube_id($link) {
    if ($link == '')
        return ''; // if blank, don't need to check
    if (preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $link, $matches)) {
        return $matches[0];
    }
}

function save_single_meta_box($post_id, $name, $new) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    // verify if this is not inline-editing
    if ($_REQUEST['action'] == 'inline-save') {
        return;
    }
    $old = get_post_meta($post_id, $name, true);


    if ($new && $new != $old) {
        update_post_meta($post_id, $name, $new);
    } elseif ('' == $new && $old) {
        delete_post_meta($post_id, $name, $old);
    }
}

if (!function_exists('the_pagination')):

    function the_pagination($max_page = 1, $range = 10) {

        global $paged, $wp_query;
        // How much pages do we have?  
        //if (!$max_page) {
        if ($max_page <= 1) {
            $max_page = $wp_query->max_num_pages;
        }
        //}
        // We need the pagination only if there are more than 1 page  
        if ($max_page <= 1)
            return false;

        if (!$paged) {
            $paged = 1;
        }
        echo '<div class="pagination"><ul>' . "\n";
        // On the first page, don't put the First page link  
        if ($paged != 1) {
            echo '<li><a class="pagination-prev" rel="' . ($paged - 1) . '" href="' . get_pagenum_link($paged - 1) . '">Previous</a></li>';
        }
        if ($max_page > $range) {
            // When closer to the beginning  
            if ($paged < $range) {
                for ($i = 1; $i <= ($range + 1); $i++) {
                    echo "<li>\n";
                    if ($i == $paged) {
                        echo "<a class='on' rel='" . ($i) . "' href='" . get_pagenum_link($i) . "'><span>$i</span></a>";
                    } else {
                        echo "<a rel='" . ($i) . "' href='" . get_pagenum_link($i) . "'><span>$i</span></a>";
                    }
                    echo "</li>\n";
                }
            }
            // When closer to the end  
            elseif ($paged >= ($max_page - ceil(($range / 2)))) {
                for ($i = $max_page - $range; $i <= $max_page; $i++) {
                    echo "<li>\n";
                    if ($i == $paged) {
                        echo "<a class='on' rel='" . ($i) . "' href='" . get_pagenum_link($i) . "'><span>$i</span></a>";
                    } else {
                        echo "<a rel='" . ($i) . "' href='" . get_pagenum_link($i) . "'><span>$i</span></a>";
                    }
                    echo "</li>\n";
                }
            }
            // Somewhere in the middle  
            elseif ($paged >= $range && $paged < ($max_page - ceil(($range / 2)))) {
                for ($i = ($paged - ceil($range / 2)); $i <= ($paged + ceil(($range / 2))); $i++) {
                    echo "<li>\n";
                    if ($i == $paged) {
                        echo "<a class='on' rel='" . ($i) . "' href='" . get_pagenum_link($i) . "'><span>$i</span></a>";
                    } else {
                        echo "<a rel='" . ($i) . "' href='" . get_pagenum_link($i) . "'><span>$i</span></a>";
                    }
                    echo "</li>\n";
                }
            }
        }
        // Less pages than the range, no sliding effect needed  
        else {
            for ($i = 1; $i <= $max_page; $i++) {
                echo "<li>\n";
                if ($i == $paged) {
                    echo "<a class='on' rel='" . ($i) . "' href='" . get_pagenum_link($i) . "'><span>$i</span></a>";
                } else {
                    echo "<a rel='" . ($i) . "' href='" . get_pagenum_link($i) . "'><span>$i</span></a>";
                }
                echo "</li>\n";
            }
        }
        // Next page  
        //	next_posts_link(' » ');
        // On the last page, don't put the Last page link  
        if ($paged != $max_page) {
            echo " <li><a class='pagination-next' rel='" . ($paged + 1) . "' href='" . get_pagenum_link($paged + 1) . "'>Next</a></li>";
        }
        echo '</ul></div>';
    }

endif;

function urlEncodeRFC3986($string) {
    $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%36', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
    $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
    $string = str_replace("&amp;", '%26', $string);
    //$string = str_replace("<", '%3C', $string);
    //$string = str_replace(">", '%3E', $string);
    $string = strip_tags($string);
    $string = urlencode($string);
    $string = str_replace($entities, $replacements, $string);
    $string = str_replace('"', "", $string);
    $string = htmlspecialchars($string, ENT_QUOTES);
    $string = str_replace('+', ' ', $string);
    return $string;
}

################################################################################
// Class to minify HTML
################################################################################

class WP_HTML_Compression {

    // Settings
    protected $compress_css = true;
    protected $compress_js = true;
    protected $info_comment = false;
    protected $remove_comments = true;
    // Variables
    protected $html;

    public function __construct($html) {
        if (!empty($html)) {
            $this->parseHTML($html);
        }
    }

    public function __toString() : string {
        return (string) ($this->html ?? '');
    }

    protected function bottomComment($raw, $compressed) {
        $raw = strlen($raw);
        $compressed = strlen($compressed);

        $savings = ($raw - $compressed) / $raw * 100;

        $savings = round($savings, 2);

        return '<!--HTML compressed, size saved ' . $savings . '%. From ' . $raw . ' bytes, now ' . $compressed . ' bytes-->';
    }

    protected function minifyHTML($html) {
        $pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
        preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
        $overriding = false;
        $raw_tag = false;
        // Variable reused for output
        $html = '';
        foreach ($matches as $token) {
            $tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;

            $content = $token[0];

            if (is_null($tag)) {
                if (!empty($token['script'])) {
                    $strip = $this->compress_js;
                } else if (!empty($token['style'])) {
                    $strip = $this->compress_css;
                } else if ($content == '<!--wp-html-compression no compression-->') {
                    $overriding = !$overriding;

                    // Don't print the comment
                    continue;
                } else if ($this->remove_comments) {
                    if (!$overriding && $raw_tag != 'textarea') {
                        // Remove any HTML comments, except MSIE conditional comments
                        $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
                    }
                }
            } else {
                if ($tag == 'pre' || $tag == 'textarea') {
                    $raw_tag = $tag;
                } else if ($tag == '/pre' || $tag == '/textarea') {
                    $raw_tag = false;
                } else {
                    if ($raw_tag || $overriding) {
                        $strip = false;
                    } else {
                        $strip = true;

                        // Remove any empty attributes, except:
                        // action, alt, content, src
                        $content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);

                        // Remove any space before the end of self-closing XHTML tags
                        // JavaScript excluded
                        $content = str_replace(' />', '/>', $content);
                    }
                }
            }

            if ($strip) {
                $content = $this->removeWhiteSpace($content);
            }

            $html .= $content;
        }

        return $html;
    }

    public function parseHTML($html) {
        $this->html = $this->minifyHTML($html);

        if ($this->info_comment) {
            $this->html .= "\n" . $this->bottomComment($html, $this->html);
        }
    }

    protected function removeWhiteSpace($str) {
        $str = str_replace("\t", ' ', $str);
        $str = str_replace("\n", '', $str);
        $str = str_replace("\r", '', $str);

        while (stristr($str, '  ')) {
            $str = str_replace('  ', ' ', $str);
        }

        return $str;
    }

}

function wp_html_compression_finish($html) {
    return new WP_HTML_Compression($html);
}

function wp_html_compression_start() {
    global $THEME_OPTIONS;	
    if (!is_admin()) {
        if (!isset($THEME_OPTIONS['compress_html'])) {
            ob_start('wp_html_compression_finish');
        }
    }
}

add_action('init', 'wp_html_compression_start', 20);

//remove empty p tag
add_filter('the_content', 'remove_empty_p', 20, 1);

function remove_empty_p($content) {
    $content = force_balance_tags($content);
    $content = preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content);
    $content = preg_replace('~\s?<p>(\s|&nbsp;)+</p>\s?~', '', $content);
    return $content;
}
