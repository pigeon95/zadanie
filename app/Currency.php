<?php

namespace App;

class Currency {

    const CURRENCIES = array(
        'ETH' => 'ETH',
        'BTC' => 'BTC',
        'LTC' => 'LTC',
        'ZEC' => 'ZEC',
        'STRAT' => 'STRAT',
        'DASH' => 'DASH',
        'XMR' => 'XMR',
        'SC' => 'SC',
        'XEM' => 'XEM',
        'GNT' => 'GNT',
        'REP' => 'REP',
        'XRP' => 'XRP',
        'BCH' => 'BCH',
        'PLN' => 'PLN',
        'USD' => 'USD',
        'EUR' => 'EUR',
        'BTG' => 'BTG',
        'LSK' => 'LSK',
        'HSR' => 'HSR',
        'QTUM' => 'QTUM',
        'ADA' => 'ADA',
        'TRX' => 'TRX',
        'ARK' => 'ARK',
        'EOS' => 'EOS');

    public static function getArray() {
        return self::CURRENCIES;
    }

}
