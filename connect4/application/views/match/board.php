
<!DOCTYPE html>

<html>
<head>
<link rel="stylesheet" href="<?= base_url()?>css/board.css">
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="<?= base_url() ?>/js/jquery.timers.js"></script>
<script>

		var otherUser = "<?= $otherUser->login ?>";
		var user = "<?= $user->login ?>";
		var status = "<?= $status ?>";
		var player = <?= $player ?>;
		var gamestatus = <?= $matchstatus ?>;

		// controls animation in order to avoid drawing the board while animating
		var animDone = true;

		// so that no more further moves are made after the completion of a game
		var gameOver = false;

		// initializes oldMove which is used in comparing the getJSON values,
		// to determine if animation is necessary
		var oldMove = [-1, -1];
		
		var currentPlayer = 1;
		
		//Controls/produces graphical representation of a connect4 game as contained in the
		// array gameArray
		function drawBoard(gameArray) {
   			for (var i=0; i<7; i++) {
        		for (var j=0; j<6; j++) {
            		if (gameArray[i][j] == 1) {
                		colour = "blue";
                		col_id = "#col" + i;
                		cutout_class =".cutout" + j;
                		$(col_id).find(cutout_class).css({"background-color":colour});
            		}
            		else if (gameArray[i][j] == 2) {
                		colour = "red";
                		col_id = "#col" + i;
                		cutout_class =".cutout" + j;
                		$(col_id).find(cutout_class).css({"background-color":colour});
            		}
        		}
    		}
		}

		// Return the player's number who has won the game. Otherwise return -1.    
		function isWin(gameArray) {
    		for (var i=0; i<7; i++) {
        		for (var j=0; j<6; j++) {
            		gameplayer = gameArray[i][j];
            
            		if (gameplayer != -1) {
 
                		//horizontal win
                		if ((i + 3) < 7) {
                    		if ((gameplayer == gameArray[i + 1][j]) &&
                        		(gameplayer == gameArray[i + 2][j]) &&
                        		(gameplayer == gameArray[i + 3][j])) {
                        		return gameplayer;
                    		}
                		}
                
                		//vertical win
                		if ((j + 3) < 6) {
                    		if ((gameplayer == gameArray[i][j + 1]) &&
                        		(gameplayer == gameArray[i][j + 2]) &&
                        		(gameplayer == gameArray[i][j + 3])) {
                        		return gameplayer;
                    		}
                		}
                
		                //right diagonal win
		                if (((i + 3) < 7) && ((j + 3) < 6)) {
		                    if ((gameplayer == gameArray[i + 1][j + 1]) &&
		                        (gameplayer == gameArray[i + 2][j + 2]) &&
		                        (gameplayer == gameArray[i + 3][j + 3])) {
		                        return gameplayer;
		                    }
		                }
		                
		                //left diagonal win
		                if (((i + 3) < 7) && ((j - 3) >= 0)) {
		                    if ((gameplayer == gameArray[i + 1][j - 1]) &&
		                        (gameplayer == gameArray[i + 2][j - 2]) &&
		                        (gameplayer == gameArray[i + 3][j - 3])) {
		                        return gameplayer;
		                    }
		                }
		            }
		        }
		    }
		    // no win
		    return -1;
		}

		//Return true if gameArray has only non negative values, else return false.
		function isFull(gameArray) {
			for (var i=0; i<7; i++) {
        		for (var j=0; j<6; j++) {
            		if (gameArray[i][j] == -1) {
                		return false;
            		}
        		}
			}
			return true;
		}
		
		// Return position value of available cutout spot in game board given
		// column position value. If no space is available return -1
		function cutoutPos (colnum, gameArray) {
		    for (var i = 5; i>=0; i--) {
		        if (gameArray[colnum][i] == -1) {
		            return i;
		        }
		    }
		    return -1;
		}
		
		//make game array
		gameArray = new Array(7);
		for (var i=0; i<7; i++) {
		    gameArray[i] = new Array(6);
		    for (var j=0; j<6; j++) {
		        gameArray[i][j] = -1;
		    }
		}
		
		$(function(){
			
			
			//check if game already over, this is only necessary if the browser is refreshed
			if (gamestatus != 1) {
				gameOver = true;
				
			}
			
			//sets the player value
			player = <?php echo $player ?>;

			$('body').everyTime(100,function(){
				if (status == 'waiting') {
					$.getJSON('<?= base_url() ?>arcade/checkInvitation',function(data, text, jqZHR){
							if (data && data.status=='rejected') {
								alert("Sorry, your invitation to play was declined!");
								window.location.href = '<?= base_url() ?>arcade/index';
							}
							if (data && data.status=='accepted') {
								status = 'playing';
								$('#status').html('Playing ' + otherUser);
							}
							
					});
				}
				
				var url = "<?= base_url() ?>board/getMsg";
				$.getJSON(url, function (data,text,jqXHR){
					if (data && data.status=='success') {
						var conversation = $('[name=conversation]').val();
						var msg = data.message;
						if (msg.length > 0)
							$('[name=conversation]').val(conversation + "\n" + otherUser + ": " + msg);
					}
				});
				
				//Indicates that it's player's move allow hovering over columns and choosing a move
				if (currentPlayer == player && animDone && (isWin(gameArray) == -1)) {
					$('.col').each(function() {
			            $(this).find('.canhover').html('1');
			        });
				}

				//Remove capability of gameplay, and remove hovering on column divs
				else {
					$('.col').each(function() {
						if (animDone) {
							$(this).find('.empty').css({"background-color":"white"});
						}
			            $(this).find('.canhover').html('0');
			        });
				} 
				
				var url = "<?= base_url() ?>board/getState";
				$.getJSON(url, function (data,text,jqXHR){
					if (data && data.status=='success' && data.state != null) {
						
							//state info stored in database as blob converted back to array
							state = $.parseJSON(data.state);
							currentMove = state[0];
							gameArray = state[2];

							//set player's move after drawing board because we want the move to be
							//animated.
							drawBoard(gameArray);
							colnum = currentMove[0];
							cutoutnum = currentMove[1];
							gameArray[colnum][cutoutnum] = state[3];

							//if the move that was received differs from the old move, then animate the move
							if ((currentMove[0] != oldMove[0] || currentMove[1] != oldMove[1]) && !gameOver) {
								oldMove = currentMove;
								if (state[3] == 1) {
									colour = 'blue';
								}
								else {
									colour = 'red';
								}

								//css animation
								$('#col'+ colnum).find('.empty').css({"background-color":colour});
								$('#col'+ colnum).find('.empty').css({"-webkit-animation":"drop-down" + 
	        	                    cutoutnum + " " + (cutoutnum + 1)/30 + "s"});

        	                    animDone = false;
							}
											 
							if (animDone) {
								//switch the player to the next player
								currentPlayer = state[1];
								drawBoard(gameArray);
							}	
					}
				});

				//Update match status
				if (!gameOver) {
					if (player == currentPlayer) {
						$('#matchstatus').html("It's your turn!");
					}
					else{
						$('#matchstatus').html("It's " + otherUser + "'s turn!");
					}
				}

				//If there is a win update the match status according to the type of win
				if (isWin(gameArray) != -1 || isFull(gameArray)) {
					winner = isWin(gameArray);
					gameOver = true;
					if (isFull(gameArray)) {
						entry = 4;
						$('#matchstatus').html("It's a tie!");
					}
					else {
						
						if (player == winner) {
							$('#matchstatus').html("You won!");
						}
						else{
							$('#matchstatus').html("You lost!");
						}
						
						if (winner == 1) {
							entry = 2;
						}
						else if (winner == 2) {
							entry = 3;
						}
					}
					url = "<?= base_url() ?>board/postStatus";

					//pass the type of win to controller board's function postStatus
					//to add the status to the database
					$.post(url,"status=" + entry, function (){
					});
				}
			});

			//Essentially drawing the empty connect4 game space
			$('.col').each(function() {

				//in the canhover class there is a hidden value used to standin for a varibale
				//to indicate which columns the player is allow to hover over, if 1 player can hover
				//otherwise he/she can't hover.
		        empty_str = '<div class="canhover">1</div><div class="empty"></div>';
		        html_str = empty_str;
		        for (var i = 0; i <= 5; i++) {
		            cutout_str = '<div class="cutout' + i +'"></div>';
		            html_str = html_str + cutout_str;
		        }
		        $(this).html(html_str);
		    });
		    
		    $('.col').hover(
				    
				 //mouseover   
		         function () {
		            if ($(this).find('.canhover').html() == 1) {
			            if (player == 1) {
			                $(this).find('.empty').css({"background-color":"blue"});
			            }
			            else {
			            	$(this).find('.empty').css({"background-color":"red"});
			            } 
		            }
		         }, 
		         
		         //mouseout
		         function () {
		            if ($(this).find('.canhover').html() == 1) {
		                $(this).find('.empty').css({"background-color":"white"});
		            }
		         }
		     );
		     
		     $('.col').click(function() {

			    //Only the current player can click on a column add make a move
		    	if (currentPlayer == player && !gameOver) {
			        colnum = parseInt($(this).attr('id').substring(3, 4));
			        cutoutnum = cutoutPos(colnum, gameArray); 
			        if (cutoutnum != -1) {
			        	animDone = false;
				        nextMove = [colnum, cutoutnum];
				        if (currentPlayer == 1) {
							nextPlayer = 2;
				        }
				        else {
							nextPlayer = 1;
				        }
			            $('.col').each(function() {
			                $(this).find('.canhover').html('0');
			            });
			        	state = [nextMove, nextPlayer, gameArray, currentPlayer];
						url = "<?= base_url() ?>board/postState";

						//state object passed as a string to controller board's function
						//postState to add the game state to the database
						$.post(url,"data=" + JSON.stringify(state), function (){
						});
						newGame = false;
						return false;
			        }
		    	}
		     });

		     //waits for completion animation on class col.
		     $(".col").bind("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd", function(){
		        $(this).find('.empty').css({"background-color":"white"});
		        $(this).find('.empty').css({"-webkit-animation":""});
		        drawBoard(gameArray);
		        animDone = true;
		     });

			$('form').submit(function(){
				var arguments = JSON.stringify(gameArray);
				var arguments = "msg=" + $('[name=msg]').val();
				var url = "<?= base_url() ?>board/postMsg";
				$.post(url,arguments, function (data,textStatus,jqXHR){
						var conversation = $('[name=conversation]').val();
						var msg = $('[name=msg]').val();
						$('[name=conversation]').val(conversation + "\n" + user + ": " + msg);
						});
				return false;
				});	
		});
	 
</script>
</head>
<body>
	<h1>Game Area</h1>

	<div>
	Hello <?= $user->fullName() ?>  <?= anchor('account/logout','(Logout)') ?>  
	</div>

	<div id='status'> 
	<?php 
		if ($status == "playing")
			echo "Playing " . $otherUser->login;
		else
			echo "Wating on " . $otherUser->login;
	?>
	</div>
	<div id='matchstatus'>
	</div>
	
<?php 
	
	echo form_textarea('conversation');
	
	echo form_open();
	echo form_input('msg');
	echo form_submit('Send','Send');
	echo form_close();
	
?>
<div class="col" id="col0">
</div>
<div class="col" id="col1">
</div>
<div class="col" id="col2">
</div>
<div class="col" id="col3">
</div>
<div class="col" id="col4">
</div>
<div class="col" id="col5"> 
</div>
<div class="col" id="col6">
</div>
</body>

</html>

