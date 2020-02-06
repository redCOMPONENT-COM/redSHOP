<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * $displayData extract
 *
 * @param   string  $title
 * @param   string  $readMoreLink
 * @param   string  $class
 *
 */
extract($displayData);
?>

<a class="<?php echo $class ?>" href='<?php echo $readMoreLink ?>' title='<?php echo $title ?>'>
	<?php echo JText::_('COM_REDSHOP_READ_MORE') ?>
</a>