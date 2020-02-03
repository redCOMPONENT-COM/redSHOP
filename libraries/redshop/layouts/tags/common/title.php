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
 * @param   string  $text
 * @param   string  $class
 * @param   string  $tag
 *
 */
extract($displayData);
?>

<<?php echo $tag ?> class="<?php echo $class ?>">
	<?php echo $text ?>
</<?php echo $tag ?>>