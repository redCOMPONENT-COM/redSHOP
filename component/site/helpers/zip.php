<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

class zipfile
{
	/**
	 * Array to store compressed data
	 *
	 * @var  array $datasec
	 */
	public $datasec = array();

	/**
	 * Central directory
	 *
	 * @var  array $ctrl_dir
	 */
	public $ctrl_dir = array();

	/**
	 * End of central directory record
	 *
	 * @var  string $eof_ctrl_dir
	 */
	public $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";

	/**
	 * Last offset position
	 *
	 * @var  integer $old_offset
	 */
	public $old_offset = 0;

	/**
	 * Converts an Unix timestamp to a four byte DOS date and time format (date
	 * in high two bytes, time in low two bytes allowing magnitude comparison).
	 *
	 * @param   integer  $unixtime  the current Unix timestamp
	 *
	 * @return  integer             the current date in a four byte DOS format
	 *
	 * @access private
	 */
	public function unix2DosTime($unixtime = 0)
	{
		$timearray = ($unixtime == 0) ? getdate() : getdate($unixtime);

		if ($timearray['year'] < 1980)
		{
			$timearray['year']    = 1980;
			$timearray['mon']     = 1;
			$timearray['mday']    = 1;
			$timearray['hours']   = 0;
			$timearray['minutes'] = 0;
			$timearray['seconds'] = 0;
		}

		return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) |
			($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
	}

	/**
	 * Adds "file" to archive
	 *
	 * @param   string   $data  file contents
	 * @param   string   $name  name of the file in the archive (may contains the path)
	 * @param   integer  $time  the current timestamp
	 *
	 * @access public
	 *
	 * @return  void
	 */
	public function addFile($data, $name, $time = 0)
	{
		$name = str_replace('\\', '/', $name);

		$dtime    = dechex($this->unix2DosTime($time));
		$hexdtime = '\x' . $dtime[6] . $dtime[7]
			. '\x' . $dtime[4] . $dtime[5]
			. '\x' . $dtime[2] . $dtime[3]
			. '\x' . $dtime[0] . $dtime[1];
		eval('$hexdtime = "' . $hexdtime . '";');

		$fr = "\x50\x4b\x03\x04";

		// Ver needed to extract
		$fr .= "\x14\x00";

		// Gen purpose bit flag
		$fr .= "\x00\x00";

		// Compression method
		$fr .= "\x08\x00";

		// Last mod time and date
		$fr .= $hexdtime;

		// "Local file header" segment
		$unc_len = strlen($data);
		$crc     = crc32($data);
		$zdata   = gzcompress($data);

		// Fix crc bug
		$zdata   = substr(substr($zdata, 0, strlen($zdata) - 4), 2);
		$c_len   = strlen($zdata);

		// Crc32
		$fr .= pack('V', $crc);

		// Compressed filesize
		$fr .= pack('V', $c_len);

		// Uncompressed filesize
		$fr .= pack('V', $unc_len);

		// Length of filename
		$fr .= pack('v', strlen($name));

		// Extra field length
		$fr .= pack('v', 0);
		$fr .= $name;

		// "File data" segment
		$fr .= $zdata;

		/*
		 * "data descriptor" segment (optional but necessary if archive is not
		 * served as file) nijel(2004-10-19): this seems not to be needed at all and causes
		 * problems in some cases (bug #1037737)
		 * $fr .= pack('V', $crc);                 // Crc32
		 * $fr .= pack('V', $c_len);               // Compressed filesize
		 * $fr .= pack('V', $unc_len);             // Uncompressed filesize
		 */

		// Add this entry to array
		$this->datasec[] = $fr;

		// Now add to central directory record
		$cdrec = "\x50\x4b\x01\x02";

		// Version made by
		$cdrec .= "\x00\x00";

		// Version needed to extract
		$cdrec .= "\x14\x00";

		// Gen purpose bit flag
		$cdrec .= "\x00\x00";

		// Compression method
		$cdrec .= "\x08\x00";

		// Last mod time & date
		$cdrec .= $hexdtime;

		// Crc32
		$cdrec .= pack('V', $crc);

		// Compressed filesize
		$cdrec .= pack('V', $c_len);

		// Uncompressed filesize
		$cdrec .= pack('V', $unc_len);

		// Length of filename
		$cdrec .= pack('v', strlen($name));

		// Extra field length
		$cdrec .= pack('v', 0);

		// File comment length
		$cdrec .= pack('v', 0);

		// Disk number start
		$cdrec .= pack('v', 0);

		// Internal file attributes
		$cdrec .= pack('v', 0);

		// External file attributes - 'archive' bit set
		$cdrec .= pack('V', 32);

		// Relative offset of local header
		$cdrec .= pack('V', $this->old_offset);
		$this->old_offset += strlen($fr);

		$cdrec .= $name;

		// Optional extra field, file comment goes here
		// Save to central directory
		$this->ctrl_dir[] = $cdrec;
	}

	/**
	 * Dumps out file
	 *
	 * @return  string  the zipped file
	 *
	 * @access public
	 */
	public function file()
	{
		$data    = implode('', $this->datasec);
		$ctrldir = implode('', $this->ctrl_dir);

		return
			$data .
			$ctrldir .
			$this->eof_ctrl_dir .
			pack('v', count($this->ctrl_dir)) .
			pack('v', count($this->ctrl_dir)) .
			pack('V', strlen($ctrldir)) .
			pack('V', strlen($data)) .
			"\x00\x00";
	}
}
