<?php
/**
 * Diese Klasse enthŠlt Hilfsfunktionen fŸr die Giropay Schnittstelle
 *
 * @version 1.2
 * @author Thorsten Marx <thorsten.marx@girosolution.de>
 * @copyright 2011 GiroSolution AG
 */
class gsGiropay {
  /**
   * Fehlercodes, wenn die Transaktion OK war
   *
   * @var array
   */
  public  $CodesOK = array( '4000' );
  /**
   * Fehlercodes, wenn die Transaktion mit einem unbekannten Fehler beendet wurde
   *
   * @var array
   */
  public  $CodesUnbekannt = array( '4500' );
  /**
   * Fehlercodes, wenn die Transaktion mit einem Fehler beendet wurde
   *
   * @var array
   */
  public  $CodesFehler = array( '1900', '1910', '3100', '4900' );

  /**
   * Generiert einen MD5 HMAC Hash Ÿber den Ÿbergebenen String
   * anhand des Keys.
   * Hinweis: Die verwendete PHP Funktion hash_hmac ist erst ab
   *          PHP Version 5.1.2 verfŸgbar
   *          FŸr frŸhere PHP Versionen ist diese FunktionalitŠt
   *          als PHP Code implementiert
   *
   * @param string $data Daten Ÿber den der Hash generiert werden soll
   * @param string $key Passphrase fŸr den Hash
   * @return string generierter Hash-Code
   */
   function generateHash( $data, $key ) {
  	// hash_hmac Funktion ist erst seit PHP Version 5.1.2 verfŸgbar
  	if( function_exists( 'hash_hmac' ) ) {
  	  return hash_hmac( 'md5', $data, $key );
  	}

  	// Implementierung fŸr PHP4
    $b = 64; // byte length for md5
    if( strlen( $key ) > $b )
      $key = pack( "H*", md5( $key ) );
    $key = str_pad( $key, $b, chr(0x00) );
    $ipad = str_pad( '', $b, chr(0x36) );
    $opad = str_pad( '', $b, chr(0x5c) );
    $k_ipad = $key ^ $ipad ;
    $k_opad = $key ^ $opad;
    return md5( $k_opad . pack( "H*", md5( $k_ipad . $data ) ) );
  }

  /**
   * Liefert zu einem Giropay Fehlercode (gpCode) die
   * deutsche Fehlerbeschreibung.
   *
   * @param integer $gpCode Fehlercode von Giropay
   * @return string Fehlerbeschreibung
   */
   function getCodeDescription( $gpCode ) {
    switch( $gpCode ) {
      case 1900:
        return 'Fehler bei Transaktionsstart. Die Ÿbergebenen Daten sind unbrauchbar.';
        break;
      case 1910:
        return 'Fehler/Abbruch bei Transaktionsstart. BLZ ungŸltig oder BLZ-Suche abgebrochen.';
        break;
      case 3100:
        return 'Benutzerseitiger Abbruch bei der Bezahlung.';
        break;
      case 4000:
        return 'Bezahlung erfolgreich.';
        break;
      case 4500:
        return 'Unbekanntes Transaktionsende. Zahlungseingang muss anhand der KontoumsŠtze ŸberprŸft werden.';
        break;
      case 4900:
        return 'Bezahlung nicht erfolgreich.';
        break;
      default:
        return 'Unbekannter Fehler';
    }
  }

  /**
   * Liefert einen Text, der dem Benutzer nach der Bezahlung Ÿber Giropay angezeigt wird
   *
   * @param integer $gpCode Fehler Code
   * @return string Meldung fŸr den Benutzer
   */
   function getCodeText( $gpCode ) {
    switch( $gpCode ) {
      case 1900:
        return 'Fehler bei der Bezahlung Ÿber Giropay. Wir haben soeben eine E-Mail an Sie geschickt. Bitte Ÿberweisen Sie den Betrag auf das in der E-Mail angegebene Konto. Nach Geldeingang werden wir die Ware verschicken.';
        break;
      case 1910:
        return 'Fehler bei der Bezahlung Ÿber Giropay. Wir haben soeben eine E-Mail an Sie geschickt. Bitte Ÿberweisen Sie den Betrag auf das in der E-Mail angegebene Konto. Nach Geldeingang werden wir die Ware verschicken.';
        break;
      case 3100:
        return 'Sie haben die Bezahlung Ÿber Giropay abgebrochen. Wir haben soeben eine E-Mail an Sie geschickt. Bitte Ÿberweisen Sie den Betrag auf das in der E-Mail angegebene Konto. Nach Geldeingang werden wir die Ware verschicken.';
        break;
      case 4000:
        return 'Vielen Dank fŸr die Bezahlung Ÿber Giropay.';
        break;
      case 4500:
        return 'Es ist ein unbekannter Fehler bei der Bezahlung Ÿber Giropay aufgetreten. Bitte ŸberprŸfen Sie Ihre KontoumsŠtze und wenden Sie sich bei Fragen an uns.';
        break;
      case 4900:
        return 'Fehler bei der Bezahlung Ÿber Giropay. Wir haben soeben eine E-Mail an Sie geschickt. Bitte Ÿberweisen Sie den Betrag auf das in der E-Mail angegebene Konto. Nach Geldeingang werden wir die Ware verschicken.';
    }
    return 'Es ist ein unbekannter Fehler bei der Bezahlung Ÿber Giropay aufgetreten. Bitte ŸberprŸfen Sie Ihre KontoumsŠtze und wenden Sie sich bei Fragen an uns.';
  }

  /**
   * Liefert, ob der angegebene Code OK bedeutet
   *
   * @param integer $gpCode Error Code
   * @return boolean
   */
   function codeIsOK( $gpCode ) {
  	if( in_array( $gpCode, $this->CodesOK ) )
  	  return true;

  	return false;
  }

  /**
   * Liefert, ob der angegebene Code ein Unbekannter ausgang ist
   *
   * @param integer $gpCode Error Code
   * @return boolean
   */
   function codeIsUnbekannt( $gpCode ) {
    if( in_array( $gpCode, $this->CodesUnbekannt ) )
      return true;

    return false;
  }

  /**
   * Liefert, ob der angegebene Code ein Fehler ist
   *
   * @param integer $gpCode Error Code
   * @return boolean
   */
   function codeIsFehler( $gpCode ) {
    if( !in_array( $gpCode, $this->CodesOK ) && !in_array( $gpCode, $this->CodesUnbekannt ) )
      return true;

    return false;
  }
}
