<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 04.04.2018
 * Time: 23:04
 */

namespace App\Service;


class ISO4217 {
    public static $cryptosCodes = [
        'BTC' => ['Bitcoin'],
        'ETH' => ['Ethereum'],
        'XRP' => ['Ripple'],
        'BCH' => ['Bitcoin Cash'],
        'LTC' => ['Litecoin'],
        'EOS' => ['EOS'],
        'ADA' => ['Cardano'],
        'XLM' => ['Stellar'],
        'NEO' => ['NEO'],
        'MIOTA' => ['IOTA'],
        'XMR' => ['Monero'],
        'DASH' => ['Dash'],
        'USDT' => ['Tether'],
        'XEM' => ['NEM'],
        'TRX' => ['TRON'],
        'BNB' => ['Binance Coin'],
        'ETC' => ['Ethereum Classic'],
        'VEN' => ['VeChain'],
        'QTUM' => ['Qtum'],
        'XVG' => ['Verge'],
        'LSK' => ['Lisk'],
        'OMG' => ['OmiseGO'],
        'ICX' => ['ICON'],
        'BTG' => ['Bitcoin Gold'],
        'NANO' => ['Nano'],
        'ZEC' => ['Zcash'],
        'ONT' => ['Ontology'],
        'STEEM' => ['Steem'],
        'BTM' => ['Bytom'],
        'PPT' => ['Populous'],
        'DGD' => ['DigixDAO'],
        'BCN' => ['Bytecoin'],
        'WAVES' => ['Waves'],
        'BTS' => ['BitShares'],
        'SC' => ['Siacoin'],
        'STRAT' => ['Stratis'],
        'BCD' => ['Bitcoin Diamond'],
        'AE' => ['Aeternity'],
        'SNT' => ['Status'],
        'RHOC' => ['RChain'],
        'MKR' => ['Maker'],
        'DOGE' => ['Dogecoin'],
        'DCR' => ['Decred'],
        'ZIL' => ['Zilliqa'],
        'ARDR' => ['Ardor'],
        'ZRX' => ['0x'],
        'REP' => ['Augur'],
        'KMD' => ['Komodo'],
        'IOST' => ['IOStoken'],
        'WTC' => ['Waltonchain'],
        'AION' => ['Aion'],
        'HSR' => ['Hshare'],
        'PIVX' => ['PIVX'],
        'CNX' => ['Cryptonex'],
        'VERI' => ['Veritaseum'],
        'ARK' => ['Ark'],
        'LRC' => ['Loopring'],
        'KCS' => ['KuCoin Shares'],
        'QASH' => ['QASH'],
        'DRGN' => ['Dragonchain'],
        'BAT' => ['Basic Attenti...'],
        'MONA' => ['MonaCoin'],
        'DGB' => ['DigiByte'],
        'XZC' => ['ZCoin'],
        'FCT' => ['Factom'],
        'GNT' => ['Golem'],
        'NAS' => ['Nebulas'],
        'GXS' => ['GXChain'],
        'GAS' => ['Gas'],
        'ETHOS' => ['Ethos'],
        'SYS' => ['Syscoin'],
        'R' => ['Revain'],
        'FUN' => ['FunFair'],
        'ETN' => ['Electroneum'],
        'STORM' => ['Storm'],
        'ELF' => ['aelf'],
        'KNC' => ['Kyber Network'],
        'SUB' => ['Substratum'],
        'RDD' => ['ReddCoin'],
        'KIN' => ['Kin'],
        'DENT' => ['Dent'],
        'NXT' => ['Nxt'],
        'POWR' => ['Power Ledger'],
        'MAID' => ['MaidSafeCoin'],
        'SALT' => ['SALT'],
        'GBYTE' => ['Byteball Bytes'],
        'STORJ' => ['Storj'],
        'NCASH' => ['Nucleus Vision'],
        'ENG' => ['Enigma'],
        'SKY' => ['Skycoin'],
        'EMC' => ['Emercoin'],
        'BNT' => ['Bancor'],
        'LINK' => ['ChainLink'],
        'REQ' => ['Request Network'],
        'DCN' => ['Dentacoin'],
        'PAY' => ['TenX'],
        'ICN' => ['Iconomi'],
        'NEBL' => ['Neblio'],
        'WAX' => ['WAX'],
        'CND' => ['Cindicator']
    ];

    public static $currencyCodes = [
        'AFA' => ['Afghan Afghani', '971'],
        'AWG' => ['Aruban Florin', '533'],
        'AUD' => ['Australian Dollars', '036'],
        'ARS' => ['Argentine Pes', '032'],
        'AZN' => ['Azerbaijanian Manat', '944'],
        'BSD' => ['Bahamian Dollar', '044'],
        'BDT' => ['Bangladeshi Taka', '050'],
        'BBD' => ['Barbados Dollar', '052'],
        'BYR' => ['Belarussian Rouble', '974'],
        'BOB' => ['Bolivian Boliviano', '068'],
        'BRL' => ['Brazilian Real', '986'],
        'GBP' => ['British Pounds Sterling', '826'],
        'BGN' => ['Bulgarian Lev', '975'],
        'KHR' => ['Cambodia Riel', '116'],
        'CAD' => ['Canadian Dollars', '124'],
        'KYD' => ['Cayman Islands Dollar', '136'],
        'CLP' => ['Chilean Peso', '152'],
        'CNY' => ['Chinese Renminbi Yuan', '156'],
        'COP' => ['Colombian Peso', '170'],
        'CRC' => ['Costa Rican Colon', '188'],
        'HRK' => ['Croatia Kuna', '191'],
        'CPY' => ['Cypriot Pounds', '196'],
        'CZK' => ['Czech Koruna', '203'],
        'DKK' => ['Danish Krone', '208'],
        'DOP' => ['Dominican Republic Peso', '214'],
        'XCD' => ['East Caribbean Dollar', '951'],
        'EGP' => ['Egyptian Pound', '818'],
        'ERN' => ['Eritrean Nakfa', '232'],
        'EEK' => ['Estonia Kroon', '233'],
        'EUR' => ['Euro', '978'],
        'GEL' => ['Georgian Lari', '981'],
        'GHC' => ['Ghana Cedi', '288'],
        'GIP' => ['Gibraltar Pound', '292'],
        'GTQ' => ['Guatemala Quetzal', '320'],
        'HNL' => ['Honduras Lempira', '340'],
        'HKD' => ['Hong Kong Dollars', '344'],
        'HUF' => ['Hungary Forint', '348'],
        'ISK' => ['Icelandic Krona', '352'],
        'INR' => ['Indian Rupee', '356'],
        'IDR' => ['Indonesia Rupiah', '360'],
        'ILS' => ['Israel Shekel', '376'],
        'JMD' => ['Jamaican Dollar', '388'],
        'JPY' => ['Japanese yen', '392'],
        'KZT' => ['Kazakhstan Tenge', '368'],
        'KES' => ['Kenyan Shilling', '404'],
        'KWD' => ['Kuwaiti Dinar', '414'],
        'LVL' => ['Latvia Lat', '428'],
        'LBP' => ['Lebanese Pound', '422'],
        'LTL' => ['Lithuania Litas', '440'],
        'MOP' => ['Macau Pataca', '446'],
        'MKD' => ['Macedonian Denar', '807'],
        'MGA' => ['Malagascy Ariary', '969'],
        'MYR' => ['Malaysian Ringgit', '458'],
        'MTL' => ['Maltese Lira', '470'],
        'BAM' => ['Marka', '977'],
        'MUR' => ['Mauritius Rupee', '480'],
        'MXN' => ['Mexican Pesos', '484'],
        'MZM' => ['Mozambique Metical', '508'],
        'NPR' => ['Nepalese Rupee', '524'],
        'ANG' => ['Netherlands Antilles Guilder', '532'],
        'TWD' => ['New Taiwanese Dollars', '901'],
        'NZD' => ['New Zealand Dollars', '554'],
        'NIO' => ['Nicaragua Cordoba', '558'],
        'NGN' => ['Nigeria Naira', '566'],
        'KPW' => ['North Korean Won', '408'],
        'NOK' => ['Norwegian Krone', '578'],
        'OMR' => ['Omani Riyal', '512'],
        'PKR' => ['Pakistani Rupee', '586'],
        'PYG' => ['Paraguay Guarani', '600'],
        'PEN' => ['Peru New Sol', '604'],
        'PHP' => ['Philippine Pesos', '608'],
        'QAR' => ['Qatari Riyal', '634'],
        'RON' => ['Romanian New Leu', '946'],
        'RUB' => ['Russian Federation Ruble', '643'],
        'SAR' => ['Saudi Riyal', '682'],
        'CSD' => ['Serbian Dinar', '891'],
        'SCR' => ['Seychelles Rupee', '690'],
        'SGD' => ['Singapore Dollars', '702'],
        'SKK' => ['Slovak Koruna', '703'],
        'SIT' => ['Slovenia Tolar', '705'],
        'ZAR' => ['South African Rand', '710'],
        'KRW' => ['South Korean Won', '410'],
        'LKR' => ['Sri Lankan Rupee', '144'],
        'SRD' => ['Surinam Dollar', '968'],
        'SEK' => ['Swedish Krona', '752'],
        'CHF' => ['Swiss Francs', '756'],
        'TZS' => ['Tanzanian Shilling', '834'],
        'THB' => ['Thai Baht', '764'],
        'TTD' => ['Trinidad and Tobago Dollar', '780'],
        'TRY' => ['Turkish New Lira', '949'],
        'AED' => ['UAE Dirham', '784'],
        'USD' => ['US Dollars', '840'],
        'UGX' => ['Ugandian Shilling', '800'],
        'UAH' => ['Ukraine Hryvna', '980'],
        'UYU' => ['Uruguayan Peso', '858'],
        'UZS' => ['Uzbekistani Som', '860'],
        'VEB' => ['Venezuela Bolivar', '862'],
        'VND' => ['Vietnam Dong', '704'],
        'AMK' => ['Zambian Kwacha', '894'],
        'ZWD' => ['Zimbabwe Dollar', '716'],
    ];

    public static function getCryptoList() {
        return array_combine(array_keys(self::$cryptosCodes), array_keys(self::$cryptosCodes));
    }
}