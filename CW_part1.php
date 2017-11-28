<?php

include '/var/www/html/home.html';

//connect to the DB for the first VM
try{
$conn = new PDO('mysql:host=localhost;dbname=CloudCourseWork;charset=utf8', 'root', 'Ganesha01$');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch (Exception $e){
die('Erreur : '. $e->getMessage());
}

//connect to the clone of the VM
try{
$conn2 = new PDO('mysql:host=192.168.56.102;dbname=CloudCourseWork;charset=utf8', 'hostVM', 'Ganesha01$');
$conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch (Exception $e){
die('Erreur : '. $e->getMessage());
}

//Web page

//The user form
echo "<div class='form-style-6'>
		<h1>Company Management</h1>
		<form enctype='multipart/form-data' action='CW_part1.php' method='get'>
			<label for='employeeId'>Employee ID :</label>
			<input type='text' id='employeeId' name='empID' placeholder='Employee ID' />
			<label for='sizeGT'>Size Greater Than :</label>
			<input type='number' id='sizeGT' name='sizeGT' min='0' placeholder='Size GT' />
			<label for='sizeLT'>Size Lower Than :</label>
			<input type='number' id='sizeLT' name='sizeLT' min='0' placeholder='Size LT' />
			<input type='submit' id='send' name='send' value='Send' />
		</form>

		
	</div>";

//Once the user click the "Send" button
if (isset($_GET["send"])){
	
	if ($_GET['empID'] != ""){

		//There is no meaning if the user enter the Employee ID and the size
		if (($_GET['sizeGT'] != null) || ($_GET['sizeLT'] != null)){

			echo "Request Impossible!!!";
			echo "<br>";
			echo "Please select either the employee ID or the size.";
			
		}

		else {

			$sql3 = "SELECT * FROM Employee WHERE EmployeeId = ".$_GET['empID'];
			$res3 = $conn->query($sql3);

				//for every element (here Employee) in the table for the result3
				while($row3 = $res3->fetch(PDO::FETCH_ASSOC)){

					$sql4 = "SELECT Descr FROM Department WHERE Dcode = ".$row3["Dcode"];
					$res4 = $conn2->query($sql4);

					echo "<table id='table1'>";
					
					//Get the description for the Employee
					foreach ($res4 as $Descr){

						echo "<tr><th>Employee ID</th>";
						echo	"<th>Name</th>";
						echo    "<th>Grade</th>";
						echo    "<th>Description</th></tr>";
						echo "<tr><td>".$row3["EmployeeId"]."</td>";
						echo    "<td>".$row3["Name"]."</td>";
						echo    "<td>".$row3["Grade"]."</td>";
						echo    "<td>".$Descr[0]."</td></tr>";			
					}

					echo "</table>";	
				}
							
		}	
		
	}
	//if the EmployeeId is not entered by the user -> case with just the size
	else{

		if(($_GET['sizeGT'] != null) && ($_GET['sizeLT'] != null)){

			$sql1 = "SELECT * FROM Department WHERE Size > ".$_GET['sizeGT']." AND Size < ".$_GET['sizeLT'];
			$res1 = $conn2->query($sql1);
			$array1 = array();
			
			//Get appropriate departements (Dcode) according to the size entered by the user
			while($row1 = $res1->fetch(PDO::FETCH_ASSOC)){

				array_push($array1, $row1["Dcode"]); 
			}
			
			$array_size = count($array1);

			//Check if there are some outputs
			if ($array_size != 0){

				echo "<table id='table2'>";
				echo "<tr><th>Employee ID</th>";
				echo	"<th>Name</th>";
				echo    "<th>Grade</th></tr>";
			
				for($i=0; $i<$array_size; $i++){
			
					$sql2 = "SELECT * FROM Employee WHERE Dcode = $array1[$i]";
					$res2 = $conn->query($sql2);
				
					//Get the employees who are in the departments above
					while($row2 = $res2->fetch(PDO::FETCH_ASSOC)){

						echo "<tr><td>".$row2["EmployeeId"]."</td>";
						echo    "<td>".$row2["Name"]."</td>";
						echo    "<td>".$row2["Grade"]."</td>";	
					}	
				}

				echo "</table>";

			}
			else{

				echo "There is no data for the entered value(s)";

			}
			
		}
		else if(($_GET['sizeGT'] == null) && ($_GET['sizeLT'] != null)){

			$sql1 = "SELECT * FROM Department WHERE Size < ".$_GET['sizeLT'];
			$res1 = $conn2->query($sql1);
			$array1 = array();
			
			//Get appropriate departements (Dcode) according to the size entered by the user
			while($row1 = $res1->fetch(PDO::FETCH_ASSOC)){

				array_push($array1, $row1["Dcode"]); 
			}
			
			$array_size = count($array1);

			//Check if there are some outputs
			if ($array_size != 0){
				echo "<table id='table3'>";
				echo "<tr><th>Employee ID</th>";
				echo	"<th>Name</th>";
				echo    "<th>Grade</th></tr>";
			
				for($i=0; $i<$array_size; $i++){
			
					$sql2 = "SELECT * FROM Employee WHERE Dcode = $array1[$i]";
					$res2 = $conn->query($sql2);

					while($row2 = $res2->fetch(PDO::FETCH_ASSOC)){

						echo "<tr><td>".$row2["EmployeeId"]."</td>";
						echo    "<td>".$row2["Name"]."</td>";
						echo    "<td>".$row2["Grade"]."</td>";	
					}	
				}
				echo "</table>";
			}
			else{

				echo "There is no data for the entered value(s)";

			}

		}
		else if(($_GET['sizeGT'] != null) && ($_GET['sizeLT'] == null)){

			$sql1 = "SELECT * FROM Department WHERE Size > ".$_GET['sizeGT'];
			$res1 = $conn2->query($sql1);
			$array1 = array();
			
			//Get appropriate departements (Dcode) according to the size entered by the user
			while($row1 = $res1->fetch(PDO::FETCH_ASSOC)){

				array_push($array1, $row1["Dcode"]); 
			}
			
			$array_size = count($array1);
			
			//Check if there are some outputs
			if ($array_size != 0){
				echo "<table id='table4'>";
				echo "<tr><th>Employee ID</th>";
				echo	"<th>Name</th>";
				echo    "<th>Grade</th></tr>";
			
				for($i=0; $i<$array_size; $i++){
			
					$sql2 = "SELECT * FROM Employee WHERE Dcode = $array1[$i]";
					$res2 = $conn->query($sql2);

					while($row2 = $res2->fetch(PDO::FETCH_ASSOC)){

						echo "<tr><td>".$row2["EmployeeId"]."</td>";
						echo    "<td>".$row2["Name"]."</td>";
						echo    "<td>".$row2["Grade"]."</td>";	
					}	
				}
				echo "</table>";
			}
			else{

				echo "There is no data for the entered value(s)";

			}
			
		}
		else{
			echo "You must enter either an employee ID or the size or the two of them.";

		}

	}

}

?>
