<?php

namespace App\Http\Models;

class WalletModels
{
    private function dataUSer(){
        return [
            "id" => "526ea8b2-428e-403b-b9fd-f10972e0d6fe",
            "name" => "tantan supriadi",
            "customer_xid" => "ea0212d3-abd6-406f-8c67-868e814a2436",
            "token" => "cb04f9f26632ad602f14acef21c58f58f6fe5fb55a",
        ];
    }
    private function wallet(){
        return  [
            "id" => "c4d7d61f-b702-44a8-af97-5dbdafa96551",
            "account" => "c4d7d61f-b702-44a8-af97-5dbdafa96551",
            "user_id"=> "526ea8b2-428e-403b-b9fd-f10972e0d6fe",
            "balance" => 0,
            "customer_xid" => "ea0212d3-abd6-406f-8c67-868e814a2436",
            "status" => "enabled",
            "enable_at" => "1994-11-05T08:15:30-05:00",
            "disable_at" => "1994-11-05T08:15:30-05:00",
        ];
    }

    
    public function cekUser($data)
    {
        $dataUser = WalletModels::dataUser();
        if($dataUser['customer_xid'] != $data){
            return [];
        }
        return json_encode($dataUser);
    }

    public function cekToken($token)
    {
        $dataUser = WalletModels::dataUser();
    
        if($dataUser['token'] != $token){
            return [];
        }

        return json_encode($dataUser);
    } 
    
    public function cekWallet($id)
    {
      
        $wallet = WalletModels::wallet();
    
        if($id != $wallet['user_id']){
            return [];
        }

        return json_encode($wallet);
    }


}
