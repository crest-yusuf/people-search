<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use DOMDocument;
use DOMXPath;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;

class UserSearchController extends Controller
{
    protected function searchCurlRequest($request) {
        
            // $request['_token'] = 'nDsETaK3bflJydBGfF2CM6RHaFvUZQCBCWTb7AoM';
        // $request['affid'] = '2';
        // $request['tc'] = '2';
        // $request['firstName'] = "John";
        // $request['lastName'] = "Doe";

        $requestData = "firstName=".$request['firstName']."&lastName=".$request['lastName']."&state=".$request['state'];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://propeoplesearch.com/ld/people/default/processing',
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
              'Cookie: XSRF-TOKEN=eyJpdiI6Ii8rUWlhRU4vK0tWa0VscjhzZGNCTVE9PSIsInZhbHVlIjoiZ3RVRXYzSzN2ZU1ZTFdqbW02azdnWlhEQnRuL05kbnNQWXJ1ejdDdWJXVCs1SWFXd1pnVThqNGxncUVubGRlK0orZjluMll6YTI0VFJMRU9EVyszYTlpcy9kLytUeE81c0FqVmJMK2lEc3B3TFFibVZJTnl3d0RkUmJQSTdHZjgiLCJtYWMiOiI4OGZkNzI0OWFmMTQ5ZGUyNzdkYjk0MDE4ZjFjN2FlMTZjMTg0ZDQzNjcyODg0MzA4MzA4MGY5MzZkMTFkYzc4In0%3D; propeoplesearch_session=eyJpdiI6IldyOWtDY0ljdytXbkFSS0lPRXNza2c9PSIsInZhbHVlIjoiSFZseFBEZ0lKT0I1Z05JQnJ0bUVJT0d5dW9GaXdFWkdQMHN0YVo4ck1zUCtUcWc3RndVeWZSaDF5SjdPK2xMekZob1Y0L2Q0bnIzd1NBUVFpV0I0MzhjSGsvcjlSQWE1Nm5sRHR1Q0hidjdEdVJQM2NFNHBkVmR0MjIvOXdvM0ciLCJtYWMiOiI5MjkyZDM0NTZjNGZjNTkwNGRlZmI1NmZjNDNlYjJlNWM0YTI5NzM3M2Y5OWYxY2VkOTMwYTQyZjBhZGRkMWJkIn0%3D'
            ),
          ));

        $response = curl_exec($curl);

        curl_close($curl);
// echo $response;

    }

    function search() {
    //     $data = [
    //         "Name" => [
    //             "Yusuf Patel",
    //         ],
    //         "Age" => [
    //             "Yusuf Patel",
    //         ]
    //     ];
    //     $response['titles'] = ['Name','Age','Phone Numbers','Releted People','Location'];
    //     $response['list'] = $data;
    // return response()->json([
    //     'data' => $response,
    //     'message' => 'Search List', 'code' => 200]);
        $requestData = request()->all();
    $this->searchCurlRequest($requestData);


    $curl2 = curl_init();
    curl_setopt_array($curl2, array(
    CURLOPT_URL => 'https://propeoplesearch.com/ld/people/default/loading-results',
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

    $doc = new DOMDocument();
    $doc->loadHtml($response2, LIBXML_NOERROR);
    
    $xpath = new DOMXPath($doc);
    $titles = $xpath->evaluate('//ul[@class="list-result"]//li//div[@class="title"]');
    $query = '//ul[@class="list-result"]//li[@class="result-list-item"]//div[@class="item-wrapper"]';

    $entries = $xpath->query($query);
    $data = [];
    $labels = ['Name','Age','Phone Numbers','Releted People','Location'];
    $titlesArray = ['Name','Age','phone_numbers','releted_people','Location'];

    foreach ($entries as $key => $entry) {
        $title =  $xpath->evaluate('string(div[@class="result-label"])', $entry);
        $info =  $xpath->evaluate('string(div[@class="result-info"])', $entry);
        
        if(in_array($title,['Name','Age']))
            $info  = $xpath->evaluate('string(div[@class="result-info bold"])', $entry);    

        if($title == 'Phone Numbers') {
            $title = 'phone_numbers';
            $info = $xpath->evaluate('string(div[@class="result-info"]//span)', $entry);    
        }

        if(in_array($title,['Releted People','Location'])) {
            $title = $title == 'Releted People' ? 'releted_people' : $title; 
            $subArray = $xpath->query('div[@class="result-info"]/span', $entry);
            $subInfoArray = [];
            foreach($subArray as $subInfo)
            {
                $subInfoArray[] = $subInfo->textContent;
            }    
            $info = implode(", ", $subInfoArray);
        }

        if(in_array($title, $titlesArray)){
            $data[$title][] = $info;
        }     
    }
    
    $formatArray = [];
    foreach($data['Name'] as $key => $value) {
        $formatArray[] = [
            'Name' => $value,
            'Age' => $data['Age'][$key],
            'phone_numbers' => $data['phone_numbers'][$key],
            'releted_people' => $data['releted_people'][$key],
            'Location' => $data['Location'][$key]
        ];
    }

        $response['titles'] = $labels;
        $response['list'] = $formatArray;
    
    return response()->json([
        'data' => $response,
        'message' => 'Search List', 'code' => 200]);
}
}
