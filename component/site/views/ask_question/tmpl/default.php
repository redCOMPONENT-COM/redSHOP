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

if (!$app->input->getInt('questionSend', 0))
{
	$ask = $app->input->getInt('ask', 0);
	$displayData = array(
		'form' => $this->form,
		'ask' => $ask
	);
	echo RedshopLayoutHelper::render('product.ask_question', $displayData);
}
else
{
	?>
<script>
	setTimeout("window.parent.redBOX.close();", 5000);
</script>
<?php
}
