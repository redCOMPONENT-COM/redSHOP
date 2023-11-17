<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

/**
 * Layout variables
 * ======================================
 *
 * @var  object $item
 */

extract($displayData);

?>
<div class="control-group">
    <div class="">
        <input class="form-control" type="file" name="logo" id="logo" size="77" />
    </div>
    <div class="">
        <?php
        if (null != $item->logo): ?>
            <div>
                <?php
                $imagePath      = REDSHOP_FRONT_IMAGES_ABSPATH . 'shopperlogo/' . $item->logo;
                $imageThumbPath = RedshopHelperMedia::getImagePath(
                    $item->logo,
                    '',
                    'thumb',
                    'shopperlogo',
                    Redshop::getConfig()->get('THUMB_WIDTH'),
                    Redshop::getConfig()->get('THUMB_HEIGHT'),
                    Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
                );
                ?>
                <?php echo
                    RedshopLayoutHelper::render(
                        'modal.lightbox',
                        [
                            'selector'        => 'ModalShoppergroupLogo',
                            'imageAttributes' => ['alt' => 'Shoppergroup logo', 'id' => 'image_display'],
                            'params'          => [
                                'imageThumbPath' => $imageThumbPath,
                                'imageMainPath'  => $imagePath,
                            ]
                        ]
                    );
                ?>
            </div>
            <?php
        endif; ?>
    </div>
    <input type="hidden" name="logo_tmp" id="logo_tmp" />
    <input type="hidden" name="logo" id="logo" value="<?php
    echo $item->logo; ?>" />
</div>
