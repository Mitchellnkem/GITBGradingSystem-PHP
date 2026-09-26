<?php


	include('../includes/dbconnection.php');
	
	$deptId = intval($_GET['deptId']);//gradeId


	$queryss=mysqli_query($conn,"select * from tblcourse where departmentId=".$deptId." ORDER BY courseTitle ASC");                        
	$countt = mysqli_num_rows($queryss);

	if($countt > 0) {
		echo '<label for "select" class=" form-control-label">Course</label>'
		.'<select required name="courseCode" class="custom-select form-control">'
			.'<option value="">--Select Course--</option>';
		while ($rows = mysqli_fetch_array($queryss)) {
			echo'<option value="'.$rows['courseId'].'" >'.$rows['courseTitle'].'</option>';
		}
		echo '</select>';
	}


?>