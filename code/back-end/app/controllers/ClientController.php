<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
require '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class ClientController extends Checker
{
    
    public function all()
    {
        $clients = new Client();
        $results = $clients->getAllClients();
        if($results){

            $array = array();
            $array['data'] = array();

            while($row = $results->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                
                $clients = array(
                    'id'=>$id,
                    'fullname'=>$fullname,
                    'email'=>$email,
                    'number'=>$number,
                    'password'=>$password,
                    
                );

                array_push($array['data'],$clients);
            }
        echo json_encode($array);
        }else
        {
            echo json_encode(
                array('message'=>'no data is here')
            );
        }
    }

    public function add()
    {
        $data =json_decode(file_get_contents("php://input"));

        if(isset($data->fullname) && isset($data->number) && isset($data->email) && isset($data->password)){
            $fullname = $data->fullname;
            $email = $data->email;
            $number = $data->number;
            $password  = $hash = password_hash($data->password, PASSWORD_DEFAULT);
        }else{
            echo "Your infos are empty";
        }


        if(isset($fullname) && isset($email) && isset($number) && isset($password) )
        {
            $client = new Client();
            if($client->addClient($fullname, $number, $email, $password)){
                echo json_encode(
                    array("message"=>"Your account have been created")
                );
            }else
            {
                echo json_encode(
                    array("message"=>"Client Account not created")
                );
            }
        }
    }



    public function login()
    {
        $data =json_decode(file_get_contents("php://input"));
        if(isset($data->email) && isset($data->password)){
            $emailCheck = $data->email;
            $passwordCheck = $data->password;
        }
        if(isset($emailCheck) && isset($passwordCheck))
        {
            $data =[];
            $data['data'] =[];
            $client = new Client();
            if($result = $client->getClient($emailCheck)){
                $result = extract($result);
                if(password_verify($passwordCheck,$password)){
                    $iat = time();
                    $payload_info = array(
                        "iss"=>"localhost",
                        "iat"=>$iat ,
                        "nbf"=>$iat+10,
                        "exp"=>$iat+86400,
                        "aud"=>"myusers",
                        "data"=>array(
                            "id"=>$id
                        ),
                    );
                    $secret_key = "msbToken";
                    $jwt= JWT::encode($payload_info,$secret_key,'HS256');
                    $data['data'] = array(
                        "error"=>false,
                        'token'=>$jwt,
                    );
                    echo json_encode($data);
                }else{
                    $data["data"] = array(
                        'error'=>true,
                        'message'=>'Invalid login details'
                    );
                    echo json_encode($data);
                }
                
            }else{
                $message = array(
                    'error'=>true,
                    'message'=>'User not found'
                );
                array_push($data['data'],$message);
                echo json_encode($data);
            }
        }


    }

    public function user()
    {
        
    }
    

    public function getPorProjects($id)
    {   
        $projects = new Projects();
        $results = $projects->get($id);
        if($results){
            $array = array();
            $array['data'] = array();

            while($row = $results->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $projects = array(
                    'professional_category'=>$professional_category,
                    'id'=>$id,
                    'title'=>$title,
                    'description'=>$description,
                    'tags'=>json_decode($tags),
                    'img'=>$img,
                    'date'=>$date
                );
                array_push($array['data'],$projects);
            }
        echo json_encode($array);
        }else
        {
            echo json_encode(
                array('message'=>'no data is here')
            );
        } 
    }


    public function addOrder()
    {
        $data =json_decode(file_get_contents("php://input"));
        $headers = getallheaders();
        $token = $headers["Authorization"];
        $result = parent::check($token);
        if($result){
            if(isset($data->product_id) && isset($result) && isset($data->address) && isset($data->Card_name) && isset($data->quantity)){
                $idProfessional = $result;
                $product_id = $data->product_id;
                $client_id = $result;
                $address = $data->address;
                $Card_name = $data->Card_name;
                $quantity = $data->quantity;
            }else{
                echo "Your infos are empty";
            }
    
            if(isset($idProfessional) && isset($product_id) && isset($client_id)&& isset($address) && isset($Card_name) && isset($quantity) )
            {
                $professional = new Orders();
                if($professional->add($product_id,$client_id,$address,$Card_name,$quantity)){
                    http_response_code(200);
                    echo json_encode(
                        array(
                            "message"=>"the order have been added ",
                        )
                    );
                }else
                {   
                    http_response_code(400);
                    echo json_encode(
                        array("message"=>"order not created")
                    );
                    }
                } 
        }
    }
}