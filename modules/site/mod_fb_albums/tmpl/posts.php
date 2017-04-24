<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_products
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$posts = $output->posts->data;
?>
<?php if (count($posts) > 0): ?>
    <div class="posts-wrapper">
        <?php foreach ($posts as $post): ?>
            <div class="post-container">
                <?php if (isset($post->full_picture)): ?>
                    <div class="post-image">
                        <img src="<?php echo $post->full_picture ?>" alt="<?php echo isset($post->story)? $post->story:
                            $post->id ?>" />
                    </div>
                <?php endif ?>
                <?php if (isset($post->story)): ?>
                    <div class="post-title"><?php echo $post->story ?></div>
                <?php endif ?>

                <?php $createdDate = explode('T', $post->created_time) ?>
                <div class="post-created-date"><?php echo $createdDate[0] ?></div>

                <div class="post-message">
                    <?php echo $post->message ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <?php echo JText::_('MOD_FB_ALBUMS_NO_POSTS'); ?>
<?php endif ?>

