<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

JHtml::addIncludePath(JPATH_LIBRARIES . '/redshop/html');
defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');

JHTMLBehavior::modal();
JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select', null, array('disable_search_threshold' => 0));

$document = JFactory::getDocument();
$document->addScript('components/com_redshop/assets/js/validation.js');
?>
<form
	action="<?php echo JRoute::_('index.php?option=com_redshop&view=manufacturer&layout=edit&id=' . $this->item->get('id', 0)); ?>"
	method="post" name="adminForm" id="adminForm">

	<?php
	echo RedshopLayoutHelper::render(
		'component.full.tab.main',
		array(
			'view'    => $this,
			'tabMenu' => $this->tabmenu->getData('tab')->items,
		)
	);
	?>
	<fieldset>
		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>
	</fieldset>
</form>


