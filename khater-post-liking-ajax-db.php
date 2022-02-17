<?php

/**
 * Plugin Name: Khater Post liking DB with AJAX (Shortcode)
 * Plugin URI:  http://khater.epizy.com/
 * Description: Allow blog visitors to "Like" or "Dislike" a post. Display the number of "Likes" and "Dislikes" a post has (Similar to YouTube).
 * Version:     1.0
 * Author:      Mohammed Khater
 * Author URI:  http://khater.epizy.com/
 * Text Domain: mak-post-liking
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

//add_shortcode('khater-post-liking-shortcode', 'shortcode_mak_post_liking');
//add_action('wp_enqueue_scripts', 'enqueue_so_22579460');

add_action('wp_enqueue_scripts', 'enqueue_mak_style');

/**
 * Enqueue our script inside the shortcode 
 */
function shortcode_mak_post_liking($attributes)
{
    wp_enqueue_script('my-script');
    ob_start();
    include __DIR__ . '/liking-form.php';
    $result = ob_get_clean();

    return $result;
}


// function mak_post_liking_form_shortcode()
// {
//     ob_start();
//     include __DIR__ . '/liking-form.php';
//     return ob_get_clean();
// }

// function mak_post_liking_add_shortcode()
// {

//     add_shortcode('mak-post-liking_shortcode', 'mak_post_liking_form_shortcode');
// }

// add_action('init', 'mak_post_liking_add_shortcode');




function the_liking_form($post_id)
{
    include __DIR__ . '/liking-form.php';
}

function do_liking_post($post_id)
{


    $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';
    if (!wp_verify_nonce($nonce, 'post-liking')) {
        wp_die('Invalid nonce');
    }


    if (isset($_POST['post_id'])) {

        $post_id = $_POST['post_id'];
        $post_like = (int) get_post_meta($post_id, '_post_liking', true);
        $post_dislike = (int) get_post_meta($post_id, '_post_disliking', true);


        if (isset($_POST['btn']) && ($_POST['btn'] == 'like')) {

            if ($_POST['btn_liking'] == 'true') {
                $post_like++;
                update_post_meta($post_id, '_post_liking', $post_like);
            } elseif ($_POST['btn_liking'] == 'false') {
                $post_like--;
                update_post_meta($post_id, '_post_liking', $post_like);
            }
        } else 
        if (isset($_POST['btn']) && ($_POST['btn'] == 'dislike')) {

            if ($_POST['btn_liking'] == 'true') {
                $post_dislike++;

                update_post_meta($post_id, '_post_disliking', $post_dislike);
            } elseif ($_POST['btn_liking'] == 'false') {
                $post_dislike--;
                update_post_meta($post_id, '_post_disliking', $post_dislike);
            }
        }
    }
}

function normal_post_liking()
{
    $post_id = $_POST['post_id'] ?? 0;

    if (!$post_id) {
        wp_die('No post Selected!');
    }
    do_liking_post($post_id);

    wp_redirect(get_permalink($post_id));
}


add_action('admin_post_post-liking', 'normal_post_liking');
add_action('admin_post_nopriv_post-liking', 'normal_post_liking');



// call by ajax action
function ajax_post_liking()
{
    $post_id = $_POST['post_id'] ?? 0;

    if (!$post_id) {
        wp_send_json_error([
            'message' => 'No post Selected!',
        ]);
    }

    $session_name = 'post_liked' . $post_id;

    if (isset($_SESSION[$session_name]) && $_SESSION[$session_name]) {
        return;
    }
    $_SESSION[$session_name] = true;


    do_liking_post($post_id);


    $meta = get_post_meta($post_id);

    wp_send_json([
        'post_like' => (int)$meta['_post_liking'][0],
        'post_dislike' => (int)$meta['_post_disliking'][0],
    ]);
}

add_action('wp_ajax_post-liking', 'ajax_post_liking');
add_action('wp_ajax_nopriv_post-liking', 'ajax_post_liking');


function enqueue_mak_style()
{
    if (is_single()) {
        wp_enqueue_style(
            'mak_styles',
            plugins_url('assets/css/style.css', __FILE__),
            [],
            false,
            'all'
        );
    }
}







function the_post_liking($post_id)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $like_count = (int)get_post_meta($post_id, '_post_liking', true);
    $dislike_count = (int)get_post_meta($post_id, '_post_disliking', true);

    printf('<div class="post_liking">like: <span>%d</span>  and dislike: <span>%d</span></div>', $like_count, $like_count);
}


function enqueue_like_scripts()
{
    if (is_single()) {

        wp_enqueue_script(
            'like_script',
            plugins_url('assets/js/ajax-script.js', __FILE__),
            ['jquery'],
            false,
            true
        );

        wp_localize_script(
            'like_script',
            'data_ajax',
            [
                'nonce'    => wp_create_nonce('post-liking'),
                'ajax_url' => admin_url('admin-ajax.php'),
                'msg'      => 'Thank you',
                'wait'     => 'Please wait..'

            ]
        );
    }
}

add_action('wp_enqueue_scripts', 'enqueue_like_scripts');


include __DIR__ . '/post-like-db.php';
