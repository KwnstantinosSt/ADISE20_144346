<?php 
//headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');    
header('Content-Type: application/json');

//initializing our api

include_once('../core/initialize.php');

//get REQUEST method and get dices 

if($_SERVER['REQUEST_METHOD'] === 'GET'){

    if(isset($_GET["d"])){
        $dices = $_GET["d"];
        $dice = new Dice($mysqli);
        $result = $dice->lastDice($dices);
        $num = mysqli_num_rows($result);

      
    if($num>0 && $result){

    $dice_arr = array();
    $dice_arr['data'] = array();

    while($row = $result->fetch_assoc()){
        extract($row);
        $dice_item = array(
            'ID' => $ID ,
            'USERNAME' => $USERNAME ,
            'DICE1' => $DICE1,
            'DICE2' => $DICE2,
            'TIME' => $TIME
        );

        array_push($dice_arr['data'],$dice_item);
    }
    //convert to Json and output
    echo json_encode($dice_arr);


}else{
    header("HTTP/1.1 404 Not Found");
    echo json_encode(array('message' => 'No last dice data found.'));

}

    }else{
        
        //instatiate dice

$dice = new Dice($mysqli);

//execute showDice method
$result = $dice->showDice();

//get the row count

$num = mysqli_num_rows($result);

if($num>0){

    $dice_arr = array();
    $dice_arr['data'] = array();

    while($row = $result->fetch_assoc()){
        extract($row);
        $dice_item = array(
            'id' => $ID ,
            'username' => $USERNAME,
            'dice1' => $DICE1 ,
            'dice2' => $DICE2 ,
            'time' => $TIME ,
        );

        array_push($dice_arr['data'],$dice_item);
    }
    //convert to Json and output
    echo json_encode($dice_arr);


}else{
    header("HTTP/1.1 404 Not Found");
    echo json_encode(array('message' => 'No Dices found.'));

}
    }

    }else{
         header("HTTP/1.1 404 Not Found");
         die(json_encode(array('message' => 'Wrong Request Method.')));}
?>