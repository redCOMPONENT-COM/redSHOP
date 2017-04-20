<?php


/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_redmanufacturer
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>
<div id="myMenuID"></div>
<script language="JavaScript" type="text/javascript"><!--
   var cmMainVSplit = [_cmNoAction, '&nbsp;'];
   var cmHSplit = [_cmNoAction, '<td class="ThemeOfficeMenuItemLeft"></td><td colspan="2"><div class="ThemeOfficeMenuSplit"></div></td>'];
   var cmMainHSplit = [_cmNoAction, '<td class="ThemeOfficeMainItemLeft"></td><td colspan="2"><div class="ThemeOfficeMenuSplit"></div></td>'];

   var <?php echo $varname ?> =
   [  
      <?php RedshopJscookCategoryMenuHelper::traverseTreeDown($menuHtml, '0', '0', $params, $shopperGroupId, $iconName); ?>
      <?php echo $menuHtml; ?>
   ]

   cmDraw ('myMenuID', <?php echo $varname ?>, '<?php echo $menuOrientation ?>', <?php echo $jscookTree ?>);
--></script>