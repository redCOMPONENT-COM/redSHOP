<?php


class MediaPage extends AdminJ3Page
{

	public static $URL = '/administrator/index.php?option=com_redshop&view=media';

	public static $buttonMediaType = ['xpath'=>'//div[@id=\'s2id_0\']'];

	public static $searchMedia = ['id'=>'s2id_autogen3_search'];

	public static $inputFile = ['xpath' => '//input[@id=\'file\']'];

	public static $btnSectionItem = ['id' => 's2id_section_id'];

	public static $searchSectionItem = ['id' => 's2id_autogen1_search'];


}