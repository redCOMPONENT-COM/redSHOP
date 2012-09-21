<?php
/** 
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved. 
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com 
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

$fid = JRequest::getVar('fid');
$fsec = JRequest::getVar('fsec');

$link = "index.php?option=com_redshop&amp;view=media&amp;layout=thumbs";
if (isset($fsec))
	$link .="&amp;fsec=".$fsec;
if (isset($fid))	
$link .="&amp;fid=".$fid;

$link .="&amp;folder=".$this->state->folder;

?>
<form action="<?php echo $link;?>" method="post" id="mediamanager-form" name="mediamanager-form">
	<div class="manager">
		<?php 
		$folder = JRequest::getVar('folder','');
		if ($folder!= '')
			echo $this->loadTemplate('up');	
		?>

		<?php for ($i=0,$n=count($this->folders); $i<$n; $i++) :
			$this->setFolder($i);
			if ($this->_tmp_folder->name != 'thumb')
				echo $this->loadTemplate('folder');
		endfor; ?>

		<?php for ($i=0,$n=count($this->documents); $i<$n; $i++) :
			$this->setDoc($i);
			echo $this->loadTemplate('doc');
		endfor; ?>

		<?php 
		if(count($this->images)>0)
		{
			for ($i=0,$n=count($this->images); $i<$n; $i++) :
				$this->setImage($i);
				echo $this->loadTemplate('img');
			endfor;
		} 
		
		if(count($this->images)==0 && count($this->documents)==0)
		{
			echo "No Records Found";
		} 
		?>

	</div>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="username" value="" />
	<input type="hidden" name="password" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>