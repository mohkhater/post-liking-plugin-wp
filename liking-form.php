  <form id="liking-form" action="<?= admin_url('admin-post.php'); ?>" method="post">
      <div class="mak-plugin" id="buttons-like">


          <h4>Like this post!</h4>
          <input type="hidden" name="action" id="buttons-like" value="post-liking">
          <input type="hidden" name="nonce" id="nonce" value="<?= wp_create_nonce("post-liking") ?>">
          <input type="hidden" name="post_id" id="post_id" value="<?= $post_id; ?>">
          <input type="hidden" name="user_id" id="user_id" value="<?= $user_id; ?>">



          <?php if ($user_id != 0) {

                if (get_post_like_count($post_id, 1) != 0) {
                    $like_count = get_post_like_count($post_id, 1);
                } else $like_count = '';

                if (get_post_like_count($post_id, 0) != 0) {
                    $dislike_count = get_post_like_count($post_id, 0);
                } else $dislike_count = '';

            ?>

              <button class="btn likebtn <?php if (select_post_like_user($post_id, $user_id, 1) != null) {
                                                echo 'green';
                                            } ?>" id="btn_like" name="btn_liking" value="true" type="submit">
                  <span class="like-count" id="like-count"><?= $like_count; ?></span>
                  <i class="fa fa-thumbs-up fa-lg" aria-hidden="true"></i>
                  <input type="hidden" name="like-count" id="buttons-like" value="true">
              </button>

              <button class="btn dislikebtn <?php if (select_post_like_user($post_id, $user_id, 0) != null) {
                                                echo 'red';
                                            } ?>" id="btn_dislike" name="btn_liking" value="true" type="submit">
                  <i class="fa fa-thumbs-down fa-lg" aria-hidden="true"></i>
                  <span class="like-count" id="dislike-count"><?= $dislike_count; ?></span>
                  <input type="hidden" name="dislike-count" id="buttons-like" value="true">
              </button>


              <?php if (isset($_GET['success']) && $_GET['success'] == 'ok') : ?>
                  <div class="success">
                      <h5>Thank you for liking</h5>
                  </div>
              <?php endif ?>

          <?php } else {
                echo '<div class="unuser"> Please register or login to Like/Dislike this post!</div>';
            } ?>

      </div>
  </form>