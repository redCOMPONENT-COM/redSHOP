<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
?>
<?php if (!$app->input->getInt('rate', 0)) : ?>
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
		<div class="componentheading<?php echo $this->params->get('pageclass_sfx') ?>">
			<?php echo $this->escape($this->productInfo->product_name); ?>
		</div>
	<?php endif; ?>
	<?php
		$displayData = array(
			'form' => $this->form,
			'modal' => 1,
			'product_id' => $this->productId
		);
		echo RedshopLayoutHelper::render('product.product_rating', $displayData);
	?>
<?php else : ?>
	<script>
		setTimeout("window.parent.location.reload(); window.parent.redBOX.close();", 5000);
	</script>
<?php endif; ?>
