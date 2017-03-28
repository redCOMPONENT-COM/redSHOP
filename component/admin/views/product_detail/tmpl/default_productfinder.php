<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$accosiation_id = 0;
$ordering       = 1;

if (!empty($this->getassociation))
{
    $accosiation_id = $this->getassociation->id;
    $ordering       = $this->getassociation->ordering;
}

?>
  <div class="col50">
    <table class="adminform">
      <tr>
        <td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TAG_NAME_TIP'), JText::_('COM_REDSHOP_TAG_NAME'), 'tooltip.png', '', '', false); ?> <?php echo JText::_('COM_REDSHOP_TAG_NAME'); ?> </td>
        <td><?php echo $this->lists['tags']; ?> </td>
      </tr>
    </table>
    <input type="hidden" name="association_id" value="<?php echo $accosiation_id; ?>" />
    <input type="hidden" name="ordering" value="<?php echo $ordering; ?>" />
  </div>
