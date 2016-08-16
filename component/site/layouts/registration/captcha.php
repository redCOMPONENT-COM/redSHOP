<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$default = JFactory::getConfig()->get('captcha');

if (JFactory::getApplication()->isSite())
{
	$default = JFactory::getApplication()->getParams()->get('captcha', JFactory::getConfig()->get('captcha'));
}

if (!empty($default))
{
	$captcha = JCaptcha::getInstance($default, array('namespace' => 'redshop'));

	if ($captcha != null)
	{
?>

<div class="form-group">
	<?php
		echo $captcha->display('security_code', 'security_code', 'required');
	?>
</div>

<?php } } ?>

