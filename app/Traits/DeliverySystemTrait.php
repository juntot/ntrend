<?php

namespace App\Traits;
use App\Services\CurlService;
use DB;

trait DeliverySystemTrait
{
   // 
   public function consumeSAPEndpoint() {
    
        $path = '';
        if(request()->has('path'))
            $path = request('path');
        
        if(request()->has('query'))
            $path .= request('query');
        
        if(request()->has('params'))
            $path .= request('params');

        if(request()->has('order'))
            $path .= request('order');
        
        
        
        $result = '';
        $api = DB::table('API_table')
        ->whereNotNull('endpoint')
        ->first();
        // return $api->endpoint.'/'.str_replace ( ' ', '%20', $path);

        // $path = 'Invoices?$'.'select=DocNum,DocDueDate,CardCode,CardName,DocumentStatus,DocumentDelivery,DocumentsOwner,SalesPersonCode,Comments,DocumentLines&'.'$'."filter=DocDate ge '2022-11-01' and DocDate le '2022-11-30'";
        try {
            $result = CurlService::httpCurlGet(
                $api->endpoint.'/'.str_replace ( ' ', '%20', $path),
                'GET',
                [],
                array(
                    'Cookie: '.session()->get('sap'),
                    "Content-Type: application/json",
                    "Accept: application/json",
                )
            );
        } catch (\Throwable $th) {
            //throw $th;
        }
        
        return $result;
    }

    public function patchPostSAPEndPoint($params){

        $path = $params['path'];
        $postfields = $params['params'] ? $params['params'] : [];
        // return $params;
        $result = '';
        $api = DB::table('API_table')
        ->whereNotNull('endpoint')
        ->first();
        // return $postfields;
        try {
            $result = CurlService::httpCurlRequest(
                $api->endpoint.'/'.str_replace ( ' ', '%20', $path),
                $params['method'],
                $postfields,
                array(
                    'Cookie: '.session()->get('sap'),
                    "Content-Type: application/json",
                    "Accept: application/json",
                )
            );
        } catch (\Throwable $th) {
            throw $th;
            // return $result;
        }
        return $result;
    }

    public function smtp2goEmailAPI($to = [], $subject= '', $message = '', $cc = []){
        $apiKey =  env ("SMTP2G0_KEY", 'api-1D9F26C4CBC011EDA384F23C91C88F4E');
        // $subject = 'Customer Survey';
        $from = 'no-reply@northtrend.com <no-reply@northtrend.com>';
        $data = array(
            "api_key"   => env("SMTP2GOKEY","api-1D9F26C4CBC011EDA384F23C91C88F4E"),
            "to"        => $to,
            "sender"    => "no-reply@northtrend.com <no-reply@northtrend.com>",
            "subject"   => $subject,
            "html_body" => $message
        );    

        if(count($cc))
        $data["cc"] = $cc;


        $data_json = json_encode($data);
        
        $url = 'https://api.smtp2go.com/v3/email/send';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response  = curl_exec($ch);
        curl_close($ch);
        return $response;
    }


  }
