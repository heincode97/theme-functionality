<?php

/* theme switching */

function theme_switcher() {
    if ($_REQUEST['v'] == 'd') { //for testing purpose
        add_filter('template', 'get_default_theme');
        add_filter('stylesheet', 'get_default_theme');
    } elseif ($_REQUEST['v'] == 'm') { //for testing purpose
        add_filter('template', 'get_mobile_theme');
        add_filter('stylesheet', 'get_mobile_theme');
    } else {
        if (IS_MOBILE) {
            add_filter('template', 'get_mobile_theme');
            add_filter('stylesheet', 'get_mobile_theme');
        } else {
            add_filter('template', 'get_default_theme');
            add_filter('stylesheet', 'get_default_theme');
        }
    }
}

function get_mobile_theme() {
    $desired_view = get_desired_view();
    check_view_switch_redirect();

    if (!empty($desired_view)) {
        return $desired_view;
    }
    else
        return 'mobile';
}

function get_default_theme() {
    $desired_view = get_desired_view();
    check_view_switch_redirect();

    if (!empty($desired_view)) {
        return $desired_view;
    }
    else
        return 'live4';
}

function get_desired_view() {
    $key = 'live4_switch_toggle';
    $time = time() + 60 * 60 * 24 * 30; // 30 days
    $url_path = '/';

    if (isset($_GET['view'])) {
        if ($_GET['view'] == 'mobile') {
            setcookie($key, 'mobile', $time, $url_path);
        } elseif ($_GET['view'] == 'normal') {
            setcookie($key, 'live4', $time, $url_path);
        }
    }
    if (isset($_COOKIE[$key])) {
        return $_COOKIE[$key];
    }
    return '';
}

function check_view_switch_redirect() {

    if (isset($_GET['view_redirect_to'])) {
        if (isset($_GET['view_redirect_nonce'])) {
            $nonce = $_GET['view_redirect_nonce'];
            if (!wp_verify_nonce($nonce, 'live4_redirect')) {
                _e('No naughty business, please.', 'live4');
                die;
            }
            $redirect_location = WP_HOME . $_GET['view_redirect_to'];
            header('Location: ' . $redirect_location);
            die;
        }
    }
}

?>