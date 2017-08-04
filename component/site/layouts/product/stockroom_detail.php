<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

extract($displayData);
?>

<?php if (count($stockroomDetails) > 0) : ?>
    <div class="redshop_stockrooms">
		<?php foreach ($stockroomDetails as $stockroomDetail) : ?>
            <div class="redshop_stockroom">
                <span><?php echo $stockroomDetail->stockroom_name ?></span>:<span><?php echo $stockroomDetail->quantity ?></span>
            </div>
		<?php endforeach; ?>
    </div>
<?php endif; ?>
