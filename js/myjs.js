var getStatusContinius;
var status;
var color=null;
var username;


$(document).ready(function(){
    $("#moves").hide();
    $("#doMove").click(domove);
    $("#startGame").click(gameEnter);
    $("#resetGame").click(gameReset);
    $("#refresh").click(endTurn);
    initBoard();
    var getstatus = setInterval(getStatus,5000);
    setTimeout(rollFirstDice, 10000);
    setInterval(getlastdice,5000);
    setInterval(initBoard,5000);
    

  });


  function domove(){
    
    pos_o = $("#oldPos").val();
    pos_n = $("#newPos").val();
    pos_o = parseInt(pos_o);
    pos_n = parseInt(pos_n);
    

    var data = {
        "pos_old":pos_o,
        "pos_new":pos_n
    };
    
    $.ajax({
        url: "api/board.php",
        method:"PUT",  
        contentType: 'application/json',
        data: JSON.stringify(data),
       // success: getStatus
      });


  }



  function endTurn(){
    var data = {
        "pos_old":0,
        "pos_new":0};
    
    $.ajax({
        url: "api/board.php",
        method:"PUT",  
        contentType: 'application/json',
        data: JSON.stringify(data),
       // success: getStatus
      });

  }

  function gameEnter(){
       username = $("#username").val();
      color = $("#selectedColor").val();
      if(username == "" || username==null){
          return alert("Must be enter a username!!!");
      }
      var token = Math.random();
      token = token.toString();
      token = token.slice(2,-1);
      token = token+username;
      putPlayer(username,color,token);
      if(color == "W"){
          color = "White";
      }else{color = "Black";}
      $("#playerName")[0].textContent = username;
      $("#alerttext")[0].textContent = "Success : " + username + " you entered the game as " + color;
      $("#alert").show();
     // var getstatus = setInterval(getStatus,5000);
      
    // $('#moves').show(2000);

  }



    function putPlayer(user,color,token){
        var data = {
            "username":user,
            "piece_color":color,
            "auth":token };
        
        $.ajax({
            url: "api/players.php",
            method:"PUT",  
            contentType: 'application/json',
            data: JSON.stringify(data),
           // success: getStatus
          });

    }



    function getStatus(){
        $.ajax({
            url: "api/status.php",
            method:"GET",  
            success: showStatus
          });
    }

    function showStatus(data){
        $("#gameStatus")[0].textContent = data.data[0].status;
        $("#playerTurn")[0].textContent = data.data[0].p_turn;
        var mycolor = color.slice(0,1);

        if(data.data[0].p_turn == mycolor){
            $('#moves').show(2000);
        }else{
            $("#moves").hide();
        }


    }


    function rollFirstDice(){
        $.ajax({
            url: "api/dices.php",
            method:"GET",
            success: getlastdice
          });
    }

    function  getlastdice(){
        $.ajax({
            url: "api/dices.php",
            method:"GET",
            data:{d:'last'},
            success: showlastdice
          });
    }

    function showlastdice(data){
        if(color =='White'){
           $("#dart1")[0].textContent = data.data[0].DICE1;
           $("#dart2")[0].textContent = data.data[0].DICE2;
        }else{
           $("#dart1")[0].textContent = data.data[0].DICE1;
           $("#dart2")[0].textContent = data.data[0].DICE2;
        }


    }

  
  function gameReset(){
   // console.log("Game is reseting..");
    $.ajax({
        url: "api/board.php",
        method:"post",  
        success: fillBoard
      });
  }



  function initBoard(){
  //    console.log("Initalizing board..");
      $.ajax({
        url: "api/board.php",  
        success: fillBoard
      });
  }

  function fillBoard(data){
   // console.log("filling board by data");
    var obj = data.data;
   // console.log(obj);
    var board = $("#board")[0];
    var i,j;
    var arr1 = [];
    var arr2 = [];
    counter1 = 0;
    counter2 = 0;


        for(i=12;i<=23;i++){
            arr1[counter1] = obj[i].piece;
            counter1++;
        }
        

        for(i=11;i>=0;i--){
            arr2[counter2] = obj[i].piece;
            counter2++;
        }

        for(i=0;i<12;i++){
            board.rows[0].cells[i].textContent= arr1[i];
           

        }

        for(i=0;i<12;i++){
            board.rows[1].cells[i].textContent= arr2[i];
            
        }


  }