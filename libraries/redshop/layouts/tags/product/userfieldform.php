<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * $displayData extract
 *
 * @param   string  $content
 *
 */
extract($displayData);
?>

<form method='post' action='' id='user_fields_form' name='user_fields_form'>
	<?php echo $content ?>
</form>