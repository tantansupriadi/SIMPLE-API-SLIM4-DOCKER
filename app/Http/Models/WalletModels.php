<?php

namespace App\Http\Models;
use App\Config\DB;
use \PDO;
class WalletModels
{   
    public static function cekCustomer($id)
    {
        $db = new DB();
        $conn = $db->connect();
        $sql = "SELECT id FROM customer where id = '".$id. "'";
        $stmt = $conn->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $customers;
    }

    public static function cekAccount($id)
    {
        $db = new DB();
        $conn = $db->connect();
        $sql = "SELECT token FROM account where customer_id = '".$id. "'";
        $stmt = $conn->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $customers;
    }

    public static function generateAccount($data)
    {
        $cek_account = WalletModels::cekAccount($data[1]);
        if(!$cek_account){
             $db = new DB();
            $conn = $db->connect();
            $sql = "INSERT INTO account (id,customer_id,token) values (?,?,?)";
            $stmt = $conn->prepare($sql);
            $customers =  $stmt->execute($data);
            return $data[2] ;
        }
        return $cek_account[0]->token;
    }
    
    public static function enableWallet($data)
    {
        $cek_wallet = WalletModels::cekWallet($data[1]);
        if(!$cek_wallet){
            $db = new DB();
            $conn = $db->connect();
            $sql = "INSERT INTO wallet (id,owned_by,is_enable) values (?,?,?)";
            $stmt = $conn->prepare($sql);
            $customers =  $stmt->execute($data);
            return $data[1] ;
        }
        return $cek_wallet[0]->id;
    }

    public static function cekToken($token)
    {
        $db = new DB();
        $conn = $db->connect();
        $sql = "SELECT * FROM account where token = '".$token. "'";
        $stmt = $conn->query($sql);
        $token = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $token;
    } 

    public static function cekWallet($account_id)
    {
        $db = new DB();
        $conn = $db->connect();
        $sql = "SELECT * FROM wallet where owned_by = '".$account_id. "'";
        $stmt = $conn->query($sql);
        $token = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $token;
    } 

    public static function disableWallet($id,$status)
    {
        $cek_wallet = WalletModels::cekWallet($id);
        
        if($cek_wallet){
            $db = new DB();
            $conn = $db->connect();
            $sql = "UPDATE  wallet SET  is_enable = ?  where owned_by = ?";
            $stmt = $conn->prepare($sql);
            $customers =  $stmt->execute([($status == 'true') ? 0 : 1 ,$id]);
            return $customers ;
        }
        return $cek_wallet[0]->id;
    }


    public static function transactions($depo,$lastBalance)
    { 
            $db = new DB();
            $conn = $db->connect();
            $sql = "INSERT INTO transaction (id,amount,reference_id,owned_by,wallet_id,type) values (?,?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $trans =  $stmt->execute($depo);

            if($trans){
                $sql = "UPDATE  wallet SET  saldo = ?  where owned_by = ?";
                $stmt = $conn->prepare($sql);
                $update =  $stmt->execute([$lastBalance,$depo[3]]);
            }
            return $update ;
        
    }
    
   


}
