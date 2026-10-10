<?php


class Currency{

    public static function parseCurrencyFormat($price,$code=""){

        $context = &get_instance();

        if(is_array($code) && isset($code['currency']))
            $currency = $code['code'];
        else
            $currency = $context->mCurrencyModel->getCurrency($code);


        if($price==null)
            $price = 0;

        if($currency==NULL || !is_array($currency)){
            $currency = array(
                'symbol' => '$',
                'format' => 1,
                'cfd' => 2,
                'cdp' => '.',
                'cts' => ',',
                'code' => 'USD'
            );
        } else {
            if(!isset($currency['symbol'])) $currency['symbol'] = '$';
            if(!isset($currency['format'])) $currency['format'] = 1;
            if(!isset($currency['cfd']) || $currency['cfd'] === '') $currency['cfd'] = 2;
            if(!isset($currency['cdp'])) $currency['cdp'] = '.';
            if(!isset($currency['cts'])) $currency['cts'] = ',';
            if(!isset($currency['code'])) $currency['code'] = 'USD';
        }


        switch ($currency['format']){
            case 1:
                return $currency['symbol'].number_format((float)$price, (int)$currency['cfd'], $currency['cdp'], $currency['cts']);
                break;
            case 2:
                return number_format((float)$price, (int)$currency['cfd'], $currency['cdp'], $currency['cts']).$currency['symbol'];
                break;
            case 3:
                return $currency['symbol']." ".number_format((float)$price, (int)$currency['cfd'], $currency['cdp'], $currency['cts']);
                break;
            case 4:
                return number_format((float)$price, (int)$currency['cfd'], $currency['cdp'], $currency['cts'])." ".$currency['symbol'];
                break;
            case 5:
                return number_format((float)$price, (int)$currency['cfd'], $currency['cdp'], $currency['cts']);
                break;
            case 6:
                return $currency['symbol'].number_format((float)$price, (int)$currency['cfd'], $currency['cdp'], $currency['cts'])." " .$currency['code'];
                break;
            case 7:
                return $currency['code'].number_format((float)$price, (int)$currency['cfd'], $currency['cdp'], $currency['cts']);
                break;
            case 8:
                return number_format((float)$price, (int)$currency['cfd'], $currency['cdp'], $currency['cts']).$currency['code'];
                break;
            default:
                return $currency['symbol'].number_format((float)$price, (int)$currency['cfd'], $currency['cdp'], $currency['cts']);
                break;
        }

    }



    public static function getCurrency($code){

        $currencies = json_decode(CURRENCIES,JSON_OBJECT_AS_ARRAY);

        foreach ($currencies as $key => $value){
            if($key==$code){
                return $value;
            }
        }

        return ;
    }

}


