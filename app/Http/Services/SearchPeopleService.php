<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SearchPeopleService
{

    protected $baseUrl;
    protected $currentSearchToken;

    public function __construct() {
        $this->baseUrl = config('constant.people_search.base_url');
    }

    function getCookie() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
                CURLOPT_URL => $this->baseUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_HEADER => 1
                // CURLOPT_CUSTOMREQUEST => 'POST',
        ));

        $response = curl_exec($curl);
    
        curl_close($curl);
        $this->currentSearchToken = $this->getToken($response);
        
    }

    protected function getToken($response) {
        $headers = array();

        $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));
    
        foreach (explode("\r\n", $header_text) as $i => $line) {
            if ($i === 0)
                $headers['http_code'] = $line;
            else
            {
                list ($key, $value) = explode(': ', $line);
                if(in_array($key, ["set-cookie","Set-Cookie"])) {
                    $cookie = explode(' ', $value);
                    $headers[$key] = $cookie[0];
                    // dump($key, $value,$cookie);
                }
    
            }
        }

        // Log::info('cookie data'.$headers);
        return $headers['Set-Cookie'].' '.$headers['set-cookie'];
    }
    // Call for Requested Data 
    function search($requestData) {
        
        // $this->getCookie();
        // Prepare Url and token
        $url = $this->baseUrl.config('constant.people_search.slugs.search');
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_HEADER => 1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $requestData,
                CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                // 'Cookie: XSRF-TOKEN='.$token
                // 'Cookie: '.$this->currentSearchToken
                ),
        ));

        $response = curl_exec($curl);

        $finalToken = $this->getToken($response);
        curl_close($curl);

    // Write Token to file 
        $file = public_path('result-token.txt');
        file_put_contents($file, $finalToken);

}

    // Call For get Data
    function getSearchResult() {

        // Prepare Url and token
        $url = $this->baseUrl.config('constant.people_search.slugs.search-result');
        $url .=  request()->has('page') ? '?page='.request()->page : '' ;

        $file = public_path('result-token.txt');
        $token = file_get_contents($file);

        $curl2 = curl_init();
        curl_setopt_array($curl2, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                // 'Cookie: XSRF-TOKEN='.$token
                'Cookie: '.$token
            ),
        ));
    
        $response2 = curl_exec($curl2);
    
        curl_close($curl2);
        return $response2;
    }

}