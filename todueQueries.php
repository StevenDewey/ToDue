<?php
	class TodoList {
		# Establish variables for internal-only use
		private $database;
		private $TodoList;
		private $TodoInfo;

		# This method is automagically called when you create a new Object using this Class
		public function __construct() {
			# New mysqli Object for database communication
			$this->database = new mysqli("localhost","tardissh_sdew","3701!","tardissh_dewey");

			# Kill the page is there was a problem with the database connection
			if ( $this->database->connect_error ):
				die( "Connection Error! Error: " . $this->database->connect_error );
			endif;
		}

		# Get all the information for a single potion
		public function singleListItem($ID) {
			$query_singleListItem = "
				SELECT 
					ID,description,complete,priority
				FROM
					ToDo
				WHERE
					ID=?
				LIMIT 1
			";
			
			if ( $TodoList = $this->database->prepare($query_singleListItem) ):
				 $TodoList->bind_param(
				 	'i',
				 	$ID
				 );
				 
				 $TodoList->execute();
				 
				 $TodoList->bind_result($ID,$description,$complete,$priority);
				 
				 $TodoList->fetch();
				 
				 $TodoInfo["ID"] = $ID;
 				 $TodoInfo["description"] = $description;
 				 $TodoInfo["complete"] = $complete;
 				 $TodoInfo["priority"] = $priority;
								 
				 $TodoList->close();
				 
				 return $TodoInfo;
			endif;
		}
	
		# Get the information for all potions in the database
		public function allListItems() {
			# Pre-define our select query
			$query_allListItems = "
				SELECT 
					ID,description,complete,priority
				FROM
					ToDo
				ORDER BY priority, complete

				
			";

			# If the query from above prepares properly, execute it
			# Else, show an error message
			if ( $this->TodoList = $this->database->prepare($query_allListItems) ):
				$this->TodoList->execute();
			else:
				die ( "<p class='error'>There was a problem executing your query</p>" );
			endif;
		}
		
		# Take all the potions and display them to the screen
		public function displayTodoList() {
			$this->allListItems();

			# Storing the result gives us access to several specialized properties
			$this->TodoList->store_result();

			# Bind the fields for each returned record to local variables that we name
			$this->TodoList->bind_result($ID,$description,$complete,$priority);

			# If the database is empty, show a message accordingly
			if ( $this->TodoList->num_rows == 0 ):
				echo "
					<table>
						<tr>
							<td colspan='6' class='error'>
								<p>No potions currently found in stock at this time.</p>
							</td>
						</tr>
						<tr>
							<td colspan='6'>
								<form action='store-room.php' method='post'>
									<input type='hidden' name='activity' value='add_potion' />

									<input type='submit' name='btn_add' class='btn_action' value='Add To Do Item' />
								</form>
							</td>
						</tr>
					</table>
				";
			else:
				# Show all the potions
				echo "
					<form action='store-room.php' method='post'>
					<table>
						
						<tr>
							
							<th>Description</th>
							<th>Priority</th>
							<th>Completed?</th>
							<th>Delete</th>
						</tr>
				";

				# Grabbing one potion record at a time display its respective information
				while( $this->TodoList->fetch() ):
						if ($priority == 1) {
								$priorityDisplay = "High";
								$priorityDisplayValue = "High";
							}
						else if ($priority == 2) {
							$priorityDisplay = "Medium";
							$priorityDisplayValue = "Medium";
						}
						else if ($priority == 3) {
							$priorityDisplay = "Low";
							$priorityDisplayValue = "Low";
						}
						if ($complete == 0) {
								$completeDisplay = "Incomplete";
							}
						else if ($complete == 1) {
							$completeDisplay = "Complete";
							$priorityDisplay = "Complete";
						}	
							echo "
						<tr class='$priorityDisplay'>
							
							<td class='description'>$description</td>
							
							<td class='priority'>
								<form action='store-room.php' method='post'>
									<input type='hidden' name='ID' value='$ID'/>
									<input type='hidden' name='priority' value='$priority'/>
									<input type='submit' class='$priorityDisplay' name='btn_action' class='btn_action delete' value='$priorityDisplayValue'/>
								</form>
							</td>
							<td>
								<form action='store-room.php' method='post'>
									<input type='hidden' name='ID' value='$ID'/>
									<input type='hidden' name='complete' value='$complete'/>
									<input type='submit' class='$priorityDisplay' name='btn_action' class='btn_action delete' value='$completeDisplay'/>
								</form>
							</td>
							<td class='completed'>
								<form action='store-room.php' method='post'>
									<input type='hidden' name='ID' value='$ID' />
									<input type='submit' class='$priorityDisplay' name='btn_action' class='btn_action delete' value='Delete'/>
								</form>
							</td>
							</tr>";

				endwhile;

				echo "
					<tr>
						<form action='store-room.php' method='post'>
							<td><input placeholder='What do you need to do?' id='addDescription' class='default' type=\"text\" name=\"Description\" /></td>
							<td>
								<select name=\"Priority\" />
									<option value=1 >High</option>
									<option value=2 >Medium</option>
									<option value=3 >Low</option>
								</select>
							</td>
							<td>
								<select name=\"Completed\" />
									<option value=0>No</option>
									<option value=1>Yes</option>
								</select>
							</td>
							<td><input class='default submit' type=\"submit\" name='btn_action' value=\"Add Item\"/></td>
						</form>
					</tr>
				</table>
				</form>


				";

				# Close out the prepared statement
				$this->TodoList->close();
			endif;
		}

		public function addTodoItem($description, $complete, $priority) {
			# Template for our insert query
			$insert_query = "
				INSERT INTO
					ToDo
					(description, complete, priority)
				VALUES
					(?, ?, ?)
			";

			# If the query prepares properly, send the record in to the database
			if ( $newTodoItem = $this->database->prepare($insert_query) ):
				
				# First argument is the data types for each piece of information
				# Second argument is the data itself
				$newTodoItem->bind_param(
					'sii',
					$description, $complete, $priority
				);
				
				$newTodoItem->execute();
				
				# Close out the prepared statement
				$newTodoItem->close();
				
				# Return the index page, using the GET to supply a message
				header("location: index.php?success=add");
			endif;
		}

		#Update Complete
		public function updateComplete($ID, $complete) {
			$update_query = "
				UPDATE
					ToDo
				SET
					complete=?
					
				WHERE
					ID=?
				LIMIT 1
			";
			echo "1";
			if ( $complete_update = $this->database->prepare($update_query) ):
				echo "2";
				$complete_update->bind_param(
					'ii',
					$complete, $ID
				);
				
				$complete_update->execute();
				
				$complete_update->close();
				
				header("location: index.php?success=updateComplete");
			endif;
		}

		#Update Priority
		public function updatePriority($ID, $priority) {
			$update_query = "
				UPDATE
					ToDo
				SET
					priority=?
					
				WHERE
					ID=?
				LIMIT 1
			";
			echo "1";
			if ( $priority_update = $this->database->prepare($update_query) ):
				echo "2";
				$priority_update->bind_param(
					'ii',
					$priority, $ID
				);
				
				$priority_update->execute();
				
				$priority_update->close();
				
				header("location: index.php?success=edit");
			endif;
		}

		# Edit an existing potion
		public function editListItem($ID, $description, $complete, $priority) {
			$update_query = "
				UPDATE
					ToDo
				SET
					description=?,
					complete=?,
					priority=?
					
				WHERE
					ID=?
				LIMIT 1
			";
			echo "1";
			if ( $ListItem_update = $this->database->prepare($update_query) ):
				echo "2";
				$ListItem_update->bind_param(
					'siii',
					$description, $complete, $priority, $ID
				);
				
				$ListItem_update->execute();
				
				$ListItem_update->close();
				
				header("location: index.php?success=edit");
			endif;
		}

			# Delete an existing potion from the database
		public function removeListItem($ID) {
			$delete_query = "
				DELETE FROM
					ToDo
				WHERE 
					ID=?
				LIMIT 1
			";
			
			if ( $ListItemRemoval = $this->database->prepare($delete_query) ):
				$ListItemRemoval->bind_param(
					'i',
					$ID
				);
				
				$ListItemRemoval->execute();
				
				$ListItemRemoval->close();
				
				header("location: index.php?success=delete");
			endif;
		}
	}
	
?>