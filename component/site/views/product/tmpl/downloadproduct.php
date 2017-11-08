<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTMLBehavior::modal();
JLoader::import('joomla.filesystem.file');

$tid    = $this->input->get('tid', null);
$model  = $this->getModel();
?>

	<script language="javascript" type="text/javascript">

		function mainProductSet(el) {
			if (document.getElementById('mainindex')) {
				if (el.id == "main") {
					document.getElementById('mainindex').value = "main";
				} else if (el.id == "additional") {
					document.getElementById('mainindex').value = "additional";
				}
			}

		}

	</script>
	<fieldset>
		<legend><?php
			echo JText::_('COM_REDSHOP_TOKEN_VARIFICATION');
			?></legend>
		<form name="downloadproduct" id="downloadproduct" method="post"
		      action="<?php echo JURI::root() . 'index.php?option=com_redshop&view=product&layout=downloadproduct&tmpl=component&no_html=1'; ?>">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td colspan="2"><?php
						echo JText::_('COM_REDSHOP_TOKEN_ID');
						?></td>
					<td>
						<input type="text" name="download_id" id="download_id"
							value="<?php echo $tid; ?>" size="35"/></td>
					<td>
						<input type="submit" name="submit_token" id="submit_token"
							value="<?php echo JText::_('COM_REDSHOP_SUBMIT_TOKEN'); ?>"/></td>
				</tr>
				<tr>
					<td>
						<input type="hidden" name="task" value="downloadProduct"/>
						<input type="hidden" name="option" value="com_redshop"/>
						<input type="hidden" name="view" value="product"/>
						<input type="hidden" name="layout" value="downloadproduct"/>
					</td>
				</tr>
			</table>
		</form>
	</fieldset>

<?php
if (isset ($tid) && $tid != "")
{
	$downloaddata = $model->downloadProduct($tid);

	?>
	<fieldset>
		<legend><?php echo JText::_('COM_REDSHOP_DOWNLOAD_PRODUCTS'); ?></legend>
		<?php
		if (count($downloaddata) > 0 && $downloaddata->media_id != null)
		{
			?>

			<div>
				<form name="downloadproduct" id="downloadproduct" method="post"
				      action="<?php echo JURI::root() . 'index.php?option=com_redshop&view=product&layout=downloadproduct&tmpl=component&no_html=1'; ?>">
					<table cellpadding="0" cellspacing="0" width="100%">
						<?php

						$mid = $downloaddata->media_id;
						$name = $downloaddata->file_name;
						$addtional_downloaddata = $model->AdditionaldownloadProduct($mid);

						$filetype = strtolower(JFile::getExt($name));

						$downloadname = substr(basename($name), 11);
						?>

						<tr>
							<td>
								<input type="radio" value="<?php echo $mid; ?>" name="additional" id="main"
								       onchange="mainProductSet(this);"/><?php echo $downloadname; ?>
							</td>
						</tr>
						<?php
						for ($i = 0, $in = count($addtional_downloaddata); $i < $in; $i++)
						{
							$additionalid   = $addtional_downloaddata [$i]->id;
							$additionalname = $addtional_downloaddata [$i]->name;

							$additionalfiletype = strtolower(JFile::getExt($additionalname));

							$additionaldownloadname = substr(basename($additionalname), 11);

							?>
							<tr>
								<td>
									<input type="radio" value="<?php echo $additionalid; ?>" name="additional"
									       id="additional"
									       onchange="mainProductSet(this);"/><?php echo $additionaldownloadname;?>
								</td>
							</tr>
						<?php
						}
						?>
						<tr>
							<td>
								<input type="hidden" name="task" value="Download"/>
								<input type="hidden" name="option" value="com_redshop"/>
								<input type="hidden" name="view" value="product"/>
								<input type="hidden" name="layout" value="downloadproduct"/>
								<input type="hidden" name="tid" value="<?php echo $tid; ?>"/>
								<input type="hidden" name="mainindex" id="mainindex" value=""/>
							</td>
						</tr>
						<tr>
							<td colspan="2"><input type="submit" name="submit" value="Download"/>
							</td>
						</tr>
					</table>
				</form>
			</div>

		<?php
		}
		else
		{
			echo JText::_('COM_REDSHOP_FILE_NOT_AVAILABLE_IN_DB');
		}
		?>
	</fieldset>
<?php
}
