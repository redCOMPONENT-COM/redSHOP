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
 * @param   string  $price
 * @param   string  $htmlPrice
 * @param   string  $class
 *
 */
extract($displayData);
?>

<div class="<?php echo $class ?>"><?php echo $htmlPrice ?></div>