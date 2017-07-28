<?php

/**
 * Helper class which manages response codes and its meanings.
 *
 * @package GiroCheckout
 * @version $Revision: 184 $ / $Date: 2017-01-18 10:24:16 -0300 (Wed, 18 Jan 2017) $
 */
class GiroCheckout_SDK_ResponseCode_helper {
  /*
   * contains the response codes and messages in different languages
   */

  private static $code = array( 'DE' => array(
      0 => 'OK',
      4000 => 'Transaktion erfolgreich',
      4001 => 'giropay Bank offline',
      4002 => 'Online-Banking-Zugang ungültig',
      4020 => 'Altersverifikation erfolgreich',
      4021 => 'Altersverifikation nicht durchführbar',
      4022 => 'Altersverifikation nicht erfolgreich',
      4051 => 'Kontoverbindung ungültig',
      4101 => 'Ausgabeland der Karte ungültig oder unbekannt',
      4102 => '3-D Secure oder MasterCard SecureCode Autorisierung fehlgeschlagen',
      4103 => 'Gültigkeitsdatum der Karte überschritten',
      4104 => 'Kartentyp ungültig oder unbekannt',
      4105 => 'Karte eingeschränkt nutzbar',
      4106 => 'Pseudo-Kartennummer ungültig',
      4107 => 'Karte gestohlen, verdächtig oder zum Einziehen markiert',
      4151 => 'PayPal-Token ungültig',
      4152 => 'Nachbearbeitung bei PayPal notwendig',
      4153 => 'Zahlungsmethode bei PayPal ändern',
      4154 => 'PayPal-Zahlung nicht abgeschlossen',
      4500 => 'Zahlungsausgang unbekannt',
      4501 => 'Timeout / keine Benutzereingabe',
      4502 => 'Abbruch durch Benutzer',
      4503 => 'Doppelte Transaktion',
      4504 => 'Manipulationsverdacht oder Zahlungsmittel temporär gesperrt',
      4505 => 'Zahlungsmittel gesperrt oder abgelehnt',
      4506 => 'Blue Code Barcode ungültig',
      4900 => 'Transaktion nicht erfolgreich',
      5000 => 'Authentifizierung fehlgeschlagen',
      5001 => 'Keine Berechtigung',
      5002 => 'Hash ungültig',
      5003 => 'Pflichtfeld nicht angegeben',
      5004 => 'Aufruf ungültig',
      5009 => 'E-Mail ungültig',
      5010 => 'Sprache ungültig',
      5011 => 'Land ungültig',
      5012 => 'Branche ungültig',
      5013 => 'Shopsystem ungültig',
      5014 => 'Geschlecht ungültig',
      5015 => 'Produkt ungültig',
      5016 => 'Organisationstyp ungültig',
      5017 => 'Händler existiert bereits',
      5018 => 'PSP ungültig',
      5019 => 'Kreditkartentyp ungültig',
      5020 => 'Händler-ID ungültig',
      5021 => 'Projekt-ID ungültig',
      5022 => 'Händler-Transaktions-ID ungültig',
      5023 => 'Verwendungszweck ungültig',
      5024 => 'BLZ ungültig',
      5025 => 'Bankkonto ungültig',
      5026 => 'BIC ungültig',
      5027 => 'IBAN ungültig',
      5028 => 'mobile ungültig',
      5029 => 'PKN ungültig',
      5030 => 'Betrag ungültig',
      5031 => 'Bankleitzahl oder BIC nicht angegeben',
      5032 => 'Lastschrift Sequenztyp ungültig',
      5033 => 'Währung ungültig',
      5034 => 'Transaktion nicht vorhanden',
      5040 => 'info1Label ungültig',
      5041 => 'info1Text ungültig',
      5042 => 'info2Label ungültig',
      5043 => 'info2Text ungültig',
      5044 => 'info3Label ungültig',
      5045 => 'info3Text ungültig',
      5046 => 'info4Label ungültig',
      5047 => 'info4Text ungültig',
      5048 => 'info5Label ungültig',
      5049 => 'info5Text ungültig',
      5050 => 'recurring ungültig',
      5051 => 'Mandatsreferenz ungültig',
      5052 => 'mandateSignedOn ungültig',
      5053 => 'mandateReceiverName ungültig',
      5054 => 'issuer ungültig',
      5055 => 'urlRedirect ungültig',
      5056 => 'urlNotify ungültig',
      5060 => 'Betrag oder Währung nicht angegeben',
      5061 => 'purposetext ungültig',
      5062 => 'paymentreference ungültig',
      5063 => 'format ungültig',
      5064 => 'resolution ungültig',
      5065 => 'Fehler beim Erstellen der Grafik',
      5066 => 'purpose und paymentreference angegeben',
      5067 => 'Empfänger-IBAN ungültig',
      5068 => 'Empfänger-BIC ungültig',
      5069 => 'purposecode ungültig',
      5070 => 'Empfängername ungültig',
      5071 => 'Empfängername, Epmfänger-IBAN oder Empfänger-BIC nicht angeben ',
      5072 => 'customerLastName ungültig',
      5073 => 'customerStreet ungültig',
      5074 => 'customerStreetNumber ungültig',
      5075 => 'customerZipCode ungültig',
      5076 => 'customerCity ungültig',
      5077 => 'customerCountry ungültig',
      5078 => 'customerBirthDate ungültig',
      5079 => 'customerGender ungültig',
      5080 => 'customerEmail ungültig',
      5081 => 'customerIp ungültig',
      5082 => 'customerId ungültig',
      5083 => 'shopId ungültig',
      5084 => 'Kundenvorname ungültig',
      5085 => 'Kontoinhaber ungültig',
      5100 => 'Fehler beim Zahlungsabwickler',
      5101 => 'Verbindungsproblem zum Zahlungsabwickler',
      5102 => 'Pseudo-Kartennummer nicht vorhanden',
      5200 => 'Transaktion nicht akzeptiert',
      5201 => 'giropay Bank offline',
      5202 => 'giropay Bank des Absenders ungültig',
      5203 => 'Bankverbindung des Absenders auf Blacklist',
      5204 => 'Bankverbindung des Absenders ungültig',
      6000 => 'Bankleitzahl oder BIC fehlt',
      6001 => 'Bank unbekannt',
      6002 => 'Bank unterstützt kein giropay',
      9999 => 'interner Fehler',
      // old codes
      1900 => 'Transaktion nicht akzeptiert',
      1910 => 'giropay Bank offline',
      1920 => 'Bankverbindung des Absenders ungültig',
      1930 => 'Bankverbindung des Absenders auf Blacklist',
      1940 => 'Bankverbindung des Absenders ungültig',
      2000 => 'Timeout / keine Benutzereingabe',
      2400 => 'Online Banking-Zugang ungültig',
      3100 => 'Abbruch durch Benutzer',
      3900 => 'giropay Bank offline',
    ),
    
    'EN' => array(
      0 => 'OK',
      4000 => 'transaction successful',
      4001 => 'giropay bank offline',
      4002 => 'online banking account invalid',
      4020 => 'age verification successful',
      4021 => 'age verification not possible',
      4022 => 'age verification unsuccessful',
      4051 => 'invalid bank account',
      4101 => 'issuing country invalid or unknown',
      4102 => '3-D Secure or MasterCard SecureCode authorization failed',
      4103 => 'validation date of card exceeded',
      4104 => 'invalid or unknown card type',
      4105 => 'limited-use card',
      4106 => 'invalid pseudo-cardnumber',
      4107 => 'card stolen, suspicious or marked to move in',
      4151 => 'invalid PayPal token',
      4152 => 'post-processing necessary at PayPal',
      4153 => 'change PayPal payment method',
      4154 => 'PayPal-payment is not completed',
      4500 => 'payment result unknown',
      4501 => 'timeout / no user input',
      4502 => 'user aborted',
      4503 => 'duplicate transaction',
      4504 => 'suspicion of manipulation or payment method temporarily blocked',
      4505 => 'payment method blocked or rejected',
      4506 => 'invalid Blue Code barcode',
      4900 => 'transaction rejected',
      5000 => 'authentication failed',
      5001 => 'no authorization',
      5002 => 'invalid hash',
      5003 => 'mandatory field not specified',
      5004 => 'invalid call',
      5009 => 'invalid mail',
      5010 => 'invalid language',
      5011 => 'invalid country',
      5012 => 'invalid branch',
      5013 => 'invalid shop system',
      5014 => 'invalid gender',
      5015 => 'invalid product',
      5016 => 'invalid organisation type',
      5017 => 'merchant already exist',
      5018 => 'invalid PSP',
      5019 => 'invalid credit card type',
      5020 => 'invalid merchantId',
      5021 => 'invalid projectId',
      5022 => 'invalid merchantTxId',
      5023 => 'invalid purpose',
      5024 => 'invalid bankcode',
      5025 => 'invalid bankaccount',
      5026 => 'invalid bic',
      5027 => 'invalid iban',
      5028 => 'invalid mobile',
      5029 => 'invalid pkn',
      5030 => 'invalid amount',
      5031 => 'bankcode or BIC missing',
      5032 => 'invalid mandateSequence',
      5033 => 'invalid currency',
      5034 => 'transaction does not exist',
      5040 => 'invalid info1Label',
      5041 => 'invalid info1Text',
      5042 => 'invalid info2Label',
      5043 => 'invalid info2Text',
      5044 => 'invalid info3Label',
      5045 => 'invalid info3Text',
      5046 => 'invalid info4Label',
      5047 => 'invalid info4Text',
      5048 => 'invalid info5Label',
      5049 => 'invalid info5Text',
      5050 => 'invalid recurring',
      5051 => 'invalid mandateReference',
      5052 => 'invalid mandateSignedOn',
      5053 => 'invalid mandateReceiverName',
      5054 => 'invalid issuer',
      5055 => 'invalid urlRedirect',
      5056 => 'invalid urlNotify',
      5060 => 'amount or currency not missing',
      5061 => 'invalid purposetext',
      5062 => 'invalid paymentreference',
      5063 => 'invalid format',
      5064 => 'invalid resolution',
      5065 => 'error by creating image',
      5066 => 'purpose and paymentreference given',
      5067 => 'invalid receiveriban',
      5068 => 'invalid receiverbic',
      5069 => 'invalid purposecode',
      5070 => 'invalid receivername',
      5071 => 'receivername, receiveriban or receiverbic not given',
      5072 => 'invalid customerLastName',
      5073 => 'invalid customerStreet',
      5074 => 'invalid customerStreetNumber',
      5075 => 'invalid customerZipCode',
      5076 => 'invalid customerCity',
      5077 => 'invalid customerCountry',
      5078 => 'invalid customerBirthDate',
      5079 => 'invalid customerGender',
      5080 => 'invalid customerEmail',
      5081 => 'invalid customerIp',
      5082 => 'invalid customerId',
      5083 => 'invalid shopId',
      5084 => 'invalid customer first name',
      5085 => 'invalid account holder',
      5100 => 'error from payment processor',
      5101 => 'connection problem to payment processor',
      5102 => 'pseudo-cardnumber does not exist',
      5200 => 'not accepted transaction',
      5201 => 'giropay bank offline',
      5202 => 'invalid giropay bank of sender',
      5203 => 'sender bank account blacklisted',
      5204 => 'invalid sender bank account',
      6000 => 'bankcode or BIC missing',
      6001 => 'bank unknown',
      6002 => 'bank does not support giropay',
      9999 => 'internal error',
      // old codes
      1900 => 'not accepted transaction',
      1910 => 'giropay bank offline',
      1920 => 'invalid sender bank account',
      1930 => 'sender bank account blacklisted',
      1940 => 'invalid sender bank account',
      2000 => 'timeout / no user input',
      2400 => 'online banking account invalid',
      3100 => 'user aborted',
      3900 => 'giropay bank offline',
    ),
  );

  /*
   * returns the message string of an given code in the given language
   * @param integer code
   * @param String language
   * @return null/String message
   */

  public static function getMessage( $code, $lang = 'EN' ) {
    if( $code < 0 ) {
      return null;
    } //code invalid
    $lang = strtoupper( $lang );

    if( !array_key_exists( $lang, self::$code ) ) { //language not found
      $lang = 'EN';
    }

    if( array_key_exists( $code, self::$code[$lang] ) ) { //code not defined
      return self::$code[$lang][$code];
    }

    return null;
  }

}
