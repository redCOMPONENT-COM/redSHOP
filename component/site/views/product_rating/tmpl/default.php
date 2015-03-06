<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();

if ($this->params->get('show_page_heading', 1))
{
	?>
	<div class="componentheading<?php echo $this->params->get('pageclass_sfx') ?>">
		<?php echo $this->escape($this->productinfo->product_name); ?>
	</div>
<?php
}

if (!$app->input->getInt('rate', 0))
{
	$displayData = array(
		'form' => $this->form
	);
	echo RedshopLayoutHelper::render('product.rating', $displayData);
}
elseif ($app->input->getCmd('tmpl') == 'component')
{
	?>
	<script>
		setTimeout("window.parent.redBOX.close();", 5000);
	</script>
<?php
}
