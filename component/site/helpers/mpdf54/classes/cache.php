<?php

require('rb.php');
R::setup('sqlite:'._MPDF_PATH.'pdfcache.sq3');

class Cache {

	public static function saveInlineProperties(&$pdf) {
		$props = R::dispense('inline');
		
		$props->family = $pdf->FontFamily;
		$props->style = $pdf->FontStyle;
		$props->sizept = $pdf->FontSizePt;
		$props->size = $pdf->FontSize;
		$props->href = $pdf->HREF; 
		$props->underline = $pdf->U; 
		$props->smcaps = $pdf->S;
		$props->strike = $pdf->strike;
		$props->textshadow = serialize($pdf->textshadow);	// mPDF 5.3.A2
		$props->sup = $pdf->SUP; 
		$props->sub = $pdf->SUB; 
		$props->linewidth = $pdf->LineWidth;
		$props->drawcolor = $pdf->DrawColor;
		$props->is_outline = $pdf->outline_on;
		$props->outlineparam = serialize($pdf->outlineparam);
		$props->toupper = $pdf->toupper;
		$props->tolower = $pdf->tolower;
		$props->capitalize = $pdf->capitalize;
		$props->fontkerning = $pdf->kerning;
		$props->lspacingcss = $pdf->lSpacingCSS;
		$props->wspacingcss = $pdf->wSpacingCSS;
		$props->i = $pdf->I;
		$props->b = $pdf->B;
		$props->colorarray = $pdf->colorarray;
		$props->bgcolorarray = $pdf->spanbgcolorarray;
		$props->border = serialize($pdf->spanborddet);	// mPDF 5.3.61
		$props->color = $pdf->TextColor; 
		$props->bgcolor = $pdf->FillColor;
		$props->lang = $pdf->currentLang;
		$props->display_off = $pdf->inlineDisplayOff;
		$id = R::store($props);
		return $id;
	}
	
	public static function restoreInlineProperties(&$pdf, $saved) {
		$props = R::load('inline', $saved);
		
		$FontFamily = $props->family;
		$pdf->FontStyle = $props->style;
		$pdf->FontSizePt = $props->sizept;
		$pdf->FontSize = $props->size;
	
		$pdf->currentLang = $props->lang;
		if ($pdf->useLang && !$pdf->usingCoreFont) {
		  if ($pdf->currentLang != $pdf->default_lang && ((strlen($pdf->currentLang) == 5 && $pdf->currentLang != 'UTF-8') || strlen($pdf->currentLang ) == 2)) { 
			list ($coreSuitable,$mpdf_pdf_unifonts) = GetLangOpts($pdf->currentLang, $pdf->useAdobeCJK);
			if ($mpdf_pdf_unifonts) { $pdf->RestrictUnicodeFonts($mpdf_pdf_unifonts); }
			else { $pdf->RestrictUnicodeFonts($pdf->default_available_fonts ); }
		  }
		  else { 
			$pdf->RestrictUnicodeFonts($pdf->default_available_fonts );
		  } 
		}
	
		$pdf->ColorFlag = ($pdf->FillColor != $pdf->TextColor); //Restore ColorFlag as well
	
		$pdf->HREF = $props->href;
		$pdf->U = $props->underline;
		$pdf->S = $props->smcaps;
		$pdf->strike = $props->strike;
		$pdf->textshadow = unserialize($props->textshadow);	// mPDF 5.3.A2
		$pdf->SUP = $props->sup;
		$pdf->SUB = $props->sub;
		$pdf->LineWidth = $props->linewidth;
		$pdf->DrawColor = $props->drawcolor;
		$pdf->outline_on = $props->is_outline;
		$pdf->outlineparam = unserialize($props->outlineparam);
		$pdf->inlineDisplayOff = $props->display_off;
	
		$pdf->toupper = $props->toupper;
		$pdf->tolower = $props->tolower;
		$pdf->capitalize = $props->capitalize;
		$pdf->kerning = $props->fontkerning;
		$pdf->lSpacingCSS = $props->lspacingcss;
		if (($pdf->lSpacingCSS || $pdf->lSpacingCSS==='0') && strtoupper($pdf->lSpacingCSS) != 'NORMAL') {
			$pdf->fixedlSpacing = $pdf->ConvertSize($pdf->lSpacingCSS,$pdf->FontSize);
		}
		else { $pdf->fixedlSpacing = false; }
		$pdf->wSpacingCSS = $props->wspacingcss;
		if ($pdf->wSpacingCSS && strtoupper($pdf->wSpacingCSS) != 'NORMAL') { 
			$pdf->minwSpacing = $pdf->ConvertSize($pdf->wSpacingCSS,$pdf->FontSize);
		}
		else { $pdf->minwSpacing = 0; }
	  
		$pdf->SetFont($FontFamily, $props->style.($pdf->U ? 'U' : '').($pdf->S ? 'S' : ''),$props->sizept,false);
	
		$pdf->currentfontstyle = $props->style.($pdf->U ? 'U' : '').($pdf->S ? 'S' : '');
		$pdf->currentfontsize = $props->sizept;
		$pdf->SetStylesArray(array('S'=>$pdf->S, 'U'=>$pdf->U, 'B'=>$props->b, 'I'=>$props->i));
	
		$pdf->TextColor = $props->color;
		$pdf->FillColor = $props->bgcolor;
		$pdf->colorarray = $props->colorarray;
		$cor = $pdf->colorarray;
		if ($cor) $pdf->SetTColor($cor);
		$pdf->spanbgcolorarray = $props->bgcolorarray;
		$cor = $pdf->spanbgcolorarray;
		if ($cor) $pdf->SetFColor($cor);
		$pdf->spanborddet = unserialize($props->border);	// mPDF 5.3.61
		
	}
	
	
	public static function clear() {
		R::wipe('inline');
	}

}

?>