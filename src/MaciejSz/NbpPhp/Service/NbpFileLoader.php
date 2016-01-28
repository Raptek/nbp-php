<?php
namespace MaciejSz\NbpPhp\Service;
 
use MaciejSz\NbpPhp\NbpRateTuple;

class NbpFileLoader
{
    /**
     * @param string $url
     * @return NbpRateTuple[]
     */
    public function load($url)
    {
        $txt = file_get_contents($url);
        $txt = iconv('ISO-8859-2', 'UTF-8//TRANSLIT', $txt);
        $txt = str_replace('ISO-8859-2', 'UTF-8', $txt);
        $Xml = new \SimpleXMLElement($txt);
        /** @var NbpRateTuple[] $rates */
        $rates = [];
        foreach ( $Xml->pozycja as $SxPos ) {
            $Item = NbpRateTuple::fromNbpXml($SxPos, $Xml);
            $rates[$Item->currency_code] = $Item;
        }

        $Item = NbpRateTuple::factory('pln', 'PLN', 1.0, $Xml);
        $rates[$Item->currency_code] = $Item;

        return $rates;
    }
}
 
