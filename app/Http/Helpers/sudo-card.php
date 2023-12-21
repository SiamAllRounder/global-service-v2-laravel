<?php

//sudo virtual card system

use App\Models\VirtualCardApi;

function virtual_card_system($name)
{
    $method = VirtualCardApi::first();
    if( $method->config->name == $name){
        return  $method->config->name;
    }else{
        return false;
    }

}

function get_funding_source($api_key,$base_url){
    $curl = curl_init();
    curl_setopt_array($curl, [
    CURLOPT_URL => $base_url."/fundingsources",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer ".$api_key,
        "accept: application/json"
    ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        $result = json_decode( $response,true);
        return  $result;
    } else {
        $result = json_decode( $response,true);
        return  $result;
    }
}
function create_sudo_account($api_key,$base_url, $currency){

    $curl = curl_init();
    curl_setopt_array($curl, [
    CURLOPT_URL => $base_url."/accounts",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode([
        'type' => 'account',
        'currency' => $currency,
        'accountType' => 'Current'
    ]),
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer ".$api_key,
        "accept: application/json",
        "content-type: application/json"
    ],
    ]);

    $response = curl_exec($curl);

    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        $result = json_decode( $response,true);
        return  $result;
    } else {
        $result = json_decode( $response,true);
        return  $result['data']??[];
    }
}
function get_sudo_accounts($api_key,$base_url){

    $curl = curl_init();
    curl_setopt_array($curl, [
    CURLOPT_URL => $base_url."/accounts",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer ".$api_key,
        "accept: application/json"
    ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        $result = json_decode( $response,true);
        return  $result['data'];
    } else {
        $result = json_decode( $response,true);
        return  $result['data'];
    }
}
function create_sudo_customer($api_key,$base_url,$user){
    $curl = curl_init();

    curl_setopt_array($curl, [
    CURLOPT_URL => $base_url."/customers",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode([
        "type" => "individual",
        "name" => $user->fullname,
        "status" => "active",
        'emailAddress' => $user->email,
        'phoneNumber' =>$user->mobile??'323456789',
        "individual" => [
            'identity' => [
                'type' => 'BVN',
                'number' => '123456789'
            ],
            "firstName" =>  $user->firstname,
            "lastName" =>  $user->lastname,
            'dob' => '1999/01/01'
        ],
        "billingAddress" => [
            "line1" => $user->address->address??"4 Barnawa Close",
            "line2" => "",
            "city" => $user->address->city??"Barnawa",
            "state" => $user->address->state??"Kaduna",
            "country" => $user->address->country??"Nigeria",
            "postalCode" => $user->address->state??"800243"
        ]
    ]),
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer ".$api_key,
        "accept: application/json",
        "content-type: application/json"
    ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);


    if ($err) {

        $result = json_decode( $response,true);
        return  $result;
    } else {
        $result = json_decode( $response,true);
        return  $result;
    }
}
function create_virtual_card($api_key,$base_url,$customerId, $currency,$bankCode, $debitAccountId, $issuerCountry){

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $base_url."/cards",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            'type' => 'virtual',
            'currency' => $currency,
            'status' => 'active',
            'brand' =>"MasterCard",
            'issuerCountry' => $issuerCountry,
            'amount' => 100,
            'customerId' => $customerId,
            'bankCode' => $bankCode,
            'debitAccountId' =>$debitAccountId
        ]),
        CURLOPT_HTTPHEADER => [
        "Authorization: Bearer ". $api_key,
        "accept: application/json",
        "content-type: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return json_decode($response,true);
    } else {
        return json_decode($response,true);
    }
}
function cardUpdate($api_key,$base_url,$card_id,$status){
    $curl = curl_init();

    curl_setopt_array($curl, [
    CURLOPT_URL => $base_url."/cards"."/".$card_id,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "PUT",
    CURLOPT_POSTFIELDS => json_encode([
        'status' => $status
    ]),
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer ".$api_key,
        "accept: application/json",
        "content-type: application/json"
    ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        return json_decode($response,true);
    } else {
        return json_decode($response,true);
    }

}
function getCardToken($api_key,$base_url,$card_id){
    $curl = curl_init();
    curl_setopt_array($curl, [
    CURLOPT_URL => $base_url."/cards"."/".$card_id."/token",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer ".$api_key,
        "accept: application/json"
    ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);


    curl_close($curl);

    if ($err) {
     $result = json_decode($response,true);
     return $result;
    } else {
        $result = json_decode($response,true);
        return $result;
    }

}
function getCardTransactions($api_key,$base_url,$card_id){
    $curl = curl_init();
    curl_setopt_array($curl, [
    CURLOPT_URL => $base_url."/cards"."/".$card_id."/transactions",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer ".$api_key,
        "accept: application/json"
    ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
     $result = json_decode($response,true);
     return $result;
    } else {
        $result = json_decode($response,true);
        return $result;
    }

}
function getSudoBalance(){
    $method = VirtualCardApi::first();
    $currency = get_default_currency_code();
    $sudo_accounts = get_sudo_accounts( $method->config->sudo_api_key,$method->config->sudo_url);
    $filteredArray = array_filter($sudo_accounts, function($item) use ($currency) {
        return $item['currency'] === $currency;
    });
    $matchingElements = array_values($filteredArray);
    if( $matchingElements == [] || $matchingElements == null || $matchingElements == ""){
       $data =[
            'amount' => 0,
            'status' => false,
            'message' => get_default_currency_code()." Currency Not Supported For Sudo Account",
       ];
       return $data;
    }
    $curl = curl_init();
    curl_setopt_array($curl, [
    CURLOPT_URL => $method->config->sudo_url."/accounts"."/".$matchingElements[0]['_id']."/balance",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer ". $method->config->sudo_api_key,
        "accept: application/json"
    ],
    ]);

    $response = curl_exec($curl);
    $result = json_decode( $response,true);
    if(isset($result['statusCode'])){
        if($result['statusCode'] == 200){
            $data =[
                'amount' => $result['data']['availableBalance'],
                'status' => true,
                'message' =>" SuccessFully Fetch Account Balance",
           ];
            return  $data;
        }else{
            $data =[
                'amount' => 0,
                'status' => false,
                'message' =>"Something Is Wrong,Please Contact With Owner",
           ];
            return $data;
        }

    }


}

