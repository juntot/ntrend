<?php

namespace App\Services;

use DB;


class CurlService{

    public static function httpCurl($url, $method = 'GET', $postfields = [], $headers = ''){
        // phpinfo();

            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
    
            if(!$headers){
                $headers = array(
                    "Content-Type: application/json",
                    "Accept: application/json",
                );
            }
            // dd($url, $method, $postfields, $headers);
    
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            curl_setopt($ch, CURLOPT_HEADER, 1);


            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postfields));
    
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false );
            curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 2 );
            
            // curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );


    
            $result = curl_exec($ch);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header = substr($result, 0, $header_size);

            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $err = curl_error($ch);
            curl_close($ch);

            if ($err) {
                echo "Error #:" . $err;
            } else {

                $header = explode('Set-Cookie: ', $header);

                $response_header = '';
                foreach ($header as $key => $value) {
                    if(
                        str_contains($value, 'B1SESSION') ||
                        str_contains($value, 'CompanyDB') ||
                        str_contains($value, 'ROUTEID')
                    ){
                        $indexOf = strpos($value, ';');
                        $response_header .= $response_header? ';'.substr($value, 0, $indexOf): substr($value, 0, $indexOf);
                    }
                }

                // return ['data'=> json_decode($result), 'status'=>$httpcode,
                //     // $header_size,
                //     'cookie' => $response_header
                // ];

                return [
                    'data' => $response_header,
                    'statusCode' => $httpcode
                ];
            }
    }


    public static function httpCurlGet($url, $method = 'GET', $postfields = [], $headers = ''){
        // phpinfo();

            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
    
            if(!$headers){
                $headers = array(
                    "Content-Type: application/json",
                    "Accept: application/json",
                );
            }
            // dd($url, $method, $postfields, $headers);
    
            // curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_HEADER, 0);

            //$body = '{}';
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
            //curl_setopt($ch, CURLOPT_POSTFIELDS,$body);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false );
            curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 1 );
            

    
            $result = curl_exec($ch);

            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $err = curl_error($ch);
            curl_close($ch);

            if ($err) {
                echo "Error #:" . $err;
            } else {

                return ['data'=> json_decode($result), 'status'=>$httpcode];
            }
    }
}
