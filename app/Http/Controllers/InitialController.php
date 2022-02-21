<?php

namespace App\Http\Controllers;
use App\Http\Models\WalletModels;

class InitialController
{
    public function index( $request,  $response)
    {

        $status = 400;
        $result = [
                "status"=> "fail",
                "data" => ["error" => [ "customer_xid" => ["Missing data for required field."]]]
        ];

        $req = $request->getParsedBody();
        $dataUser = WalletModels::cekUser($req['customer_xid']);
        $dataParse = json_decode($dataUser);
        if(!empty($dataUser)){
            $status = 201;
            $result = [
                "status"=> "success",
                "data" => ["token" => $dataParse->token]
            ];
        }

        $response->getBody()->write(json_encode($result));
        return $response->withStatus($status);
    }
    
    public function enableWallet( $request,  $response)
    {
        $status = 400;
        $result = [
                "status"=> "fail",
                "data" => ["error" =>  "Already enabled"]
        ];
        $token = $request->getHeader("Authorization"); 
        $dataUser = WalletModels::cekToken( str_replace("Token ", "", $token[0]) );
        $dataParse = json_decode($dataUser);
        $wallet = json_decode(WalletModels::cekWallet( $dataParse->id ));
        if(!empty($dataUser)){
            $req = $request->getParsedBody();
            if($req){
                if($req['is_disabled'] == true){
                    $status = 201;
                    $result = [
                        "status"=> "success",
                        "data" => [
                            "wallet" => [
                                "id"=> $wallet->id,
                                "owned_by"=>  $wallet->account,
                                "status"=> $wallet->status,
                                "disabled_at"=> $wallet->disable_at,
                                "balance"=> $wallet->balance
                            ]
                        ]
                    ];
                }
                $response->getBody()->write(json_encode($result));
                return $response->withStatus($status);
            }
            $status = 201;
            $result = [
                "status"=> "success",
                "data" => [
                    "wallet" => [
                       "id"=> $wallet->id,
                        "owned_by"=>  $wallet->account,
                        "status"=> $wallet->status,
                        "enable_at"=> $wallet->enable_at,
                        "balance"=> $wallet->balance
                    ]
                ]
            ];
        }

        $response->getBody()->write(json_encode($result));
        return $response->withStatus($status);
    }

    public function checkBallance( $request,  $response){
        $status = 400;
        $result = [
                "status"=> "fail",
                "data" => ["error" =>  "Disabled"]
        ];
        $token = $request->getHeader("Authorization"); 

        $req = $request->getParsedBody();
        $dataUser = WalletModels::cekToken( str_replace("Token ", "", $token[0]) );
        $dataParse = json_decode($dataUser);
        $wallet = json_decode(WalletModels::cekWallet( $dataParse->id ));

        if(!empty($dataUser)){
            $status = 200;
            $result = [
                "status"=> "success",
                "data" => [
                    "wallet" => [
                        "id" => $wallet->id,
                        "owned_by" => $wallet->account,
                        "status" => $wallet->status,
                        "enabled_at"=> $wallet->enable_at,
                        "balance" => $wallet->ballance
                    ]
                ]
            ];
        }

        $response->getBody()->write(json_encode($result));
        return $response->withStatus($status);
    }
    
    public function deposits( $request,  $response){
        $status = 400;
        $result = [
                "status"=> "fail",
                "data" => ["error" =>  "failed to deposits"]
        ];
        $token = $request->getHeader("Authorization"); 

        $req = $request->getParsedBody();
        $dataUser = WalletModels::cekToken( str_replace("Token ", "", $token[0]) );
        $dataParse = json_decode($dataUser);
        $wallet = json_decode(WalletModels::cekWallet( $dataParse->id ));
        if(!empty($dataUser)){
            $status = 200;
            $result = [
                "status"=> "success",
                "data" => [
                    "deposit" => [
                        "id"=> "ea0212d3-abd6-406f-8c67-868e814a2433",
                        "deposited_by"=> $dataParse->id,
                        "status"=> "success",
                        "deposited_at"=> "1994-11-05T08:15:30-05:00",
                        "amount"=> $req['amount'],
                        "reference_id"=> $req['reference_id']
                    ]
                ]
            ];
        }

        $response->getBody()->write(json_encode($result));
        return $response->withStatus($status);
    } 
    
    public function withdrawals( $request,  $response){
        $status = 400;
        $result = [
                "status"=> "fail",
                "data" => ["error" =>  "Disabled"]
        ];
        $token = $request->getHeader("Authorization"); 

        $req = $request->getParsedBody();
        $dataUser = WalletModels::cekToken( str_replace("Token ", "", $token[0]) );
        $dataParse = json_decode($dataUser);
        if(!empty($dataUser)){
            $status = 200;
            $result = [
                "status"=> "success",
                "data" => [
                    "withdrawal" => [
                          "id"=> "ea0212d3-abd6-406f-8c67-868e814a2433",
                            "withdrawn_by"=> $dataParse->id,
                            "status"=> "success",
                            "withdrawn_at"=> "1994-11-05T08:15:30-05:00",
                             "amount"=> $req['amount'],
                        "reference_id"=> $req['reference_id']
                    ]
                ]
            ];
        }

        $response->getBody()->write(json_encode($result));
        return $response->withStatus($status);
    }

}
