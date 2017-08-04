<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * $displayData extract
 *
 * @param   object  $form        A JForm object
 * @param   int     $product_id  Id current product
 * @param   int     $modal       Flag use form in modal
 */
extract($displayData);

?>

<tr>
	<td><?php echo $extra_field_label;?>:</td>
	<td><?php echo $extra_field_value;?></td>
</tr>
