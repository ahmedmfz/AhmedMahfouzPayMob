<?php

namespace Ahmedmahfouz\Paymob\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class PayMobService
{
    // hint
    // billing is array of user data [ 'first_name' , 'last_name' , 'email' , 'phone_number' ]
    
    public function pay($amount , $billing , $orderId , $service , $integrationId)
    {
        $token            = $this->getToken();
        $orderPayMobId    = $this->createOrder($token , $amount , $orderId);
        $tokenThirdStep   = $this->getPaymentToken($orderPayMobId, $billing, $token, $amount , $integrationId);

        if($service == 'MOBILE_WALLET'){
            $url = $this->getWalletUrlData($tokenThirdStep , $billing['phone_number']);
            if($url){
                return  Redirect::away($this->getWalletUrlData($tokenThirdStep , $billing['phone_number']));
            }
            return back()->with('error' , 'phone number is invalid Or Provider Not Work right now Plz Call Support');
        }
        
        return Redirect::away("https://accept.paymob.com/api/acceptance/iframes/".env('VISAIFRAMEID')."?payment_token=".$tokenThirdStep);
    }

    public function getToken()
    {
        $call = Http::post("https://accept.paymob.com/api/auth/tokens", [
            'api_key'   =>  config('paymob.token')
        ]);
        return $call->object()->token;
    }

    public function toPennies($value): int
    {
        return (int) (string) ((float) preg_replace("/[^0-9.]/", "", $value) * 100);
    }

    public function createOrder($token, $amount , $orderId)
    {
        $data = [
            "auth_token"        => $token,
            "delivery_needed"   => "false",
            "amount_cents"      => $this->toPennies($amount),
            "currency"          => "EGP",
            "merchant_order_id" => $orderId ,
            "items" => [],
        ];
        $order = Http::post("https://accept.paymob.com/api/ecommerce/orders", $data);
        return $order->object()->id;
    }

    public function getPaymentToken($orderId, $billing, $token, $amount, $integrationId)
    {
        $data = [
            "auth_token"   => $token,
            "amount_cents" => $this->toPennies($amount),
            "expiration"   => 3600,
            "order_id"     => $orderId,
            "billing_data" => [
                "email"        => $billing['email'],
                "first_name"   => $billing['first_name'],
                "last_name"    =>  $billing['last_name'],
                "phone_number" => $billing['phone_number'],
                "floor" => "NA",
                "street" => "NA",
                "apartment" => "NA",
                "building" => "NA",
                "shipping_method" => "NA",
                "postal_code" => "NA",
                "city" => "NA",
                "country" => "EG",
                "state" => "NA"
            ],
            "currency" => "EGP",
            "integration_id" => $integrationId
        ];

        $order = Http::post("https://accept.paymob.com/api/acceptance/payment_keys", $data);
        return $order->object()->token;
    }

    public function callback(Request $request): array
    {
        $string = $request['amount_cents'] . $request['created_at'] . $request['currency'] . $request['error_occured'] . $request['has_parent_transaction'] . $request['id'] . $request['integration_id'] . $request['is_3d_secure'] . $request['is_auth'] . $request['is_capture'] . $request['is_refunded'] . $request['is_standalone_payment'] . $request['is_voided'] . $request['order'] . $request['owner'] . $request['pending'] . $request['source_data_pan'] . $request['source_data_sub_type'] . $request['source_data_type'] . $request['success'];
        $hmac = $request['hmac'];
        $secret = config('paymob.hmac');
        $hased = hash_hmac('sha512', $string, $secret);
        if ($hased == $hmac) {
            if ($request['success'] == "true") {
                return [
                    'success' => true,
                    'payment_id' => $request['order'],
                    'message' => 'PAYMENT_DONE',
                    'process_data' => $request->all()
                ];
            } else {
                return [
                    'success' => false,
                    'payment_id' => $request['order'],
                    'message' => 'PAYMENT_FAILED_WITH_CODE',
                    'process_data' => $request->all()
                ];
            }
        } else {
            return [
                'success'       =>  false,
                'payment_id'    =>  null,
                'message'       => 'PAYMENT_FAILED',
                'process_data'  => $request->all()
            ];
        }
    }

    public function getWalletUrlData($token,$phone)
    {
        $source_data =[
                'source' => [
                    'identifier'=>$phone,
                    'subtype'=>'WALLET',
                ],
                "payment_token" => $token
            ];
        $response = Http::post('https://accept.paymob.com/api/acceptance/payments/pay',$source_data);
        // $url = $response->object()->redirect_url ? $response->object()->redirect_url : $response->object()->iframe_redirection_url;
        $url = $response->object()->redirect_url ;
        return $url;
    }

    public function getAmanUrlData($token)
    {
        $source_data =[
            'source' => [
                'identifier'=>'AGGREGATOR',
                'subtype'=>'AGGREGATOR',
            ],
            "payment_token" => $token
        ];
        $response = Http::post('https://accept.paymob.com/api/acceptance/payments/pay',$source_data);
        return $response->object()->data->bill_reference;
    }

    // public function refund($authToken, $transactionId, $amount)
    // {
    //     $refund_request = Http::post("https://accept.paymob.com/api/acceptance/void_refund/refund", [
    //         "auth_token"        =>      $authToken,
    //         "transaction_id"    =>      $transactionId,
    //         "amount_cents"      =>      $amount
    //     ]);
    //     $refund = $refund_request->object();
    //     dd($refund);
    // }
    
}
