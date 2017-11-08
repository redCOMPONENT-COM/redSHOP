<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

extract($displayData);

$view = JFactory::getApplication()->input->getCmd('view');
?>
<a href="<?php echo $link; ?>" class="<?php echo ($active ? 'active': '') ?>">
    <?php if (!empty($icon)): ?><i class="<?php echo $icon ?>"></i><?php endif; ?><span><?php echo $title ?></span>
</a>
