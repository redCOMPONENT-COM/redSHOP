<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Software: mPDF, Unicode-HTML Free PDF generator						*
 * Version:  X.0		   based on										*
 *		   FPDF by Olivier PLATHEY										*
 *		   HTML2FPDF by Renato Coelho									*
 * Date:	 2010-09-19													*
 * Author:   Ian Back <ianb@bpm1.com>									*
 * Author:   Carl Holmberg <info@talgdank.se>							*
 * License:  GPL														*
 *																		*
 * Changes:  See changelog.txt											*
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class Error
{

	public function __construct($msg) {
		die('MPDF-error\n'.$msg);
	}

}


class Color
{
	public static function lighten($c)
	{
		// mPDF 5.3.74
		if (is_array($c)) {
			die('Color error in Color::lighten()');
		}
		if ($c[0]==3 || $c[0]==5) { 	// RGB
			list($h,$s,$l) = self::rgb2hsl(ord($c[1])/255,ord($c[2])/255,ord($c[3])/255);
			$l += ((1 - $l)*0.8);
			list($r,$g,$b) = self::hsl2rgb($h,$s,$l);
			$ret = array(3,$r,$g,$b);
		}
		else if ($c[0]==4 || $c[0]==6) { 	// CMYK
			$ret = array(4, max(0,(ord($c[1])-20)), max(0,(ord($c[2])-20)), max(0,(ord($c[3])-20)), max(0,(ord($c[4])-20)) );
		}
		else if ($c[0]==1) {	// Grayscale
			$ret = array(1,min(255,(ord($c[1])+32)));
		}
		$c = array_pad($ret, 6, 0);
		$cstr = pack("a1ccccc", $c[0], ($c[1] & 0xFF), ($c[2] & 0xFF), ($c[3] & 0xFF), ($c[4] & 0xFF), ($c[5] & 0xFF) ); 
		return $cstr;
	}
	
	
	public static function darken($c)
	{	
		if (is_array($c)) {
			die('Color error in Color::darken()');
		}
		// mPDF 5.3.74
		if ($c[0]==3 || $c[0]==5) { 	// RGB
			list($h,$s,$l) = self::rgb2hsl(ord($c[1])/255,ord($c[2])/255,ord($c[3])/255);
			$s *= 0.25;
			$l *= 0.75;
			list($r,$g,$b) = self::hsl2rgb($h,$s,$l);
			$ret = array(3,$r,$g,$b);
		}
		else if ($c[0]==4 || $c[0]==6) { 	// CMYK
			$ret = array(4, min(100,(ord($c[1])+20)), min(100,(ord($c[2])+20)), min(100,(ord($c[3])+20)), min(100,(ord($c[4])+20)) );
		}
		else if ($c[0]==1) {	// Grayscale
			$ret = array(1,max(0,(ord($c[1])-32)));
		}
		$c = array_pad($ret, 6, 0);
		$cstr = pack("a1ccccc", $c[0], ($c[1] & 0xFF), ($c[2] & 0xFF), ($c[3] & 0xFF), ($c[4] & 0xFF), ($c[5] & 0xFF) ); 
		return $cstr;
	}
	
	static function rgb2gray($c)
	{
		if (isset($c[4])) {
			return array(1,($c[1] * .21 + $c[2] * .71 + $c[3] * .07), $c[4]);
		} else {
			return array(1,($c[1] * .21 + $c[2] * .71 + $c[3] * .07));
		}
	}
	
	static function cmyk2gray($c)
	{
		return self::rgb2gray(self::cmyk2rgb($c));
	}

	static function rgb2cmyk($c)
	{
		$cyan = 1 - $c[1] / 255;
		$magenta = 1 - $c[2] / 255;
		$yellow = 1 - $c[3] / 255;
		$K = min($cyan, $magenta, $yellow);

		if ($K == 1) {
			if ($c[0] == 5) {
				return array (6, 100, 100, 100, 100, $c[4]);
			} else {
				return array (4, 100, 100, 100, 100);
			}
		}
		$black = 100/(1 - $K);
		if ($c[0] == 5) {
			return array (6,($cyan-$K)*$black, ($magenta-$K)*$black, ($yellow-$K)*$black, $K*100, $c[4]);
		} else {
			return array (4,($cyan-$K)*$black, ($magenta-$K)*$black, ($yellow-$K)*$black, $K*100);
		}
	}
	
	
	static function cmyk2rgb($c)
	{
		$colors = 255 - ($c[4]*2.55);
		$r = intval($colors * (1 - $c[1]*0.01));
		$g = intval($colors * (1 - $c[2]*0.01));
		$b = intval($colors * (1 - $c[3]*0.01));
		if ($c[0] == 6) {
			return array (5, $r, $g, $b, $c[5]);
		} else {
			return array (3, $r, $g, $b);
		}
	}
	
	public static function rgb2hsl($var_r, $var_g, $var_b)
	{
		$var_min = min($var_r,$var_g,$var_b);
		$var_max = max($var_r,$var_g,$var_b);
		$del_max = $var_max - $var_min;
		$l = ($var_max + $var_min) / 2;
		if ($del_max == 0) {
				$h = 0;
				$s = 0;
		}
		else {
				if ($l < 0.5) { $s = $del_max / ($var_max + $var_min); }
				else { $s = $del_max / (2 - $var_max - $var_min); }
				$del_r = ((($var_max - $var_r) / 6) + ($del_max / 2)) / $del_max;
				$del_g = ((($var_max - $var_g) / 6) + ($del_max / 2)) / $del_max;
				$del_b = ((($var_max - $var_b) / 6) + ($del_max / 2)) / $del_max;
				if ($var_r == $var_max) { $h = $del_b - $del_g; }
				elseif ($var_g == $var_max)  { $h = (1 / 3) + $del_r - $del_b; }
				elseif ($var_b == $var_max)  { $h = (2 / 3) + $del_g - $del_r; };
				if ($h < 0) { $h += 1; }
				if ($h > 1) { $h -= 1; }
		}
		return array($h,$s,$l);
	}
	
	
	function hsl2rgb($h2,$s2,$l2)
	{
		// Input is HSL value of complementary colour, held in $h2, $s, $l as fractions of 1
		// Output is RGB in normal 255 255 255 format, held in $r, $g, $b
		// Hue is converted using function hue2rgb, shown at the end of this code
		if ($s2 == 0) {
			$r = $l2 * 255;
			$g = $l2 * 255;
			$b = $l2 * 255;
		}
		else {
			if ($l2 < 0.5) { $var_2 = $l2 * (1 + $s2); }
			else { $var_2 = ($l2 + $s2) - ($s2 * $l2); }
			$var_1 = 2 * $l2 - $var_2;
			$r = round(255 * self::hue2rgb($var_1,$var_2,$h2 + (1 / 3)));
			$g = round(255 * self::hue2rgb($var_1,$var_2,$h2));
			$b = round(255 * self::hue2rgb($var_1,$var_2,$h2 - (1 / 3)));
		}
		return array($r,$g,$b);
	}
	
	static function hue2rgb($v1,$v2,$vh)
	{
		// Function to convert hue to RGB, called from above
		if ($vh < 0) { $vh += 1; };
		if ($vh > 1) { $vh -= 1; };
		if ((6 * $vh) < 1) { return ($v1 + ($v2 - $v1) * 6 * $vh); };
		if ((2 * $vh) < 1) { return ($v2); };
		if ((3 * $vh) < 2) { return ($v1 + ($v2 - $v1) * ((2 / 3 - $vh) * 6)); };
		return ($v1);
	}
	
	static function invert($cor)
	{
		if ($cor[0]==3 || $cor[0]==5) {	// RGB
			return array(3, (255-$cor[1]), (255-$cor[2]), (255-$cor[3]));
		}
		else if ($cor[0]==4 || $cor[0]==6) {	// CMYK
			return array(4, (100-$cor[1]), (100-$cor[2]), (100-$cor[3]), (100-$cor[4]));
		}
		else if ($cor[0]==1) {	// Grayscale
			return array(1, (255-$cor[1]));
		}	
		// Cannot cope with non-RGB colors at present
		die('Error in Color::invert() - trying to invert non-RGB color');
	}
	
	static function array2string($cor)
	{
		$s = '';
		if ($cor[0]==1) $s = 'rgb('.$cor[1].','.$cor[1].','.$cor[1].')';
		else if ($cor[0]==2) $s = 'spot('.$cor[1].','.$cor[2].')';		// SPOT COLOR
		else if ($cor[0]==3) $s = 'rgb('.$cor[1].','.$cor[2].','.$cor[3].')';
		else if ($cor[0]==4) $s = 'cmyk('.$cor[1].','.$cor[2].','.$cor[3].','.$cor[4].')';
		else if ($cor[0]==5) $s = 'rgba('.$cor[1].','.$cor[2].','.$cor[3].','.$cor[4].')';
		else if ($cor[0]==6) $s = 'cmyka('.$cor[1].','.$cor[2].','.$cor[3].','.$cor[4].','.$cor[5].')';
		return $s;
	}
	
	static function setColor($col, $type='')
	{
		$out = '';
		// mPDF 5.3.74
		if ($col[0]==3 || $col[0]==5) {	// RGB / RGBa
			$out = sprintf('%.3F %.3F %.3F rg',ord($col{1})/255,ord($col{2})/255,ord($col{3})/255);
		}
		else if ($col[0]==1) {	// GRAYSCALE
			$out = sprintf('%.3F g',ord($col{1})/255);
		}
		else if ($col[0]==2) {	// SPOT COLOR
			$out = sprintf('/CS%d cs %.3F scn',ord($col{1}),ord($col{2})/100);
		}
		else if ($col[0]==4 || $col[0]==6) {	// CMYK / CMYKa
			$out = sprintf('%.3F %.3F %.3F %.3F k', ord($col{1})/100, ord($col{2})/100, ord($col{3})/100, ord($col{4})/100);
		}
		if ($type=='Draw') { $out = strtoupper($out); }	// e.g. rg => RG
		else if ($type=='CodeOnly') { $out = preg_replace('/\s(rg|g|k)/','',$out); }
		return $out; 
	}
}

class Text
{
	static function getCharWidth(&$cw, $u, $isdef=true)
	{
		if ($u == 0) {
			$w = false;
		} else {
			$w = (ord($cw[$u*2]) << 8) + ord($cw[$u*2+1]);
		}
		if ($w == 65535) {
			return 0;
		} else if ($w) {
			return $w;
		} else if ($isdef) {
			return false;
		} else {
			return 0;
		}
	}
	
	static function charDefined(&$cw, $u)
	{
		if ($u == 0) {
			return false;
		}
		$w = (ord($cw[$u*2]) << 8) + ord($cw[$u*2+1]);
		return ($w)? true : false;
	}
	
	static function escape($s)
	{
		return strtr($s, array(')' => '\\)', '(' => '\\(', '\\' => '\\\\', "\xd" => '\r'));
	}
	
	static function lesser_entity_decode($html)
	{
		//supports the most used entity codes (only does ascii safe characters)
		return str_replace(array('&nbsp;', '&lt;', '&gt;', '&apos;', '&quot;', '&amp;'),
						   array(' ', '<', '>', "'", '"', '&'),
						   $html);
	}
	
	/**
	 * is_utf8
	 */
	public static function is_utf8(&$string)
	{
		$str = mb_convert_encoding(mb_convert_encoding($string, 'UTF-32', 'UTF-8'),
								   'UTF-8', 'UTF-32');
		return ($string === $str);
	}


	/**
	 * reverseLetters
	 * @since: mPDF 4.0
	 */
	public static function reverseLetters($str)
	{
		$str = strtr($str, '{}[]()', '}{][)(');
		return join("", array_reverse(
        	preg_split("//u", $str)
    	)); 
	}
	
	
	/**
	 * purify_utf8_text
	 * Make sure UTF-8 string of characters
	 */
	public static function purify_utf8_text($txt)
	{
		if (!self::is_utf8($txt)) {
			new Error('Text contains invalid UTF-8 character(s)');
		}
		return str_replace("\r", '', $txt);
	}
	
	/**
	 * purify_utf8
	 * Checks string is valid UTF-8 encoded
	 * converts html_entities > ASCII 127 to UTF-8
	 * Only exception - leaves low ASCII entities e.g. &lt; &amp; etc.
	 * Leaves in particular &lt; to distinguish from tag marker
	 */
	public static function purify_utf8($html, $lo=true)
	{
		$html = self::purify_utf8_text($html);
		$html = self::substituteHiEntities($html);
		return self::strcode2utf($html, $lo);
	}
	
	
	static function utf8_entity_decode($entity)
	{
		$convmap = array(0x0, 0x10000, 0, 0xfffff);
		return mb_decode_numericentity($entity, $convmap, 'UTF-8');
	}


	/**
	 * strcode2utf
	 * Converts all &#nnn; or &#xHHH; to UTF-8 multibyte
	 * If $lo==true then includes ASCII < 128
	 */
	public static function strcode2utf($str)
	{
		//decode decimal HTML entities added by web browser
  		$str = preg_replace('/&#\d{2,5};/ue', "Text::utf8_entity_decode('$0')", $str);
  		//decode hex HTML entities added by web browser
  		return preg_replace('/&#x([a-fA-F0-9]{2,8});/ue', "Text::utf8_entity_decode('&#'.hexdec('$1').';')", $str);
	}


	/**
	 * substituteHiEntities
	 * Converts html_entities > ASCII 127 to unicode
	 * Leaves in particular &lt; to distinguish from tag marker
	 *
	 * @param	string	$html	String to convert
	 */
	public static function substituteHiEntities($html)
	{
		return str_replace(array('&nbsp;','&iexcl;','&cent;','&pound;','&curren;','&yen;',
								 '&brvbar;','&sect;','&uml;','&copy;','&ordf;','&laquo;','&not;',
								 '&shy;','&reg;','&macr;','&deg;','&plusmn;','&sup2;','&sup3;',
								 '&acute;','&micro;','&para;','&middot;','&cedil;','&sup1;',
								 '&ordm;','&raquo;','&frac14;','&frac12;','&frac34;','&iquest;',
								 '&Agrave;','&Aacute;','&Acirc;','&Atilde;','&Auml;','&Aring;',
								 '&AElig;','&Ccedil;','&Egrave;','&Eacute;','&Ecirc;','&Euml;',
								 '&Igrave;','&Iacute;','&Icirc;','&Iuml;','&ETH;','&Ntilde;',
								 '&Ograve;','&Oacute;','&Ocirc;','&Otilde;','&Ouml;','&times;',
								 '&Oslash;','&Ugrave;','&Uacute;','&Ucirc;','&Uuml;','&Yacute;',
								 '&THORN;','&szlig;','&agrave;','&aacute;','&acirc;','&atilde;',
								 '&auml;','&aring;','&aelig;','&ccedil;','&egrave;','&eacute;',
								 '&ecirc;','&euml;','&igrave;','&iacute;','&icirc;','&iuml;',
								 '&eth;','&ntilde;','&ograve;','&oacute;','&ocirc;','&otilde;',
								 '&ouml;','&divide;','&oslash;','&ugrave;','&uacute;','&ucirc;',
								 '&uuml;','&yacute;','&thorn;','&yuml;','&OElig;','&oelig;',
								 '&Scaron;','&scaron;','&Yuml;','&fnof;','&circ;','&tilde;',
								 '&Alpha;','&Beta;','&Gamma;','&Delta;','&Epsilon;','&Zeta;',
								 '&Eta;','&Theta;','&Iota;','&Kappa;','&Lambda;','&Mu;','&Nu;',
								 '&Xi;','&Omicron;','&Pi;','&Rho;','&Sigma;','&Tau;','&Upsilon;',
								 '&Phi;','&Chi;','&Psi;','&Omega;','&alpha;','&beta;','&gamma;',
								 '&delta;','&epsilon;','&zeta;','&eta;','&theta;','&iota;',
								 '&kappa;','&lambda;','&mu;','&nu;','&xi;','&omicron;','&pi;',
								 '&rho;','&sigmaf;','&sigma;','&tau;','&upsilon;','&phi;','&chi;',
								 '&psi;','&omega;','&thetasym;','&upsih;','&piv;','&ensp;','&emsp;',
								 '&thinsp;','&zwnj;','&zwj;','&lrm;','&rlm;','&ndash;','&mdash;',
								 '&lsquo;','&rsquo;','&sbquo;','&ldquo;','&rdquo;','&bdquo;',
								 '&dagger;','&Dagger;','&bull;','&hellip;','&permil;','&prime;',
								 '&Prime;','&lsaquo;','&rsaquo;','&oline;','&frasl;','&euro;',
								 '&image;','&weierp;','&real;','&trade;','&alefsym;','&larr;',
								 '&uarr;','&rarr;','&darr;','&harr;','&crarr;','&lArr;','&uArr;',
								 '&rArr;','&dArr;','&hArr;','&forall;','&part;','&exist;','&empty;',
								 '&nabla;','&isin;','&notin;','&ni;','&prod;','&sum;','&minus;',
								 '&lowast;','&radic;','&prop;','&infin;','&ang;','&and;','&or;',
								 '&cap;','&cup;','&int;','&there4;','&sim;','&cong;','&asymp;',
								 '&ne;','&equiv;','&le;','&ge;','&sub;','&sup;','&nsub;','&sube;',
								 '&supe;','&oplus;','&otimes;','&perp;','&sdot;','&lceil;',
								 '&rceil;','&lfloor;','&rfloor;','&lang;','&rang;','&loz;',
								 '&spades;','&clubs;','&hearts;','&diams;'),
						   array(' ','¡','¢','£','¤','¥','¦','§','¨','©','ª','«','¬','­','®','¯',
								 '°','±','²','³','´','µ','¶','·','¸','¹','º','»','¼','½','¾','¿',
								 'À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï',
								 'Ð','Ñ','Ò','Ó','Ô','Õ','Ö','×','Ø','Ù','Ú','Û','Ü','Ý','Þ','ß',
								 'à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï',
								 'ð','ñ','ò','ó','ô','õ','ö','÷','ø','ù','ú','û','ü','ý','þ','ÿ',
								 'Œ','œ','Š','š','Ÿ','ƒ','ˆ','˜','Α','Β','Γ','Δ','Ε','Ζ','Η','Θ',
								 'Ι','Κ','Λ','Μ','Ν','Ξ','Ο','Π','Ρ','Σ','Τ','Υ','Φ','Χ','Ψ','Ω',
								 'α','β','γ','δ','ε','ζ','η','θ','ι','κ','λ','μ','ν','ξ','ο','π',
								 'ρ','ς','σ','τ','υ','φ','χ','ψ','ω','ϑ','ϒ','ϖ',' ',' ',' ','‌',
								 '‍','‎','‏','–','—','‘','’','‚','“','”','„','†','‡','•','…','‰',
								 '′','″','‹','›','‾','⁄','€','ℑ','℘','ℜ','™','ℵ','←','↑','→','↓',
								 '↔','↵','⇐','⇑','⇒','⇓','⇔','∀','∂','∃','∅','∇','∈','∉','∋','∏',
								 '∑','−','∗','√','∝','∞','∠','∧','∨','∩','∪','∫','∴','∼','≅','≈',
								 '≠','≡','≤','≥','⊂','⊃','⊄','⊆','⊇','⊕','⊗','⊥','⋅','⌈','⌉','⌊',
								 '⌋','〈','〉','◊','♠','♣','♥','♦'), $html);
	}
	
	/**
	 * all_entities_to_utf8
	 * Converts txt_entities > ASCII 127 to UTF-8
	 * Leaves in particular &lt; to distinguish from tag marker
	 */
	public static function all_entities_to_utf8(&$txt)
	{
		$txt = self::substituteHiEntities($txt);
		$txt = self::lesser_entity_decode($txt);
	}
	
}

class Numeric
{
	/**
	 * convertSize
	 *
	 * Depends of maxsize value to make % work properly. Usually maxsize == pagewidth
	 * For text $maxsize = Fontsize
	 * Setting e.g. margin % will use maxsize (pagewidth) and em will use fontsize
	 *
	 * @param  bool  $usefontsize   Set to false for e.g. margins - will ignore fontsize for % values
	 */
	static function convertSize($size=5, $dpi=96, $maxsize=0, $fontsize=false, $usefontsize=true)
	{
		//Identify size (remember: we are using 'mm' units here)
		$size = strtolower($size);
		if ($size == 'thin') {
			//1 pixel width for table borders
			return 25.4 / $dpi; // mPDF 4.4.003
		} else if ($size == 'medium') {
			//3 pixel width for table borders
			return 3 * 25.4 / $dpi; // mPDF 4.4.003
		} else if ($size == 'thick') {
			//5 pixel width for table borders
			return 5 * 25.4 / $dpi; // mPDF 4.4.003
		} else if (strstr($size, 'px')) {
			//pixels
			return $size * 25.4 / $dpi; // mPDF 4.4.003
		} else if (strstr($size, 'cm')) {
			//centimeters
			return $size * 10;
		} else if (strstr($size, 'mm')) {
			//millimeters
			return $size + 0;
		} else if (strstr($size, 'in')) {
			//inches
			return $size * 25.4;
		} else if (strstr($size, 'pc')) {
			//PostScript picas
			return $size * 38.1 / 9;
		} else if (strstr($size, 'pt')) {
			//72 pts/inch
			return $size * 25.4 / 72;
		} else if (strstr($size, 'ex')) {
			// mPDF 4.4.003  Approximates "ex" as half of font height
			return $size * 0.5 * (($fontsize)? $fontsize : $maxsize);
		} else if (strstr($size, 'em')) {
			return $size * (($fontsize)? $fontsize : $maxsize);
		} else if (strstr($size, '%')) {
			return $size / 100 * (($fontsize && $usefontsize)? $fontsize : $maxsize);
		} else if ($size == 'xx-small') {
			return $size * 0.7 * (($fontsize)? $fontsize : $maxsize);
		} else if ($size == 'x-small') {
			return $size * 0.77 * (($fontsize)? $fontsize : $maxsize);
		} else if ($size == 'small') {
			return $size * 0.86 * (($fontsize)? $fontsize : $maxsize);
		} else if ($size == 'medium') {
			return $size * (($fontsize)? $fontsize : $maxsize);
		} else if ($size == 'large') {
			return $size * 1.2 * (($fontsize)? $fontsize : $maxsize);
		} else if ($size == 'x-large') {
			return $size * 1.5 * (($fontsize)? $fontsize : $maxsize);
		} else if ($size == 'xx-large') {
			return $size * 2 * (($fontsize)? $fontsize : $maxsize);
		}
		//nothing == px // mPDF 4.4.003
		return $size * 25.4 / $dpi;
	}
	
	/**
	 * toAlpha
	 */
	static function toAlpha($val, $toupper = true)
	{
		if ($val < 1 || $val > 18278) {
			return '?'; //supports 'only' up to 18278
		}
		$anum = '';
		while ($val >= 1) {
			$val = $val - 1;
			$anum = chr(($val % 26) + 65) . $anum;
			$val = $val / 26;
		}
		return $toupper ? $anum : strtolower($anum);
	}


	/**
	 * toRoman
	 */
	static function toRoman($val, $toupper = true)
	{
		if ($val < 1  || $val > 4999) {
			return '?'; //supports 'only' up to 4999
		}
		
		$val = (int)$val;
		$result = '';

		$lookup = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400,
						'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40,
						'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);

		foreach ($lookup as $roman => $value) {
			$matches = (int)($val / $value);
			$result .= str_repeat($roman, $matches);
			$val = $val % $value;
		}
 
		return $toupper ? $result : strtolower($result);
	}

	
	/**
	 * pageFormat
	 * @since: mPDF 4.2.024
	 */
	static function pageFormat($format)
	{
		switch (strtoupper($format)) {
			case '4A0':
				return array(4767.87, 6740.79);
			case '2A0':
				return array(3370.39, 4767.87);
			case 'A0':
				return array(2383.94, 3370.39);
			case 'A1':
				return array(1683.78, 2383.94);
			case 'A2':
				return array(1190.55, 1683.78);
			case 'A3':
				return array(841.89, 1190.55);
			case 'A4':
			default:
				return array(595.28, 841.89);
			case 'A5':
				return array(419.53, 595.28);
			case 'A6':
				return array(297.64, 419.53);
			case 'A7':
				return array(209.76, 297.64);
			case 'A8':
				return array(147.40, 209.76);
			case 'A9':
				return array(104.88, 147.40);
			case 'A10':
				return array(73.70, 104.88);
			case 'B0':
				return array(2834.65, 4008.19);
			case 'B1':
				return array(2004.09, 2834.65);
			case 'B2':
				return array(1417.32, 2004.09);
			case 'B3':
				return array(1000.63, 1417.32);
			case 'B4':
				return array(708.66, 1000.63);
			case 'B5':
				return array(498.90, 708.66);
			case 'B6':
				return array(354.33, 498.90);
			case 'B7':
				return array(249.45, 354.33);
			case 'B8':
				return array(175.75, 249.45);
			case 'B9':
				return array(124.72, 175.75);
			case 'B10':
				return array(87.87, 124.72);
			case 'C0':
				return array(2599.37, 3676.54);
			case 'C1':
				return array(1836.85, 2599.37);
			case 'C2':
				return array(1298.27, 1836.85);
			case 'C3':
				return array(918.43, 1298.27);
			case 'C4':
				return array(649.13, 918.43);
			case 'C5':
				return array(459.21, 649.13);
			case 'C6':
				return array(323.15, 459.21);
			case 'C7':
				return array(229.61, 323.15);
			case 'C8':
				return array(161.57, 229.61);
			case 'C9':
				return array(113.39, 161.57);
			case 'C10':
				return array(79.37, 113.39);
			case 'RA0':
				return array(2437.80, 3458.27);
			case 'RA1':
				return array(1729.13, 2437.80);
			case 'RA2':
				return array(1218.90, 1729.13);
			case 'RA3':
				return array(864.57, 1218.90);
			case 'RA4':
				return array(609.45, 864.57);
			case 'SRA0':
				return array(2551.18, 3628.35);
			case 'SRA1':
				return array(1814.17, 2551.18);
			case 'SRA2':
				return array(1275.59, 1814.17);
			case 'SRA3':
				return array(907.09, 1275.59);
			case 'SRA4':
				return array(637.80, 907.09);
			case 'LETTER':
				return array(612.00, 792.00);
			case 'LEGAL':
				return array(612.00, 1008.00);
			case 'EXECUTIVE':
				return array(521.86, 756.00);
			case 'FOLIO':
				return array(612.00, 936.00);
			case 'B':
				return array(362.83, 561.26); // 'B' format paperback size 128x198mm
			case 'A':
				return array(314.65, 504.57); // 'A' format paperback size 111x178mm
			case 'DEMY':
				return array(382.68, 612.28); // 'Demy' format paperback size 135x216mm
			case 'ROYAL':
				return array(433.70, 663.30); // 'Royal' format paperback size 153x234mm
		}
		return false; // will probably never be run
	}
}
