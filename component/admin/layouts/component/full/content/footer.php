<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$redshopversion = RedshopModel::getInstance('Configuration', 'RedshopModel')->getCurrentVersion();

$year = JFactory::getDate()->format('Y');

?>

<div class="pull-right hidden-xs">
    <div class="redshopversion">
        <small><?php echo JText::_('COM_REDSHOP_VERSION');?></small>
        <span class="label label-info"><?php echo $redshopversion;?></span>
    </div>
</div>
<strong>Copyright &copy; 2008-<?php echo $year ?> <a target="_blank" href="http://redcomponent.com">redCOMPONENT</a>.</strong> All rights reserved.

