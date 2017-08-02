<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$jinput = JFactory::getApplication()->input;

$fid  = $jinput->get('fid');
$fsec = $jinput->get('fsec');

$link = "index.php?option=com_redshop&amp;view=media&amp;layout=thumbs";
if (isset($fsec))
	$link .= "&amp;fsec=" . $fsec;
if (isset($fid))
	$link .= "&amp;fid=" . $fid;

$link .= "&amp;folder=" . $this->state->folder;

?>
<form action="<?php echo $link; ?>" method="post" id="mediamanager-form" name="mediamanager-form">
	<div class="manager">
		<?php
		$folder = $jinput->get('folder', '');
		if ($folder != '')
			echo $this->loadTemplate('up');
		?>

		<?php for ($i = 0, $n = count($this->folders); $i < $n; $i++) :
			$this->setFolder($i);
			if ($this->_tmp_folder->name != 'thumb')
				echo $this->loadTemplate('folder');
		endfor; ?>

		<?php for ($i = 0, $n = count($this->documents); $i < $n; $i++) :
			$this->setDoc($i);
			echo $this->loadTemplate('doc');
		endfor; ?>

		<?php
		if (count($this->images) > 0)
		{
			for ($i = 0, $n = count($this->images); $i < $n; $i++) :
				$this->setImage($i);
				echo $this->loadTemplate('img');
			endfor;
		}

		if (count($this->images) == 0 && count($this->documents) == 0)
		{
			echo "No Records Found";
		}
		?>

	</div>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="username" value=""/>
	<input type="hidden" name="password" value=""/>
	<?php echo JHTML::_('form.token'); ?>
</form>
