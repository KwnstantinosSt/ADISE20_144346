<?php 
//headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,POST');    
header('Content-Type: application/json');

//initializing our api

include_once('../core/initialize.php');

//get REQUEST method and get board status 

if($_SERVER['REQUEST_METHOD'] === 'GET'){

//instatiate players

$bStatus = new BoardStatus($mysqli);

//execute showPlayers method
$result = $bStatus->showStatus();

//get the row count

$num = mysqli_num_rows($result);

if($num>0){

    $status_arr = array();
    $status_arr['data'] = array();

    while($row = $result->fetch_assoc()){
        extract($row);
        $status_item = array(
            'status' => $STATUS ,
            'p_turn' => $P_TURN ,
            'result' => $RESULT,
            'last_action' => $LAST_ACTION
        );

        array_push($status_arr['data'],$status_item);
    }
    //convert to Json and output
    echo json_encode($status_arr);


}else{
    header("HTTP/1.1 404 Not Found");
    echo json_encode(array('message' => 'No Status found.'));

}
    //get REQUEST method and update board 
    }else if(($_SERVER['REQUEST_METHOD'] === 'POST')){

        //echo json_encode(array('message' => 'Posting new status.'));
        $bStatus = new BoardStatus($mysqli);
       
        if($bStatus->changeStatus()){
            echo json_encode(
                array('message' => 'Status Changed.')
            );

        }else{
            header("HTTP/1.1 404 Not Found");
            echo json_encode(
                array('message' => 'Status not changed!!!')
            );
            die();
        }


    }else{
        header("HTTP/1.1 404 Not Found");
         die(json_encode(array('message' => 'Wrong Request Method.')));}
?>