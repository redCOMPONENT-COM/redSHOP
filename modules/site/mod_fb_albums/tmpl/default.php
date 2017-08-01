<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_products
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
$albums = isset($output->albums->data)? $output->albums->data: array();

$count = count($albums);
?>
<?php if ($count > 0): ?>
<div class="container list-gallery">
    <div class="row">
        <div class="tab-gallery">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <?php foreach ($albums as $album): ?>
                <li role="presentation"><a href="#<?php echo isset($album->id)? $album->id: '' ?>" role="tab"
                                           data-toggle="tab"><?php echo isset($album->name)? $album->name: ''
                        ?></a></li>
                <?php endforeach; ?>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <?php foreach ($albums as $album): ?>
                <div role="tabpanel" class="tab-pane" id="<?php echo isset($album->id)? $album->id: '' ?>">
                    <?php if ($count > 0): ?>
                        <?php foreach ($album->photos->data as $photo): ?>
                            <a class="gallery_item cboxElement" href="<?php echo $photo->picture ?>" title="<?php echo isset($album->name)? $album->name: ''
                            ?>" style="background: url('<?php echo $photo->picture ?>');">
                                <img src="<?php echo $photo->picture ?>" alt="<?php echo $album->name ?>" />
                            </a>
                        <?php endforeach; ?>
                    <?php else:?>
                        <?php echo JText::_('MOD_FB_ALBUMS_NO_PHOTO_IN_ALBUM'); ?>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
    <?php echo JText::_('MOD_FB_ALBUMS_NO_ALBUM'); ?>
<?php endif; ?>
