<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$returnUrl = Redshop\IO\Route::_('index.php?option=com_redshop&view=checkout', false);
$returnUrl = base64_encode($returnUrl);
$itemId    = RedshopHelperRouter::getCheckoutItemId();
?>

<form action="" method="POST" class="form-inline" id="redshop-login-form">
    <div class="form-group">
        <label class="label" for="username"><?php echo JText::_('COM_REDSHOP_USERNAME') ?></label>
        <input class="form-control" type="text" id="username" name="username" value=""/>
    </div>
    <div class="form-group">
        <label class="label" for="password"><?php echo JText::_('COM_REDSHOP_PASSWORD') ?></label>
        <input class="form-control" type="password" id="password" name="password" value=""/>
    </div>
    <input type="submit" class="button btn btn-primary" name="submitbtn"
           value="<?php echo JText::_('COM_REDSHOP_LOGIN') ?>">
    <input type="hidden" name="l" value="1">
    <input type="hidden" name="return" value="<?php echo $returnUrl; ?>"/>
    <input type="hidden" name="Itemid" value="<?php echo $itemId; ?>"/>
    <input type="hidden" name="task" value="setlogin"/>
    <input type="hidden" name="option" value="com_redshop"/>
    <input type="hidden" name="view" value="login"/>
    <a href="<?php echo Redshop\IO\Route::_('index.php?option=com_users&view=reset', false); ?>">
        <?php echo JText::_('COM_REDSHOP_FORGOT_PWD_LINK') ?>
    </a>
</form>
