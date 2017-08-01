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

$default = JFactory::getConfig()->get('captcha');

if ($app->isSite())
{
	$default = $app->getParams()->get('captcha', JFactory::getConfig()->get('captcha'));
}
?>

<?php if (!empty($default)): ?>
	<?php $captcha = JCaptcha::getInstance($default, array('namespace' => 'redshop')); ?>

	<?php if ($captcha != null): ?>
        <div class="form-group">
			<?php echo $captcha->display('security_code', 'security_code', 'required'); ?>
        </div>
	<?php endif; ?>
<?php endif; ?>

