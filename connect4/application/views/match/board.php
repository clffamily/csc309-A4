
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
		var player = -1;

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
            		player = gameArray[i][j];
            
            		if (player != -1) {
 
                		//horizontal win
                		if ((i + 3) < 7) {
                    		if ((player == gameArray[i + 1][j]) &&
                        		(player == gameArray[i + 2][j]) &&
                        		(player == gameArray[i + 3][j])) {
                        		return player;
                    		}
                		}
                
                		//vertical win
                		if ((j + 3) < 6) {
                    		if ((player == gameArray[i][j + 1]) &&
                        		(player == gameArray[i][j + 2]) &&
                        		(player == gameArray[i][j + 3])) {
                        		return player;
                    		}
                		}
                
		                //right diagonal win
		                if (((i + 3) < 7) && ((j + 3) < 6)) {
		                    if ((player == gameArray[i + 1][j + 1]) &&
		                        (player == gameArray[i + 2][j + 2]) &&
		                        (player == gameArray[i + 3][j + 3])) {
		                        return player;
		                    }
		                }
		                
		                //left diagonal win
		                if (((i - 3) >= 0) && ((j - 3) >= 0)) {
		                    if ((player == gameArray[i - 1][j - 1]) &&
		                        (player == gameArray[i - 2][j - 2]) &&
		                        (player == gameArray[i - 3][j - 3])) {
		                        return player;
		                    }
		                }
		            }
		        }
		    }
		    return player;
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
			//sets the player value
			player = <?php echo $player ?>;

			$('body').everyTime(200,function(){
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
				var url = "<?= base_url() ?>board/getState";
				$.getJSON(url, function (data,text,jqXHR){
					if (data && data.status=='success' && data.state != null) {
						//alert(data.state);
						gameArray = $.parseJSON(data.state);
						drawBoard(gameArray);
					}
				});
			});
			
			$('.col').each(function() {
		        empty_str = '<div class="canhover">1</div><div class="empty"></div>';
		        html_str = empty_str;
		        for (var i = 0; i <= 5; i++) {
		            cutout_str = '<div class="cutout' + i +'"></div>';
		            html_str = html_str + cutout_str;
		        }
		        $(this).html(html_str);
		    });
		    
		    drawBoard(gameArray);
		    
		    $('.col').hover(
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
		         function () {
		            if ($(this).find('.canhover').html() == 1) {
		                $(this).find('.empty').css({"background-color":"white"});
		            }
		         }
		     );
		     
		     $('.col').click(function() {
		        colnum = parseInt($(this).attr('id').substring(3, 4));
		        cutoutnum = cutoutPos(colnum, gameArray);
		        
		        if (cutoutnum != -1) {
		            gameArray[colnum][cutoutnum] = player;
		            $('.col').each(function() {
		                $(this).find('.canhover').html('0');
		            });
		            
		            //$(this).find('.empty').css({"background-color":"red"});
		            $(this).find('.empty').css({"-webkit-animation":"drop-down" + 
		                                   cutoutnum + " " + (cutoutnum + 1) + "s"});
		        }
				var url = "<?= base_url() ?>board/postState";
				$.post(url,"data=" + JSON.stringify(gameArray), function (){
				});
				return false;
		     });
		     
		     $(".col").bind("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd", function(){
		        $(this).find('.cutout5').css({"background-color":"red"});
		        $(this).find('.empty').css({"background-color":"white"});
		        $(this).find('.empty').css({"-webkit-animation":""});
		        drawBoard(gameArray);
		        $('.col').each(function() {
		            $(this).find('.canhover').html('1');
		        });
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

