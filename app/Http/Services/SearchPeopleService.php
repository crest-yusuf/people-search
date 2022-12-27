<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SearchPeopleService
{

    protected $token;
    protected $baseUrl;

    public function __construct() {
        $this->token = env('TARGET_URL_SEARCH_TOKEN');
        $this->baseUrl = config('constant.base_url');
        // dd(env('TARGET_URL_SEARCH_TOKEN'),env('TARGET_URL_RESULT_TOKEN'));
    }

    function search($requestData) {
        $url = $this->baseUrl.config('constant.slugs.search');
        
        $token = env('TARGET_URL_SEARCH_TOKEN');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $requestData,
            CURLOPT_HTTPHEADER => array(
              'Content-Type: application/x-www-form-urlencoded',
              'Cookie: XSRF-TOKEN='.$token.'; propeoplesearch_session=eyJpdiI6IldyOWtDY0ljdytXbkFSS0lPRXNza2c9PSIsInZhbHVlIjoiSFZseFBEZ0lKT0I1Z05JQnJ0bUVJT0d5dW9GaXdFWkdQMHN0YVo4ck1zUCtUcWc3RndVeWZSaDF5SjdPK2xMekZob1Y0L2Q0bnIzd1NBUVFpV0I0MzhjSGsvcjlSQWE1Nm5sRHR1Q0hidjdEdVJQM2NFNHBkVmR0MjIvOXdvM0ciLCJtYWMiOiI5MjkyZDM0NTZjNGZjNTkwNGRlZmI1NmZjNDNlYjJlNWM0YTI5NzM3M2Y5OWYxY2VkOTMwYTQyZjBhZGRkMWJkIn0%3D'
            ),
          ));

        $response = curl_exec($curl);

        curl_close($curl);
    }

    function getSearchResult() {
        $url = $this->baseUrl.config('constant.slugs.search-result');
        $url .=  request()->has('page') ? '?page='.request()->page : '' ;
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
            'Cookie: XSRF-TOKEN=eyJpdiI6IjF2QjJnQWxFeHdBTWlRT1VycWZ0Z2c9PSIsInZhbHVlIjoiTkYvcDBXOTVxMnV2Z1FxbDg5QklDOTI1bk9Cd3BOYUozZ2w3TEcwblhLaXhkSDMvL1llbVBsRkVYUVV4OXU1cXowNWtqSzI4cWZYLzBPMVh3bENuOUdjMjB4N2RXdG1KazB4WG9zNG05L3ZyajJ3OEtrVmxSVlJEYnF5OEJHb2MiLCJtYWMiOiI2NTdmYjdlMGZhN2Y5MWNkMmY4NjBiY2Y4MWFkMGQzNGI2OGIwMWQwYmQ1ZDRkM2U3Y2JhODRmN2Q3ZWI1NDZjIn0%3D; propeoplesearch_session=eyJpdiI6IjZ5SHJXME50ZktCUXRlTHdTc202S2c9PSIsInZhbHVlIjoiSVlRc3B0YjB2MjErRUU0Rnl3WlhGOVpMbnNPRWRpOTNNbVNsUTV5N1YvM01lNlZkRnZZeGJmWXBTaFNNUm8vS08xeVl2UFlDb2VHdkhHN0dvanpFSW5yZEg5aVpxb3l6dnVBNExtZEwySE9UaFlOZUhCZ2ladlIyRStMaTFTRFMiLCJtYWMiOiI0Zjk0YTlhMTFjOGVjYzhmZDVmNzdmNzliNDI3NDBjZWRlMjhkYTA0OGRiMzMzOTA5ODA0NGVhNmEwMTNjZmU3In0%3D'
        ),
        ));
    
        $response2 = curl_exec($curl2);
    
        curl_close($curl2);
        return $response2;
    }

    // function getSearchResult() {
    //     $url = $this->baseUrl.config('constant.slugs.search-result');
    //     $curl = curl_init();
    //     curl_setopt_array($curl, array(
    //     CURLOPT_URL => $url,
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_ENCODING => '',
    //     CURLOPT_MAXREDIRS => 10,
    //     CURLOPT_TIMEOUT => 0,
    //     CURLOPT_FOLLOWLOCATION => true,
    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     CURLOPT_CUSTOMREQUEST => 'GET',
    //     CURLOPT_HTTPHEADER => array(
    //         'Cookie: XSRF-TOKEN='.$this->token.'; propeoplesearch_session=eyJpdiI6IjZ5SHJXME50ZktCUXRlTHdTc202S2c9PSIsInZhbHVlIjoiSVlRc3B0YjB2MjErRUU0Rnl3WlhGOVpMbnNPRWRpOTNNbVNsUTV5N1YvM01lNlZkRnZZeGJmWXBTaFNNUm8vS08xeVl2UFlDb2VHdkhHN0dvanpFSW5yZEg5aVpxb3l6dnVBNExtZEwySE9UaFlOZUhCZ2ladlIyRStMaTFTRFMiLCJtYWMiOiI0Zjk0YTlhMTFjOGVjYzhmZDVmNzdmNzliNDI3NDBjZWRlMjhkYTA0OGRiMzMzOTA5ODA0NGVhNmEwMTNjZmU3In0%3D'
    //     ),
    //     ));
    
    //     $response2 = curl_exec($curl);
    
    //     curl_close($curl);
    //     return $response2;
    // }

}