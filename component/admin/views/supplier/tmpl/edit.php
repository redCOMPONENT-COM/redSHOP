<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton)
	{
		var email = document.getElementById("jform_email").value;
		if (checkmail(email) === false) {
			alert("<?php echo JText::_('COM_REDSHOP_EMAIL_INVALID', true); ?>");
		}
		else {
			submitform(pressbutton);
		}
	}
</script>
<?php
echo RedshopLayoutHelper::render('view.edit.' . $this->formLayout, array('data' => $this));
