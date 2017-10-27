<?php


class MediaPage extends AdminJ3Page
{

	public static $URL = '/administrator/index.php?option=com_redshop&view=media';

	public static $buttonMediaType = ['xpath'=>'//div[@id=\'s2id_0\']'];

	public static $searchMedia = ['id'=>'s2id_autogen3_search'];

	public static $inputFile = ['xpath' => '//input[@id=\'file\']'];

	public static $btnSectionItem = ['id' => 's2id_section_id'];

	public static $searchSectionItem = ['id' => 's2id_autogen1_search'];

	public static $buttonSaveMedia = ['xpath' => '//div[@id=\'toolbar-apply\']'];

	public static $fieldMediaAlter = ['xpath' => '//td/input[@name=\'media_alternate_text\']'];

	public static $imageFileAttach = 'test.jpg';

	public static $mediaAlterText = ['xpath' => '//tr[@class=\'row0\']/td[5]'];

	public static $mediaImage =  ['xpath' => '//img'];

	public static $imageXpath = ['xpath' =>'//a/img'];

	//media is youtube
	public static $youTubeId = ['xpath' => '//input[@name=\'youtube_id\']'];

	public static $mediaYou =  ['xpath' => '//tr[@class=\'row0\']/td[3]'];


}