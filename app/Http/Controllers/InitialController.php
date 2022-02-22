<?php

namespace App\Http\Controllers;
use App\Http\Models\WalletModels;
use Ramsey\Uuid\Uuid;

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
        $customer = WalletModels::cekCustomer($req['customer_xid']);
        

        if(!empty($customer)){
            $uuid = Uuid::uuid4()->toString();
            $token = hash('sha256', $uuid);
            $account_data = [
                $uuid,
                $req['customer_xid'],
                $token,
            ];

            $generate_account = WalletModels::generateAccount($account_data);

            $status = 201;
            $result = [
                "status"=> "success",
                "data" => ["token" => $generate_account ]
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
        $req = $request->getParsedBody();
        $cekToken = WalletModels::cekToken( str_replace("Token ", "", $token[0]) );

        if($cekToken){
            $wallet = WalletModels::cekWallet( $cekToken[0]->id);
            if($req){
                if($wallet){
                    $update = WalletModels::disableWallet( $wallet[0]->owned_by, $req['is_disabled'] );
                    if($update){
                        $status = 201;
                            $result = [
                                "status"=> "success",
                                "data" => [
                                    "wallet" => [
                                        "id"=> $wallet[0]->id,
                                        "owned_by"=>  $wallet[0]->owned_by,
                                        "status"=> ($req['is_disabled'] == 'true') ? 'disabled' : 'enabled',
                                        "balance"=> $wallet[0]->saldo
                                    ]
                                ]
                            ];
                            $response->getBody()->write(json_encode($result));
                            return $response->withStatus($status);
                    }
                }  
            }else{
                if(!$wallet){
                    $uuid = Uuid::uuid4()->toString();
                    $wallet_data = [
                        $uuid,
                        $cekToken[0]->id,
                        1
                    ];
                    $enable = WalletModels::enableWallet( $wallet_data );
                        if($enable){
                            $status = 201;
                            $result = [
                                "status"=> "success",
                                "data" => [
                                    "wallet" => [
                                        "id"=> $uuid,
                                        "owned_by"=>  $cekToken[0]->id,
                                        "status"=> "enabled",
                                        "balance"=> 0
                                    ]
                                ]
                            ];
                            $response->getBody()->write(json_encode($result));
                            return $response->withStatus($status);
                        }
                    }
                }
            }

        $response->getBody()->write(json_encode($result));
        return $response->withStatus($status);
    }

    public function checkBallance( $request,  $response){
        $status = 400;
        $result = [
                "status"=> "fail",
                "data" => ["error" =>  "wrong account"]
        ];
        $token = $request->getHeader("Authorization"); 
        $req = $request->getParsedBody();
        $cekToken = WalletModels::cekToken( str_replace("Token ", "", $token[0]) );
        $wallet = WalletModels::cekWallet( $cekToken[0]->id);

        if($wallet){
            $status = 200;
            $result = [
                "status"=> "success",
                "data" => [
                    "wallet" => [
                        "id" => $wallet[0]->id,
                        "owned_by" => $wallet[0]->owned_by,
                        "status" => ($wallet[0]->is_enable == true) ?  'enabled' : 'disabled' ,
                        "enabled_at"=> $wallet[0]->created_at,
                        "balance" => $wallet[0]->saldo
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

        $token = $request->getHeader("Authorization"); 
        $req = $request->getParsedBody();
        $cekToken = WalletModels::cekToken( str_replace("Token ", "", $token[0]) );
        $wallet = WalletModels::cekWallet( $cekToken[0]->id);
        if($req){
            if($wallet){
                $lastBalance = $wallet[0]->saldo + $req['amount'];
                $uuid = Uuid::uuid4()->toString();
                $dataDepo = [
                    $uuid,
                    $req['amount'],
                    $req['reference_id'],
                    $wallet[0]->owned_by,
                    $wallet[0]->id,
                    'deposit'
                ];
                $deposit =  WalletModels::transactions($dataDepo, $lastBalance);
                if($deposit){
                    $status = 201;
                    $result = [
                        "status"=> "success",
                        "data" => [
                            "deposit" => [
                                "id"=> $uuid,
                                "deposited_by"=> $wallet[0]->owned_by,
                                "status"=> "success",
                                "deposited_at"=> date('Y-m-d H:i:s'),
                                "amount"=> $req['amount'],
                                "reference_id"=> $req['reference_id']
                            ]
                        ]
                    ];
                }
            }
        }

        $response->getBody()->write(json_encode($result));
        return $response->withStatus($status);
    } 
    
    public function withdrawals( $request,  $response){
         $status = 400;
        $result = [
                "status"=> "fail",
                "data" => ["error" =>  "failed to deposits"]
        ];
        $token = $request->getHeader("Authorization"); 

        $token = $request->getHeader("Authorization"); 
        $req = $request->getParsedBody();
        $cekToken = WalletModels::cekToken( str_replace("Token ", "", $token[0]) );
        $wallet = WalletModels::cekWallet( $cekToken[0]->id);
        if($req){
            if($wallet){
                $lastBalance = $wallet[0]->saldo - $req['amount'];
                $uuid = Uuid::uuid4()->toString();
                $dataDepo = [
                    $uuid,
                    $req['amount'],
                    $req['reference_id'],
                    $wallet[0]->owned_by,
                    $wallet[0]->id,
                    'withdraw'
                ];
                $deposit =  WalletModels::transactions($dataDepo, $lastBalance);
                if($deposit){
                    $status = 201;
                    $result = [
                        "status"=> "success",
                        "data" => [
                            "deposit" => [
                                "id"=> $uuid,
                                "deposited_by"=> $wallet[0]->owned_by,
                                "status"=> "success",
                                "deposited_at"=> date('Y-m-d H:i:s'),
                                "amount"=> $req['amount'],
                                "reference_id"=> $req['reference_id']
                            ]
                        ]
                    ];
                }
            }
        }

        $response->getBody()->write(json_encode($result));
        return $response->withStatus($status);
    }

}
