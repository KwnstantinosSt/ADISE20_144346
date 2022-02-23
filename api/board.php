<?php 
//headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,POST,PUT');    
header('Content-Type: application/json');

//initializing our api

include_once('../core/initialize.php');

//get REQUEST method and get board 

if($_SERVER['REQUEST_METHOD'] === 'GET'){

    if(isset($_GET["pos"])){
        $pos = $_GET["pos"];
        $backgammon = new Backgammon($mysqli);
        $result = $backgammon->readPosition($pos);
        $num = mysqli_num_rows($result);

        
    if($num>0){

    $back_arr = array();
    $back_arr['data'] = array();

    while($row = $result->fetch_assoc()){
        extract($row);
        $back_item = array(
            'x' => $x ,
            'y' => $y ,
            'pos' => $pos,
            'piece' => $PIECE
        );

        array_push($back_arr['data'],$back_item);
    }
    //convert to Json and output
    echo json_encode($back_arr);


}else{
    header("HTTP/1.1 404 Not Found");
    echo json_encode(array('message' => 'No board data found.'));

}

    }else{


//instatiate backgammon

$backgammon = new Backgammon($mysqli);

//execute readBoard method
$result = $backgammon->readBoard();

//get the row count

$num = mysqli_num_rows($result);

if($num>0){

    $back_arr = array();
    $back_arr['data'] = array();

    while($row = $result->fetch_assoc()){
        extract($row);
        $back_item = array(
            'x' => $x ,
            'y' => $y ,
            'pos' => $pos,
            'piece' => $PIECE
        );

        array_push($back_arr['data'],$back_item);
    }
    //convert to Json and output
    echo json_encode($back_arr);


}else{
    header("HTTP/1.1 404 Not Found");
    echo json_encode(array('message' => 'No board data found.'));

}
        }
    //get REQUEST method and update board 
    }else if(($_SERVER['REQUEST_METHOD'] === 'POST')){

       // echo json_encode(array('message' => 'Resetting the Board and the whole game.'));
      
        $backgammon = new Backgammon($mysqli);

        //execute resetBoard method
        $result = $backgammon->resetBoard();
        $result = $backgammon->readBoard();
        $num = mysqli_num_rows($result);
        if($num>0){

            $back_arr = array();
            $back_arr['data'] = array();
        
            while($row = $result->fetch_assoc()){
                extract($row);
                $back_item = array(
                    'x' => $x ,
                    'y' => $y ,
                    'pos' => $pos,
                    'piece' => $PIECE
                );
        
                array_push($back_arr['data'],$back_item);
            }
            //convert to Json and output
            echo json_encode($back_arr);
        
        
             }else{
                 header("HTTP/1.1 404 Not Found");
                 echo json_encode(array('message' => 'No board data found.'));
        
                }
    

    }else if(($_SERVER['REQUEST_METHOD'] === 'PUT')){

        //echo json_encode(array('message' => 'PUT method passed.'));
                
        $backgammon = new Backgammon($mysqli);
        $data = json_decode(file_get_contents("php://input"));
        $backgammon->p_o=$data->pos_old;
        $backgammon->p_n=$data->pos_new;
      

        if($backgammon->movePiece()){
            echo json_encode(
                array('message' => 'Piece moved')
            );

        }else{
            header("HTTP/1.1 404 Not Found");
            echo json_encode(
                array('message' => 'Piece not moved!!!')
            );
            die();
        }






    }else{
         header("HTTP/1.1 404 Not Found");
         die(json_encode(array('message' => 'Wrong Request Method.')));}
?>