<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8 ");
header("Access-Control-Allow-Methods: * ");
header("Access-Control-Allow-Max-Age: 3600 ");
header("Access-Control-Allow-Headers: * ");



class ClientController
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
                    $data['data'] = array(
                        'id'=>$id,
                        'fullname'=>$fullname,
                        'email'=>$email,
                        'number'=>$number,
                        'is_admin'=>$is_admin,
                        'is_pro'=>$is_pro,
                    );
                    echo json_encode($data);
                }else{
                    $message = array(
                        'error'=>true,
                        'message'=>'Invalid login details'
                    );
                    array_push($data['data'],$message);
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
        echo $_COOKIE['idPros'];
    }
    
}