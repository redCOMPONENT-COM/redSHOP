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

defined ( '_JEXEC' ) or die ( 'restricted access' );

JHTMLBehavior::modal ();
jimport ( 'joomla.filesystem.file' );

$option = JRequest::getVar ( 'option' );
$tid = JRequest::getCmd ( 'tid' );
$model = $this->getModel ();
?>

<script language="javascript" type="text/javascript">

function mainProductSet(el){

	if(document.getElementById('mainindex')){

		if(el.id == "main"){

			document.getElementById('mainindex').value = "main";
		}else if (el.id == "additional"){

			document.getElementById('mainindex').value = "additional";
		}
	}

}

</script>
<fieldset><legend><?php
echo JText::_ ( 'TOKEN_VARIFICATION' );
?></legend>
<form name="downloadproduct" id="downloadproduct" method="post"
	action="<?php echo JURI::root().'index.php?option=com_redshop&view=product&layout=downloadproduct&tmpl=component&no_html=1';?>">
<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td colspan="2"><?php
		echo JText::_ ( 'TOKEN_ID' );
		?></td>
		<td><input type="text" name="download_id" id="download_id"
			value="<?php
			echo $tid;
			?>" size="35" /></td>
		<td><input type="submit" name="submit_token" id="submit_token"
			value="<?php
			echo JText::_ ( 'SUBMIT_TOKEN' );
			?>" /></td>
	</tr>
	<tr>
		<td>
			<input type="hidden" name="task" value="downloadProduct" />
			<input type="hidden" name="option" value="com_redshop" />
			<input type="hidden" name="view" value="product" />
			<input type="hidden" name="layout" value="downloadproduct" />
		</td>
	</tr>
</table>
</form>
</fieldset>

<?php
if (isset ( $tid ) && $tid != "") {

	$downloaddata = $model->downloadProduct ( $tid );

	?>
	<fieldset>
	<legend><?php echo JText::_ ( 'DOWNLOAD_PRODUCTS' ); ?></legend>
	<?php
	if (count ( $downloaddata ) > 0 && $downloaddata->media_id != NULL) {
	?>

<div>
<form name="downloadproduct" id="downloadproduct" method="post"
	action="<?php echo JURI::root().'index.php?option=com_redshop&view=product&layout=downloadproduct&tmpl=component&no_html=1';?>">
<table cellpadding="0" cellspacing="0" width="100%">
<?php

		$mid = $downloaddata->media_id;
		$name = $downloaddata->file_name;
		$addtional_downloaddata = $model->AdditionaldownloadProduct ( $mid );

		$filetype = strtolower ( JFile::getExt ( $name ) );

		$downloadname = substr(basename($name),11);
		?>

		<tr>
			<td>
				<input type="radio" value="<?php echo $mid;?>" name="additional" id="main" onchange="mainProductSet(this);" /><?php echo $downloadname; ?>
		 	</td>
		</tr>
		<?php
		for($i = 0; $i < count ( $addtional_downloaddata ); $i ++) {

			$additionalid = $addtional_downloaddata [$i]->id;
			$additionalname = $addtional_downloaddata [$i]->name;

			$additionalfiletype = strtolower ( JFile::getExt ( $additionalname ) );

			$additionaldownloadname = substr(basename($additionalname),11);

			?>
			<tr>
				<td>
					<input type="radio" value="<?php echo $additionalid;?>" name="additional" id="additional" onchange="mainProductSet(this);" /><?php echo $additionaldownloadname;?>
				</td>
			</tr>
			<?php
		}
		?>
		<tr>
		<td>
			<input type="hidden" name="task" value="Download" />
			<input type="hidden" name="option" value="com_redshop" />
			<input type="hidden" name="view" value="product" />
			<input type="hidden" name="layout" value="downloadproduct" />
			<input type="hidden" name="tid" value="<?php echo $tid; ?>" />
			<input type="hidden" name="mainindex" id="mainindex" value="" />
		</td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Download" />
		</td>
	</tr>
</table>
</form>
</div>

<?php
	}else{
		echo JText::_('FILE_NOT_AVAILABLE_IN_DB');
	}
?>
	</fieldset>
<?php

}
?>