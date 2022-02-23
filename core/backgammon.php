<?php

    class Backgammon{
        //db stuff
        private $conn;
        private $table = 'board';

        //board properties
        public $x;
        public $y;
        public $pos;
        public $piece;
        public $p_o;
        public $p_n;
        public $dice1;
        public $dice2;
        public $status;

        //constructor with db connection
        public function __construct($mysqli)
        {
            $this->conn = $mysqli;
        }



        //read the whole board from database
        public function readBoard(){
            //create query
            $query = 'SELECT * FROM ' . $this->table;

            //prepare statement
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $res = $stmt ->get_result();
            
            return  $res;
        }


        //reset the whole board 
        public function resetBoard(){
             //create query
             $query = 'call CLEAN_BOARD()';
        
            //prepare statement
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $query = 'call CLEAN_ALL()';
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
                }


         //get specific position of board 
         public function readPosition($pos){
            $stmt = $this->conn->prepare("SELECT * FROM board WHERE pos = ?");
            $stmt->bind_param("i", $pos);
            $stmt->execute();
            $res = $stmt ->get_result();
            return  $res;
               }


        //function to move the piece
        public function movePiece(){
            $counters= $this->totalAvailMoves();
            echo $counters[0] . "   " .$counters[1] . "\n\n";
            $c1 = $counters[0];
            $c2 = $counters[1];
            
            if($c1 == 0 & $c2 == 0){
                $this->updateStatusDice();
                return true;
            }

            if($this->checkPosType($this->p_o,$this->p_n)){
                
                $this->checkDices();
                $this->readStatus();
                $pos_old_data = $this->getaDataPos($this->p_o);
                $pos_new_data = $this->getaDataPos($this->p_n);
                $pos_old = $this->p_o;
                $pos_new = $this->p_n;
                $diceA = $this->dice1;
                $diceB = $this->dice2;
                $status = $this->status;
                $dice_sum =  $diceA + $diceB;
               
                
                if($status == "B" & $pos_old>$pos_new){
             
                    $moves = $pos_old-$pos_new;
                        if($diceA == $moves){
                                if(strlen($pos_old_data)>0 & substr($pos_old_data,-1) == $status){
                                        if(strlen($pos_new_data) == 0){
                                            // call sql func kai metakinise to
                                                $stmt = $this->conn->prepare("call movePiece(?,?)");
                                                $stmt->bind_param("ii", $pos_old,$pos_new);
                                                $stmt->execute();
                                                $stmt->close();

                                                $stmt2 = $this->conn->prepare("call DiceAZero()");
                                                $stmt2->execute();
                                                $stmt2->close();
                                            return true;
                                        }else if(strlen($pos_new_data) >= 1 & substr($pos_new_data,-1) == $status){
                                            // call sql func kai metakinise to
                                            $stmt = $this->conn->prepare("call movePiece(?,?)");
                                            $stmt->bind_param("ii", $pos_old,$pos_new);
                                            $stmt->execute();
                                            $stmt->close();

                                            $stmt2 = $this->conn->prepare("call DiceAZero()");
                                            $stmt2->execute();
                                            $stmt2->close();
                                            return true;
                                        }else if(strlen($pos_new_data) == 1 & substr($pos_new_data,-1) != $status){
                                            // call sql func kai metakinise to
                                            $stmt = $this->conn->prepare("call movePiece(?,?)");
                                            $stmt->bind_param("ii", $pos_old,$pos_new);
                                            $stmt->execute();
                                            $stmt->close();

                                            $stmt2 = $this->conn->prepare("call DiceAZero()");
                                            $stmt2->execute();
                                            $stmt2->close();
                                            return true;
                                        }
                                }

                        }else if($diceB == $moves){
                                if(strlen($pos_old_data)>0 & substr($pos_old_data,-1) == $status){
                                    if(strlen($pos_new_data) == 0){
                                        // call sql func kai metakinise to
                                        $stmt = $this->conn->prepare("call movePiece(?,?)");
                                        $stmt->bind_param("ii", $pos_old,$pos_new);
                                        $stmt->execute();
                                        $stmt->close();

                                        $stmt2 = $this->conn->prepare("call DiceBZero()");
                                        $stmt2->execute();
                                        $stmt2->close();
                                        return true;
                                    }else if(strlen($pos_new_data) >= 1 & substr($pos_new_data,-1) == $status){
                                        // call sql func kai metakinise to
                                        $stmt = $this->conn->prepare("call movePiece(?,?)");
                                        $stmt->bind_param("ii", $pos_old,$pos_new);
                                        $stmt->execute();
                                        $stmt->close();

                                        $stmt2 = $this->conn->prepare("call DiceBZero()");
                                        $stmt2->execute();
                                        $stmt2->close();
                                        return true;
                                    }else if(strlen($pos_new_data) == 1 & substr($pos_new_data,-1) != $status){
                                        // call sql func kai metakinise to
                                        $stmt = $this->conn->prepare("call movePiece(?,?)");
                                        $stmt->bind_param("ii", $pos_old,$pos_new);
                                        $stmt->execute();
                                        $stmt->close();

                                        $stmt2 = $this->conn->prepare("call DiceBZero()");
                                        $stmt2->execute();
                                        $stmt2->close();
                                        return true;
                                    }
                                }

                        }/*else if($dice_sum == $moves){
                            if(strlen($pos_old_data)>0 & substr($pos_old_data,-1) == $status){
                                if(strlen($pos_new_data) == 0){
                                    // call sql func kai metakinise to
                                    return true;
                                }else if(strlen($pos_new_data) >= 1 & substr($pos_new_data,-1) == $status){
                                    // call sql func kai metakinise to
                                    return true;
                                }else if(strlen($pos_new_data) == 1 & substr($pos_new_data,-1) != $status){
                                    // call sql func kai metakinise to
                                    return true;
                                }
                        }

                        }*/

                }else if($status == "W" & $pos_new>$pos_old){
            
                    $moves = $pos_new-$pos_old;
                        if($diceA == $moves){
                            if(strlen($pos_old_data)>0 & substr($pos_old_data,-1) == $status){
                                if(strlen($pos_new_data) == 0){
                                    // call sql func kai metakinise to
                                    $stmt = $this->conn->prepare("call movePiece(?,?)");
                                    $stmt->bind_param("ii", $pos_old,$pos_new);
                                    $stmt->execute();
                                    $stmt->close();

                                    $stmt2 = $this->conn->prepare("call DiceAZero()");
                                    $stmt2->execute();
                                    $stmt2->close();
                                    return true;
                                }else if(strlen($pos_new_data) >= 1 & substr($pos_new_data,-1) == $status){
                                    // call sql func kai metakinise to
                                    $stmt = $this->conn->prepare("call movePiece(?,?)");
                                    $stmt->bind_param("ii", $pos_old,$pos_new);
                                    $stmt->execute();
                                    $stmt->close();

                                    $stmt2 = $this->conn->prepare("call DiceAZero()");
                                    $stmt2->execute();
                                    $stmt2->close();
                                    return true;
                                }else if(strlen($pos_new_data) == 1 & substr($pos_new_data,-1) != $status){
                                    // call sql func kai metakinise to
                                    $stmt = $this->conn->prepare("call movePiece(?,?)");
                                    $stmt->bind_param("ii", $pos_old,$pos_new);
                                    $stmt->execute();
                                    $stmt->close();

                                    $stmt2 = $this->conn->prepare("call DiceAZero()");
                                    $stmt2->execute();
                                    $stmt2->close();
                                    return true;
                                }
                        }

                        }else if($diceB == $moves){
                            if(strlen($pos_old_data)>0 & substr($pos_old_data,-1) == $status){
                                if(strlen($pos_new_data) == 0){
                                    // call sql func kai metakinise to
                                    $stmt = $this->conn->prepare("call movePiece(?,?)");
                                    $stmt->bind_param("ii", $pos_old,$pos_new);
                                    $stmt->execute();
                                    $stmt->close();

                                    $stmt2 = $this->conn->prepare("call DiceBZero()");
                                    $stmt2->execute();
                                    $stmt2->close();
                                    return true;
                                }else if(strlen($pos_new_data) >= 1 & substr($pos_new_data,-1) == $status){
                                    // call sql func kai metakinise to
                                    $stmt = $this->conn->prepare("call movePiece(?,?)");
                                    $stmt->bind_param("ii", $pos_old,$pos_new);
                                    $stmt->execute();
                                    $stmt->close();

                                    $stmt2 = $this->conn->prepare("call DiceBZero()");
                                    $stmt2->execute();
                                    $stmt2->close();
                                    return true;
                                }else if(strlen($pos_new_data) == 1 & substr($pos_new_data,-1) != $status){
                                    // call sql func kai metakinise to
                                    $stmt = $this->conn->prepare("call movePiece(?,?)");
                                    $stmt->bind_param("ii", $pos_old,$pos_new);
                                    $stmt->execute();
                                    $stmt->close();

                                    $stmt2 = $this->conn->prepare("call DiceBZero()");
                                    $stmt2->execute();
                                    $stmt2->close();
                                    return true;
                                }
                        }

                        }/*else if($dice_sum == $moves){
                            if(strlen($pos_old_data)>0 & substr($pos_old_data,-1) == $status){
                                if(strlen($pos_new_data) == 0){
                                    // call sql func kai metakinise to
                                    return true;
                                }else if(strlen($pos_new_data) >= 1 & substr($pos_new_data,-1) == $status){
                                    // call sql func kai metakinise to
                                    return true;
                                }else if(strlen($pos_new_data) == 1 & substr($pos_new_data,-1) != $status){
                                    // call sql func kai metakinise to
                                    return true;
                                }
                        }

                        }*/



                }  






              
            }else{
                return false;
               
            }


        }

        





        public function checkPosType($po,$pn){

            if(gettype($po) == 'integer' & gettype($pn) == 'integer'){
                if($po >= 1 & $po<=24 & $pn >= 1 & $pn<=24){
                    return true;
                }
                return false;
            }else{
                return false;
            }
               
        }

    
        public function checkDices(){
            $dice = new Dice($this->conn); 
            $result = $dice->lastDice('last');
            
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
             $this->dice1 = $dice_arr['data'][0]['DICE1'];
             $this->dice2 = $dice_arr['data'][0]['DICE2'];
             
        }
    
        public function readStatus(){
            $stat = new BoardStatus($this->conn);
            $result = $stat->showStatus(); 
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
                $this->status = $status_arr['data'][0]['p_turn'];
        }
    
    
        public function getaDataPos($pos){
            $result = $this->readPosition($pos);
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
            return $back_arr['data'][0]['piece'];


     }
    


        public function totalAvailMoves(){
            $this->checkDices();
            $this->readStatus();
            $diceA = $this->dice1;
            $diceB = $this->dice2;
            $status = $this->status;
            $board = $this->readBoard();

            $back_arr = array();
            $back_arr['data'] = array();
        
            while($row = $board->fetch_assoc()){
                extract($row);
                $back_item = array(
                    'x' => $x ,
                    'y' => $y ,
                    'pos' => $pos,
                    'piece' => $PIECE
                );
        
                array_push($back_arr['data'],$back_item);
                }
                
                $counter2=0;
                $counter3=0;
                $dice1=0;
                $dice2=0;
               // echo "Zaria A: " . " $diceA" . " " . "Zaria B: " . " $diceB" . "\n\n";

                if($status == 'B'){
                    for($i=23;$i>=0;$i--){

                        if($diceA>0){
                        $dice1= $back_arr['data'][$i]['pos'] - $diceA;}
                        if($diceB>0){
                        $dice2= $back_arr['data'][$i]['pos'] - $diceB;}
                      
                        if(strlen($back_arr['data'][$i]['piece'])>0 & substr($back_arr['data'][$i]['piece'],-1) == $status){
                          /*  echo "Status game: " .$status . " \n";
                            echo  "Zari A: " . $diceA . " \n";
                            echo  "Zari B: " . $diceB . " " . "\n";
                            echo  "Thesi pinaka: " . $back_arr['data'][$i]['pos'] . " \n";
                            echo  "Piece: " . $back_arr['data'][$i]['piece'] . " \n\n";*/
                           
                          
                           
                            
                            
                            if($dice1>0 & $dice1<24){
                                
                          

                            if(strlen($back_arr['data'][$dice1-1]['piece']) == 0){
                                $counter2++;
                                echo "Zari 1 - mporei na kounithei stin thesi: " . $back_arr['data'][$dice1-1]['pos'] . " \n";
                            }else if(strlen($back_arr['data'][$dice1-1]['piece']) == 1 & substr($back_arr['data'][$dice1-1]['piece'],-1) != $status){
                                echo "Zari 1 - mporei na kounithei stin thesi: " . $back_arr['data'][$dice1-1]['pos'] . " \n";
                                $counter2++;
                            }else if(strlen($back_arr['data'][$dice1-1]['piece']) >= 1 & substr($back_arr['data'][$dice1-1]['piece'],-1) == $status){
                                echo "Zari 1 - mporei na kounithei stin thesi: " . $back_arr['data'][$dice1-1]['pos'] . " \n";
                                $counter2++;
                            } }

                            if($dice2>0 & $dice2<24){

                            if(strlen($back_arr['data'][$dice2-1]['piece']) == 0){
                                $counter3++;
                                echo "Zari 2 - mporei na kounithei stin thesi: " . $back_arr['data'][$dice2-1]['pos'] . " \n";
                            }else if(strlen($back_arr['data'][$dice2-1]['piece']) == 1 & substr($back_arr['data'][$dice2-1]['piece'],-1) != $status){
                                echo "Zari 2 - mporei na kounithei stin thesi: " . $back_arr['data'][$dice2-1]['pos'] . " \n";
                                $counter3++;
                            }else if(strlen($back_arr['data'][$dice2-1]['piece']) >= 1 & substr($back_arr['data'][$dice2-1]['piece'],-1) == $status){
                                echo "Zari 2 - mporei na kounithei stin thesi: " . $back_arr['data'][$dice2-1]['pos'] . " \n";
                                $counter3++;
                            }   }





                            }
                        


                    }
                
                }else if($status == 'W'){
                   
                    for($i=0;$i<=23;$i++){
                            
                        if($diceA>0){
                        $dice1= $back_arr['data'][$i]['pos'] + $diceA;}

                        if($diceB>0){
                        $dice2= $back_arr['data'][$i]['pos'] + $diceB;}

                       
                          if(strlen($back_arr['data'][$i]['piece'])>0 & substr($back_arr['data'][$i]['piece'],-1) == $status){
                           /*   echo "Status game: " .$status . " \n";
                              echo  "Zari A: " . $diceA . " \n";
                              echo  "Zari B: " . $diceB . " " . "\n";
                              echo  "Thesi pinaka: " . $back_arr['data'][$i]['pos'] . " \n";
                              echo  "Piece: " . $back_arr['data'][$i]['piece'] . " \n\n";*/
                             
                             
  
                             
                              if($dice1>0 & $dice1<24){
                            
  
                              if(strlen($back_arr['data'][$dice1-1]['piece']) == 0){
                                  $counter2++;
                                  echo "Zari A : mporei na kounithei stin thesi: " . $back_arr['data'][$dice1-1]['pos'] . " \n";
                              }else if(strlen($back_arr['data'][$dice1-1]['piece']) == 1 & substr($back_arr['data'][$dice1-1]['piece'],-1) != $status){
                                  echo "Zari A : mporei na kounithei stin thesi: " . $back_arr['data'][$dice1-1]['pos'] . " \n";
                                  $counter2++;
                              }else if(strlen($back_arr['data'][$dice1-1]['piece']) >= 1 & substr($back_arr['data'][$dice1-1]['piece'],-1) == $status){
                                 echo "Zari A : mporei na kounithei stin thesi: " . $back_arr['data'][$dice1-1]['pos'] . " \n";
                                  $counter2++;
                              } }
  
                              
                              if($dice2>0 & $dice2<24){

                              if(strlen($back_arr['data'][$dice2-1]['piece']) == 0){
                                  $counter3++;
                                  echo "Zari B : mporei na kounithei stin thesi: " . $back_arr['data'][$dice2-1]['pos'] . " \n";
                              }else if(strlen($back_arr['data'][$dice2-1]['piece']) == 1 & substr($back_arr['data'][$dice2-1]['piece'],-1) != $status){
                                  echo "Zari B : mporei na kounithei stin thesi: " . $back_arr['data'][$dice2-1]['pos'] . " \n";
                                  $counter3++;
                              }else if(strlen($back_arr['data'][$dice2-1]['piece']) >= 1 & substr($back_arr['data'][$dice2-1]['piece'],-1) == $status){
                                  echo "Zari B : mporei na kounithei stin thesi: " . $back_arr['data'][$dice2-1]['pos'] . " \n";
                                  $counter3++;
                              } }
  
  
  
  
  
                              }
                          
  
  
                      }



                }
                        
                        echo "Kiniseis zariou A : " . $counter2 . " \n";
                        echo "Kiniseis zariou B : " . $counter3 . " \n\n";
                        $counters = array();
                        array_push( $counters ,$counter2);
                        array_push( $counters ,$counter3);
                        return  $counters;
                       // $this->movePiece($counters); 
        
        
            
            }
    
    
            public function updateStatusDice(){
                //create query
                $query1 = 'call first_next_dices()';
                $query = 'CALL change_turn()';
    
                //prepare statement
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stmt1 = $this->conn->prepare($query1);
                $stmt1->execute();
                return ;
            }
    
    
    
    
    }


    
    class Players{
        //db stuff
        private $conn;
        private $table = 'players';

        //players properties
        public $username;
        public $piece_color;
        public $auth;
       
        

        //constructor with db connection
        public function __construct($mysqli)
        {
            $this->conn = $mysqli;
        }



        //read all players from database
        public function showPlayers(){
            //create query
            $query = 'SELECT * FROM ' . $this->table;

            //prepare statement
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $res = $stmt ->get_result();
            
            return  $res;
        }



        //read one player from database
        public function onePlayer($p){
 
            $stmt = $this->conn->prepare("SELECT * FROM players WHERE PIECE_COLOR = ?");
            $stmt->bind_param("s", $p);

            $stmt->execute();
            $res = $stmt ->get_result();          
            $stmt->close();
            
            return  $res;
        }


         //put a player into database
         public function putPlayer(){
 
            $stmt = $this->conn->prepare("INSERT INTO PLAYERS(`USERNAME`,`PIECE_COLOR`,`AUTH`) VALUES (?,?,?)");
            $stmt->bind_param("sss",$this->username,$this->piece_color,$this->auth );
            if($stmt->execute()){
                return true;
            }
            $stmt->close();
            
        }

        function set_name($username) {
            $this->username = $username;
          }

        function set_color($piece_color) {
            $this->piece_color = $piece_color;
          }

        function set_auth($auth) {
            $this->auth = $auth;
          }


        function deletePlayer(){
            $stmt = $this->conn->prepare("DELETE FROM PLAYERS WHERE PIECE_COLOR = ?");
            if($this->piece_color == null){
                return false;
            }
            $stmt->bind_param("s",$this->piece_color);
            if($stmt->execute()){
                return true;
            }
            $stmt->close();

        }

    }


    class BoardStatus{
        //db stuff
        private $conn;
        private $table = 'game_status';

        //board status properties
        public $status;
        public $p_turn;
        public $result;
        public $last_action;
        

        //constructor with db connection
        public function __construct($mysqli)
        {
            $this->conn = $mysqli;
        }



        //read the whole board from database
        public function showStatus(){
            //create query
            $query = 'SELECT * FROM ' . $this->table;

            //prepare statement
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $res = $stmt ->get_result();
            
            return  $res;
        }

        public function changeStatus(){
                 $query = 'CALL change_turn()';
                 $stmt = $this->conn->prepare($query);
                 $stmt->execute();
                 $stmt->close();
                 return true;

        }

    }


    class Dice{
        //db stuff
        private $conn;
        private $table = 'dice_history';

        //board status properties
        public $id;
        public $username;
        public $dice1;
        public $dice2;
        public $time;
        

        //constructor with db connection
        public function __construct($mysqli)
        {
            $this->conn = $mysqli;
        }



        //read the whole board from database
        public function showDice(){
            //create query
            $query = 'SELECT * FROM ' . $this->table;
            $query2 = 'CALL first_next_dices()';
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->execute();
            //prepare statement
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $res = $stmt ->get_result();
            
            return  $res;
      
        }



        
        //read the whole board from database
        public function lastDice($dices){
            if($dices == 'last'){
            //create query
            $query = 'CALL get_last_dice()';

            //prepare statement
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $res = $stmt ->get_result();
            return  $res;
            }else{
                return false;
            }
        }

    }



?>