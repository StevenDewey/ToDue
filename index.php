<?php
	require_once 'todueQueries.php'; # Make sure you have the needed Class file
	$myList = new TodoList;
?><!doctype html>
<html>
	<head>
		<meta charset="utf-8" />
		
		<link rel="stylesheet" href="styles.css" />

		<title>To do List</title>
	</head>
	
	<body>
	<div id="mainContent">
		<h1>To Do List</h1>
		<?php
			# Check for success message trigger and display accordingly
			
			if( isset($_GET["success"]) ):
				switch($_GET["success"]):
					case "add":
						echo "<p class='success'>To do item added successfully.</p>";
						break;
						
					case "delete":
						echo "<p class='success'>To do item removed successfully.</p>";
						break;
						
					case "edit":
						echo "<p class='success'>Priority level updated successfully.</p>";
						break;

					case "updateComplete":
						echo "<p class='success'>Completed status updated successfully.</p>";
						break;
				endswitch;
			endif;


			$myList = new TodoList; # Instantiate an Object using our PotionShop Class
			$myList->displayTodoList(); # Call the displayPotions method through the new Object
		?>
		</div>
	</body>
</html>