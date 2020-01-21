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
 * @param   string  $template
 * @param   string  $commonId
 *
 */
extract($displayData);
?>

<div id='divaccstatus<?php echo $commonId ?>' class='accessorystatus'><?php echo $template ?></div>