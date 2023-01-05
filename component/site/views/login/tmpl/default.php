<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$app        = JFactory::getApplication();
$Itemid     = $app->input->getInt('Itemid');
$loginlink  = 'index.php?option=com_redshop&view=login&Itemid=' . $Itemid;
$mywishlist = $app->input->getString('wishlist');

if ($mywishlist != '') {
    $newuser_link = 'index.php?wishlist=' . $mywishlist . '&option=com_redshop&view=registration&Itemid=' . $Itemid;
} else {
    $newuser_link = 'index.php?option=com_redshop&view=registration&Itemid=' . $Itemid;
}

$params       = $app->getParams('com_redshop');
$returnitemid = $params->get('login', $Itemid);

$thirdPartyLogin = Redshop\Helper\Login::getThirdPartyLogin();

?>
<?php /* // Tweak by Ronni - Add class="panel panel-default" */  ?>
<div class="panel panel-default">
<div class="panel-heading"><?php echo JText::_('COM_REDSHOP_LOGIN_PAGE_HEADING'); ?> :</div><br><br>
<form action="<?php echo Redshop\IO\Route::_($loginlink); ?>" method="post">
    <div class="redshop-login form-horizontal">
        <?php /* // Tweak by Ronni - Comment out Login desc + change to col-md-2 col-xs-4 + col-md-3 col-xs-8 + changes -
        <p><?php echo JText::_('COM_REDSHOP_LOGIN_DESCRIPTION'); ?></p> */ ?>
        <div class="form-group">
            <div class="col-md-6 col-xs-12" style="text-align:center">
                <span class="input-prepend input-append">
                    <span class="add-on"><i class="fas fa-user"></i>
                        <label class=""></label>
                    </span>
                </span>
                <span class="hasPopover" title="<?php echo JText::_('COM_REDSHOP_USERNAME'); ?>" 
                        data-content="<?php echo JText::_('COM_REDSHOP_USER_LOGIN_TIP'); ?>">
                    <input class="inputbox" type="text" id="username" name="username" autocomplete="username" 
                        placeholder="<?php echo JText::_('COM_REDSHOP_USERNAME'); ?>"/>
                </span>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-xs-12" style="text-align:center">
                <span class="input-prepend input-append">
                    <span class="add-on"><i class="fas fa-lock"></i>
                        <label class=""></label>
                    </span>
                </span>
                <span class="hasPopover" title="<?php echo JText::_('COM_REDSHOP_PASSWORD'); ?>" 
                        data-content="<?php echo JText::_('COM_REDSHOP_PASSWORD_LOGIN_TIP'); ?>">
                    <input class="inputbox" id="password" name="password" type="password" autocomplete="current-password"
                        placeholder="<?php echo JText::_('COM_REDSHOP_PASSWORD'); ?>"/>
                </span>
            </div>
        </div>
        <div style="display:none" class="form-group">
            <div>
                <input id="modlgn-remember" type="checkbox" name="remember" class="col-sm-offset-3" value="1"/>
                <label for="modlgn-remember" class="control-label f-merri fw-300"><?php echo JText::_('COM_REDSHOP_REMEMBER_ME'); ?></label>
            </div>
        </div>
        <br><br>
        <div class="form-group">
            <div class="col-sm-12" style="text-align:center">
                <input type="submit" name="submit" class="btn btn-primary"
                       value="<?php echo JText::_('COM_REDSHOP_LOGIN'); ?>">
            </div>
            <br><br>
            <div class="col-md-12" style="text-align:center">
				<a class="btn btn-small" href="<?php echo Redshop\IO\Route::_('index.php?option=com_users&view=reset'); ?>">
                    <?php echo JText::_('COM_REDSHOP_FORGOT_PWD_LINK'); ?>
                </a>
			</div>
        </div>

		<?php /* Tweak by Ronni START - Add Login first visit message */  ?>
		<br><br><br>
		<div class="alert alert-warning" style="margin-bottom:-10px!important">
			<a class="close" data-dismiss="alert">×</a>
			<h4 class="alert-heading"></h4>
            <div>
				<div class="alert-message"><?php echo JText::_('COM_REDSHOP_LOGIN_ACCOUNT_TIP'); ?></div>
		    </div>
        </div>
		<?php /* Tweak by Ronni END - Add Login first visit message */  ?>
    </div>

    <input type="hidden" name="task" id="task" value="setlogin">
    <input type="hidden" name="mywishlist" id="mywishlist" value="<?php echo $app->input->getString('wishlist'); ?>">
    <input type="hidden" name="product_id" id="product_id" value="<?php echo $app->input->getString('product_id'); ?>">
    <input type="hidden" name="returnitemid" id="returnitemid" value="<?php echo $returnitemid; ?>">
    <input type="hidden" name="option" id="option" value="com_redshop"/>
</form>
</div>
<div class="form-group">
	<div class="third-party-login ">
        <?php foreach ($thirdPartyLogin as $login): ?>
            <?php if (!empty($login['plugin']) && !empty($login['linkLogin'])): ?>
				<div class="row login-<?php echo $login['plugin'] ?>">
					<a href="<?php echo $login['linkLogin']; ?>" class="btn btn-primary login-button">
                        <?php echo ucfirst($login['plugin']) ?>
					</a>
				</div>
            <?php endif; ?>
        <?php endforeach; ?>
	</div>
</div>
