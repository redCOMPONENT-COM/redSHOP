<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

extract($displayData);

$toolbar = JToolbar::getInstance('toolbar');

$redshopversion = RedshopModel::getInstance('Configuration', 'RedshopModel')->getcurrentversion();

?>

<div class="component-title">
	<?php echo JFactory::getApplication()->JComponentTitle; ?>

	<div class="redshopversion">
		<small><?php echo JText::_('COM_REDSHOP_VERSION');?></small>
		<span class="label label-info"><?php echo $redshopversion;?></span>
	</div>
</div>

<?php echo $toolbar->render() ?>

<div class="row-fluid message-sys" id="message-sys"></div>
