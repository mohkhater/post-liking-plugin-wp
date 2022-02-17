<?php

//const TABLE_NAME = 'mak_postliking';

function mk_post_liking_activation()
{
    // object WPDB
    global $wpdb;

    $query = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}mak_postliking`( 
           `id` INT NOT NULL AUTO_INCREMENT ,
           `post_id` INT NOT NULL ,
           `user_id` INT NOT NULL ,
           `like_type` INT NOT NULL , 
           `like_date` TIMESTAMP NOT NULL ,
            PRIMARY KEY (`id`))";

    $wpdb->query($query);
}

register_activation_hook(__FILE__, 'mk_post_liking_activation');

function mk_post_liking_uninstall()
{

    global $wpdb;

    $query = "DROP TABLE IF EXISTS `{$wpdb->prefix}mak_postliking` ";

    $wpdb->query($query);
}
register_uninstall_hook(__FILE__, 'mk_post_liking_uninstall');


function do_liking_post_db()
// function do_liking_post_db($post_id)
{

    global $wpdb;
    $user_id = get_current_user_id();
    $post_id = 113;

    $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';
    if (!wp_verify_nonce($nonce, 'post-liking')) {
        wp_die('Invalid nonce');
    }

    if (isset($_POST['like-count']) && isset($_POST['post_id'])) {

        // $post_id = $_POST['post_id'];
        //$post_like = (int) get_post_meta($post_id, '_post_liking', true);
        //$post_dislike = (int) get_post_meta($post_id, '_post_disliking', true);
        $post_like = 10;
        $post_dislike = 5;

        if (isset($_POST['btn_liking']) && ($_POST['btn_liking'] == 'likebtn')) {

            $post_like++;
            // update_post_meta($post_id, '_post_liking', $post_like);
            $wpdb->insert("{$wpdb->prefix}mak_postliking", [
                'post_id' => $post_id,
                'user_id' => $user_id,
                'like_type' => 1,
                'like_date' => date('Y-m-d H:i:s'),
            ]);
        } elseif (isset($_POST['btn_liking']) && ($_POST['btn_liking'] == 'dislikebtn')) {

            $post_dislike++;
            $wpdb->insert("{$wpdb->prefix}mak_postliking", [
                'post_id' => $post_id,
                'user_id' => $user_id,
                'like_type' => 0,
                'like_date' => date('Y-m-d H:i:s'),
            ]);
        }

        wp_redirect(add_query_arg('success', 'ok', get_permalink($post_id)));
    }
}

add_action('admin_post_post-liking', 'do_liking_post_db', 10);
add_action('admin_post_nopriv_post-liking', 'do_liking_post_db', 10);

function mak_post_liking_form_shortcode()
{
    ob_start();
    include __DIR__ . '/liking-form.php';
    return ob_get_clean();
}

function mak_post_liking_add_shortcode()
{

    add_shortcode('mak-post-liking_shortcode', 'mak_post_liking_form_shortcode');
}

add_action('init', 'mak_post_liking_add_shortcode');

function mak_post_liking_admin_pages()
{
    add_menu_page(
        'Users Post Likes',
        'Post Likes',
        'manage_options',
        'mak-post-liking',
        'mak_post_liking_page',
        'dashicons-thumbs-up',
        30

    );
}

add_action('admin_menu', 'mak_post_liking_admin_pages');

function mak_post_liking_page()
{
    global $wpdb;
    include __DIR__ . '/post-liking-admin-page.php';
}


function do_delete_post_like()
{
    global $wpdb;

    $nonce = isset($_GET['nonce']) ? $_GET['nonce'] : '';
    if (!wp_verify_nonce($nonce, 'delete_post_liking')) {
        wp_die('Invalid nonce');
    }

    $id = isset($_GET['like_id']) ? $_GET['like_id'] : false;

    if ($id) {
        $wpdb->delete(
            "{$wpdb->prefix}mak_postliking",
            [
                'id' => $id
            ]
        );
    }
    //var_dump(menu_page_url('mak-post-liking', false));

    wp_redirect(admin_url('admin.php?page=mak-post-liking'));
}

add_action('admin_post_delete_post_liking', 'do_delete_post_like');
