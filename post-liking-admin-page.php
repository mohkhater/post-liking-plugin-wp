<div class="wrap">
    <h2><?= get_admin_page_title() ?></h2>

    <table class="wp-list-table widefat fixed striped table-view-list posts">
        <thead>
            <tr>
                <th></th>
                <th>Post</th>
                <th>Like Count</th>
                <th>Dislike Count</th>
                <th>Like Time</th>
                <th>Option</th>
            </tr>
        </thead>
        <tbody>
            <?php

          //  $wpdb->show_errors();

            $query = " SELECT * FROM `{$wpdb->prefix}mak_postliking` ORDER BY `like_date` DESC ";

            $result = $wpdb->get_results($query);
             
            foreach ($result as $row) :

            ?>
                <tr>
                    <td></td>
                    <td><?= $row->post_id ?></td>
                    <td><?= $row->like_type ?></td>
                    <td><?= $row->like_type ?></td>
                    <td><?= $row->like_date ?></td>
                    <td><a href="<?= admin_url('admin-post.php') ?>?action=delete_post_liking&nonce=<?= wp_create_nonce('delete_post_liking') ?>&like_id=<?= $row->id ?>">Delete</a></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>