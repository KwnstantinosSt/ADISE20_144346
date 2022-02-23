<?php 
//headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET ,PUT,DELETE');    
header('Content-Type: application/json');

//initializing our api

include_once('../core/initialize.php');

//get REQUEST method and get players 

if($_SERVER['REQUEST_METHOD'] === 'GET'){

    if (isset($_GET["p"])){
      //  echo $_GET["p"];
        $p =  $_GET["p"];
        $player = new Players($mysqli);
        $result = $player->onePlayer($p);
        $num = mysqli_num_rows($result);
        if($num>0){

            $play_arr = array();
            $play_arr['data'] = array();
        
            while($row = $result->fetch_assoc()){
                extract($row);
                $play_item = array(
                    'username' => $USERNAME ,
                    'piece_color' => $PIECE_COLOR ,
                    'auth' => $AUTH
                    
                );
        
                array_push($play_arr['data'],$play_item);
            }
            //convert to Json and output
            echo json_encode($play_arr);
        
        
        }else{
            header("HTTP/1.1 404 Not Found");
            echo json_encode(array('message' => 'No Players found.'));
        
        }

}else{

//instatiate players

$player = new Players($mysqli);

//execute showPlayers method
$result = $player->showPlayers();

//get the row count

$num = mysqli_num_rows($result);

if($num>0){

    $play_arr = array();
    $play_arr['data'] = array();

    while($row = $result->fetch_assoc()){
        extract($row);
        $play_item = array(
            'username' => $USERNAME ,
            'piece_color' => $PIECE_COLOR ,
            'auth' => $AUTH
            
        );

        array_push($play_arr['data'],$play_item);
    }
    //convert to Json and output
    echo json_encode($play_arr);


}else{
    header("HTTP/1.1 404 Not Found");
    echo json_encode(array('message' => 'No Players found.'));

}}
    //get REQUEST method and update board 
    }else if(($_SERVER['REQUEST_METHOD'] === 'PUT')){

        
        $player = new Players($mysqli);
        $data = json_decode(file_get_contents("php://input"));
        $player->set_name($data->username);
        $player->set_color($data->piece_color);
        $player->set_auth($data->auth);

        if($player->putPlayer()){
            echo json_encode(
                array('message' => 'Player Created')
            );

        }else{
            header("HTTP/1.1 404 Not Found");
            echo json_encode(
                array('message' => 'Player not created')
            );
            die();
        }
    
    
    
    
    
    }else if(($_SERVER['REQUEST_METHOD'] === 'DELETE')){
        $player = new Players($mysqli);
        $data = json_decode(file_get_contents("php://input"));
       
        $player->set_color($data->piece_color);
        

        if($player->deletePlayer()){
            echo json_encode(
                array('message' => 'Player deleted')
            );

        }else{
            header("HTTP/1.1 404 Not Found");
            echo json_encode(
                array('message' => 'Player not deleted')
            );
            die();
        }
    



    
    
    }else{
         
         header("HTTP/1.1 404 Not Found");
         die(json_encode(array('message' => 'Wrong Request Method.')));}
?>