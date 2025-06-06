<?php

/**
 * Template part for displaying the User Account Details
 *
 * @package socialv
 */
$socialv_options = get_option('socialv-options');
$display_user = (class_exists('Redux') && $socialv_options['header_display_login'] == 'yes') ? true : false;

if (is_user_logged_in()) {
    $loggedin_user = wp_get_current_user();
    if (($loggedin_user instanceof WP_User)) {
        if (!function_exists('buddypress') && class_exists('LearnPress')) {
            $user_link = function_exists('learn_press_user_profile_link') ? learn_press_user_profile_link(get_current_user_id(), false) : '#';
        } else {
            $user_link = function_exists('bp_members_get_user_url') ? bp_members_get_user_url(get_current_user_id()) : '#';
        }
        $image_url = get_avatar_url(get_current_user_id(), ['size' => '50']);
        $user_img = '<span class="main-profile">
        <span class="item-img">
            <a class="user-link" href="' . esc_url($user_link) . '">
            <img class="rounded-circle avatar-50 photo" src="' . esc_url($image_url) . '" loading="lazy" alt="' . esc_attr__('user-img', 'socialv') . '">
            </a>
        </span>
    </span>';
        if (!function_exists('buddypress') && class_exists('LearnPress')) { ?>
            <div class="dropdown-toggle">
                <?php echo apply_filters('socialv_header_user_profile', $user_img); ?>
            </div>
        <?php } else { ?>
            <div class="dropdown dropdown-profile">
                <div class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php echo apply_filters('socialv_header_user_profile', $user_img); ?>
                </div>
                <div class="dropdown-menu dropdown-menu-right">
                    <?php
                    do_action('socialv_user_profile_menu');
                    ?>
                    <div class="user-menu-footer">
                        <a href="<?php echo wp_logout_url(home_url()); ?>" class="socialv-button"><i class="iconly-Logout icli mx-1"></i><?php esc_html_e('Log Out', 'socialv'); ?></a>
                    </div>
                </div>
            </div>
        <?php
        }
    }
} else {
    if ($display_user == true) {
        $image = (isset($socialv_options['socialv_site_login_logo']) && !empty($socialv_options['socialv_site_login_logo']['url'])) ? $socialv_options['socialv_site_login_logo']['url'] : (get_template_directory_uri() . '/assets/images/redux/login-icon.svg');
        $image_url =  $content = '';
        $class = 'btn-login';
        if (!empty($image)) :
            $file_extension = pathinfo($image, PATHINFO_EXTENSION);
            if ($file_extension === 'svg') {
                $remote_svg_file = wp_remote_get($image);
                $svg_content = wp_remote_retrieve_body($remote_svg_file);
                $image_url .= $svg_content;
            } else {
                if (in_array($file_extension, ['png', 'jpg', 'jpeg'])) {
                    $image_url .= '<img src=' . esc_url($image) . '  alt="' . esc_attr__('Login', 'socialv') . '" loading=lazy>';
                }
            }
        endif;
        $show_icon = isset($socialv_options['is_socialv_site_login_icon_desktop']) && $socialv_options['is_socialv_site_login_icon_desktop'] == '0' ? false : true;

        $login_page_title =  sprintf(_x('%s', 'site_login_title', 'socialv'), $socialv_options['site_login_title']);
        if (!empty($login_page_title)) {
            $content = '<span class=' . (($show_icon == true) ? 'me-2' : '') . '>' . ($login_page_title) . '</span>';
            $class .= ' socialv-button';
        }
        $show_icon = ($show_icon == false) ? 'icon-none' : '';
        if (isset($socialv_options['site_login']) && $socialv_options['site_login'] == 1) {
        ?>
            <div class="bp-icon-wrap <?php echo esc_html($show_icon); ?>">
                <button class="<?php echo esc_attr($class); ?>" data-bs-toggle="modal" data-bs-target="#register_modal"><?php echo wp_kses_post($content) . ($image_url); ?></button>
            </div>
<?php } else {
            $login_link = !empty($socialv_options['site_login_link']) ? get_page_link($socialv_options['site_login_link']) : '#';
            echo '<div class="bp-icon-wrap ' . esc_html($show_icon) . '">
            <a href="' . esc_url($login_link) . '" class="' . esc_attr($class) . '">' . wp_kses_post($content) . $image_url . '</a>
          </div>';
        }
    }
}
