<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_promote_free_shipping
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$condition_text = $params->get("condition_text");

echo "<div id='rs_promote_free_shipping_div'>" . $text . "</div><br><div id='redconditionDiv'>" . $condition_text . "</div>";
