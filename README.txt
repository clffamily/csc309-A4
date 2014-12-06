csc309-A4
=========

CDF accounts: 
	g2jarmst, c4cailio


General Information:
	1. AMI ID: ami-ba2fb5d2
	2. Location within AMI: /var/www/html/connect4
	3. Browser: Latest version of Chrome
	
	There are two users in the database.
		a) username: admin1 password: testing
		b) username: admin2 password: testing
	Feel free to create more accounts.


Description:
	Essentially, there are limited user defined objects. The user who invites another user is,
	by courtesy, player 2, the invitee is player 1. The user is able to declare his or her move by
	hovering the cursor over each column of the board, and then clicking on the column where they wish 
	to drop his or her game piece. After each move a function checks whether the board is in a 
	winning state or not. If after a player moves and the move is not a winning move, then the next 
	player is given an opportunity to make a move. So, the game progesses, back and forth in this manner, 
	until we are in a winning state, at which point no more gameplay is allowed, and a win status is sent 
	to the database.
	
	Our UI is constructed using a mixture of bootstrap templates and css animation. For gameplay we used
	css animation to mimic the dropping of pieces that occurs during a real-life game of connect 4. This
	involved setting up an array of div objects that were sized to squares with grey backgrounds and 
	transparent circles. Then when a move would be made an animated element could be dropped behind the board
	and finish animating in the appropriate spot. At the end of the animation the data structure of the board,
	would be updated, and the rendering of the board completed. This gave the illusion that a move was made in
	game play. 


Data Structure:
	There are two essential structures that allow the UI to have the appearance of a functioning game.
	These are the array structures of the game board and the game state. The board keeps track of moves.
	and the game state allows for moves to be transmitted between users when the game board is continuously
	updated.

	Game Board  	A 6 x 7 array, where each element is set to -1 to indicate no move. If a move was made 
			an appropriate element is set to either 1 or 2, to declare a move made by player 1 or player 2
		     	respectively. This is used to indicate the player that the piece occupying the game board
		     	belongs to.
		     	
	Game State	The game state is a one dimensional array, that contains the current player's move,
			the current player's number, the next player's number, and the current game board array
			before the move was made. This is passed to the database, as a JSON string, and then
			retrieved by Get in order to animate the move, get the next player's id, change the game board,
			and determine if a win has occured.
