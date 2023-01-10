<?php

class UserController extends BaseController
{

    public function listMethod()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();
        if (strtoupper($requestMethod) == 'GET') {
            try {
                $userObj = new User();
                $arrUsers = $userObj->all();
                $responseData = json_encode($arrUsers);
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
        // send output 
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public function loginMethod(){

        $strErrorDesc = '';
        $strErrorHeader = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();
        $data = json_decode(file_get_contents("php://input"));

        if (strtoupper($requestMethod) == 'POST') {
                
                  if(!isset($data->email) 
                    || !isset($data->password)
                    || empty(trim($data->email))
                    || empty(trim($data->password))
                    ):

                    $fields = ['fields' => ['email','password']];
                    $strErrorDesc = $this->msg(0,422,'Please Fill in all Required Fields!',$fields);
               
                else:
                    $email = trim($data->email);
                    $password = trim($data->password);

                    if(!filter_var($email, FILTER_VALIDATE_EMAIL)):
                        $strErrorDesc = $this->msg(0,422,'Invalid Email Address!');                    
                   
                    elseif(strlen($password) < 8):
                        $strErrorDesc = $this->msg(0,422,'Your password must be at least 8 characters long!');
                    else:
                        try{

                            $userObj = new User();

                            if($row = $userObj->get($email)):
                                $check_password = password_verify($password, $row['password']);

                                if($check_password):

                                    $jwt = new JwtHandler();
                                    $token = $jwt->jwtEncodeData(
                                        'http://localhost/task/',
                                        array("user_id"=> $row['id'])
                                    );
                                    
                                    $strErrorDesc = [
                                        'success' => 1,
                                        'message' => 'You have successfully logged in.',
                                        'token' => $token
                                    ];
                              
                                else:
                                    $strErrorDesc = $this->msg(0,422,'Invalid Password!');
                                endif;

                            else:
                                $strErrorDesc = $this->msg(0,422,'Invalid Email Address!');
                            endif;
                        }
                        catch(PDOException $e){
                            $strErrorDesc = $this->msg(0,500,$e->getMessage());
                        }

                    endif;

                endif;
                    $responseData = json_encode($strErrorDesc);
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
        // send output 
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public function postRegisterUserMethod()
    {
        $strErrorDesc = '';
        $strErrorHeader = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();
        $data = json_decode(file_get_contents("php://input"));
        if (strtoupper($requestMethod) == 'POST') {
                
                        if ( !isset($data->name)
                            || !isset($data->email)
                            || !isset($data->password)
                            || empty(trim($data->name))
                            || empty(trim($data->email))
                            || empty(trim($data->password))
                        ) :

                            $fields = ['fields' => ['name', 'email', 'password']];
                            $strErrorDesc = $this->msg(0, 422, 'Please Fill in all Required Fields!', $fields);
                       
                        else :

                            $name = trim($data->name);
                            $email = trim($data->email);
                            $password = trim($data->password);
                            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) :
                                $strErrorDesc = $this->msg(0, 422, 'Invalid Email Address!');

                            elseif (strlen($password) < 8) :
                                $strErrorDesc = $this->msg(0, 422, 'Your password must be at least 8 characters long!');

                            elseif (strlen($name) < 3) :
                                $strErrorDesc = $this->msg(0, 422, 'Your name must be at least 3 characters long!');

                            else :
                                try {
                                    $userObj = new User();
                                    if($userObj->checkEmailExist($email)) :
                                       $strErrorDesc = $this->msg(0, 422, 'This E-mail already in use!');
                                    else :
                                        $userObj->add($name,$email,password_hash($password,PASSWORD_DEFAULT));
                                         $strErrorDesc = $this->msg(1, 201, 'You have successfully registered.');
                                    endif;
                                } catch (PDOException $e) {
                                    $strErrorDesc = $this->msg(0, 500, $e->getMessage());
                                }
                            endif;
                        endif;
                    $responseData = json_encode($strErrorDesc);
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
        // send output 
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public function getUserMethod(){

        $allHeaders = getallheaders();
        $auth = new Auth(new User(), $allHeaders);
        echo json_encode($auth->isValid());
    }


    public function createTaskMethod(){

        $strErrorDesc = '';
        $strErrorHeader = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();
        $data = json_decode(file_get_contents("php://input"));
        $allHeaders = getallheaders();
        $auth = new Auth(new User(), $allHeaders);
        $isValid = $auth->isValid();
        if (strtoupper($requestMethod) == 'POST' && $isValid['success']) {
                        if ( !isset($data->subject)
                            || !isset($data->description)
                            || !isset($data->status)
                            || !isset($data->priority)
                            || !isset($data->start_date)
                            || !isset($data->due_date)
                            || empty(trim($data->subject))
                            || empty(trim($data->description))
                            || empty(trim($data->priority))
                            || empty(trim($data->start_date))
                            || empty(trim($data->due_date))
                        ) :

                            $fields = ['fields' => ['subject', 'description', 'status','priority', 'start_date', 'due_date']];
                            $strErrorDesc = $this->msg(0, 422, 'Please Fill in all Required Fields!', $fields);
                       
                        else :

                            $taskData = ['subject'=>trim($data->subject),
                                         'description'=>trim($data->description),
                                         'status'=>trim($data->status),
                                         'priority'=>trim($data->priority),
                                         'start_date'=>trim($data->start_date),
                                         'due_date'=>trim($data->due_date),
                                        ];
                            $subject = trim($data->subject);
                            $description = trim($data->description);
                            $status = trim($data->status);
                            $priority = trim($data->priority);
                            $start_date = trim($data->start_date);
                            $due_date = trim($data->due_date);
                                try {
                                    $taskObj = new Task();
                                    $lastID = $taskObj->add($taskData);
                                    if(isset($lastID) && $lastID!=null){
                                        $noteArray = [];
                                        foreach ($data->notes as $key => $value) {
                                            $details = ['subject'=>trim($value->subject),
                                                 'task_id'=> $lastID,
                                                 'attachment'=>trim($value->attachment),
                                                 'note'=>trim($value->note)
                                                ];
                                            array_push($noteArray, $details);
                                        }
                                       $noteObj = new Note();
                                       if($noteObj->add($noteArray))
                                        $strErrorDesc = $this->msg(1, 201, 'Task created successfully.');
                                    }
                                         
                                } catch (PDOException $e) {
                                    $strErrorDesc = $this->msg(0, 500, $e->getMessage());
                                }
                        endif;
                    $responseData = json_encode($strErrorDesc);
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
        // send output 
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }

    }

    public function getAllNotesMethod(){

        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();
        $allHeaders = getallheaders();
        $auth = new Auth(new User(), $allHeaders);
        $isValid = $auth->isValid();
        if (strtoupper($requestMethod) == 'GET' && $isValid['success']) {
            try {
                $taskObj = new Task();
                $arrTasks = $taskObj->all();
                $data = array();
                foreach ($arrTasks as $row) {
                    $data[$row['task_id']][] = $row;
                }
                $responseData = json_encode($data);
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
        // send output 
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }


    function msg($success,$status,$message,$extra = []){
        return array_merge([
            'success' => $success,
            'status' => $status,
            'message' => $message
        ],$extra);
    }
}