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
 * @param   string  $fullImage
 * @param   string  $title
 * @param   string  $attr
 * @param   string  $thumbUrl
 *
 */
extract($displayData);
?>

<a href='<?php echo $fullImage ?>' title='<?php echo $title ?>' <?php echo $attr ?>>
	<img src='<?php echo $thumbUrl ?>'>
</a>