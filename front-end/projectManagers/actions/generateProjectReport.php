<!----- PHP HEADER ----->

<?php
session_start();

if (!$_SESSION['loggedin'] || $_SESSION['roleID'] != 3)
{header ("Location: ../login.php");}

// Store session

$tempUserID = (int)$_SESSION['userID'];
$roleID = (int)$_SESSION['roleID'];

$projectId = (int)$_GET['report'];
// Include DB Connection
include "../../../includes/dbconnect.ini.php";



//total tasks
$sqlTotalProjectTasksQuery = "SELECT COUNT(taskId) as totalTasks FROM Tasks WHERE projectId = $projectId";
$stmt2 = $conn->query($sqlTotalProjectTasksQuery);
$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
$totalTasks = $row2['totalTasks'];

//tasks completed
$completeStatus=5;
$sqlProjectTasksCompletedQuery = "SELECT COUNT(taskId) as completedTasks FROM Tasks WHERE projectId = $projectId AND status = $completeStatus";
$stmt3 = $conn->query($sqlProjectTasksCompletedQuery);
$row3 = $stmt3->fetch(PDO::FETCH_ASSOC);
$tasksCompleted = $row3['completedTasks'];

//tasks in progress
$sqlProjectTasksInProgressQuery = "SELECT COUNT(taskId) as progressTasks FROM Tasks WHERE projectId = $projectId AND (status = 2 OR status = 3 OR status = 4)";
$stmt4 = $conn->query($sqlProjectTasksInProgressQuery);
$row4 = $stmt4->fetch(PDO::FETCH_ASSOC);
$tasksInProgress = $row4['progressTasks'];

//tasks not started
$notStartedStatus=1;
$sqlProjectTasksCompletedQuery = "SELECT COUNT(taskId) as notStartedTasks FROM Tasks WHERE projectId = $projectId AND status = $notStartedStatus";
$stmt5 = $conn->query($sqlProjectTasksCompletedQuery);
$row5 = $stmt5->fetch(PDO::FETCH_ASSOC);
$tasksNotStarted = $row5['notStartedTasks'];

$dataPoints = array(
	array("label"=>"Complete", "y"=>100*$tasksCompleted/$totalTasks),
	array("label"=>"In Progress", "y"=>100*$tasksInProgress/$totalTasks),
	array("label"=>"Not Started", "y"=>100*$tasksNotStarted/$totalTasks)

)

?>

<!----- HTML SECTION ----->

<!DOCTYPE HTML>
<title>Project Hours Report</title>

<head>
<script>
window.onload = function() {


var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	title: {
		text: "Check me out fam..."
	},
	subtitles: [{
		text: "Look what I did"
	}],
	data: [{
		type: "pie",
		yValueFormatString: "#,##0.00\"%\"",
		indexLabel: "{label} ({y})",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();

}
</script>
</head>

<link rel="stylesheet"type="text/css"href="../../users/bootstrap.css">
<link rel="stylesheet"type="text/css"href="../../style.css">
<body>
	<div id="header" class="ui-container">
		<div class="nav">
			<button class="nav-hover">Menu</button>
			<div class="nav-links">
				<a href="../projectManagersIndex.php"> Back to Index </a>
				<a href="../../actionLogOut.php">Sign out</a>
			</div>
		</div>
	</div>

	<div id = "login-container" class = "ui-container">

		<?php
		// Include DB Connection
		//include "../../../includes/dbconnect.ini.php";

		$sqlProjectNameQuery = "SELECT projectName FROM Projects WHERE projectId = $projectId";

		$stmt = $conn->query($sqlProjectNameQuery);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		echo "<center><p> Project Hours Report on $row[projectName] </p></center>"

		?>

		<center> <label> Total Hours put into Project </label> </center>

		<center> <label> Total Tasks </label> </center>
		<center>
			<?php
				echo "<p>".$row2['totalTasks']."</p>";
			?>
		</center>
		<center> <label> Tasks Complete </label> </center>
		<center>
			<?php
			echo "<p>".$row3['completedTasks']."</p>";
			?>
		</center>

		<center> <label> Tasks In Progress </label> </center>
		<center>
			<?php
			echo "<p>".$row4['progressTasks']."</p>";
			?>
		</center>

		<center> <label> Tasks not started </label> </center>
		<center>
			<?php
			echo "<p>".$row5['notStartedTasks']."</p>";
			?>
		</center>


	</div>
	<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>


</body>
</HTML>
