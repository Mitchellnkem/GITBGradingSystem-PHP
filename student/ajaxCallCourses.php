<?php

    // include('../includes/dbconnection.php');

    // $deptId = intval($_GET['deptId']);//gradeId

    //     $queryss=mysqli_query($conn,"select * from tblcourse where departmentId=".$deptId." ORDER BY courseTitle ASC");                        
    //     $countt = mysqli_num_rows($queryss);

    //     if($countt > 0){                       
    //     echo '<label for="select" class=" form-control-label">Course</label>
    //     <select required name="courseId" class="custom-select form-control">';
    //     echo'<option value="">--Select Course--</option>';
    //     while ($row = mysqli_fetch_array($queryss)) {
    //     echo'<option value="'.$row['courseId'].'" >'.$row['courseTitle'].'</option>';
    //     }
    //     echo '</select>';
    //     }

?>



<?php

// Include database connection file
include('../includes/dbconnection.php');

// Validate and sanitize input
$deptId = filter_var($_GET['deptId'], FILTER_VALIDATE_INT);

// Check if department ID is valid
if ($deptId === false) {
    echo 'Invalid department ID';
    exit;
}

// Prepare SQL query
$query = "SELECT * FROM tblcourse WHERE departmentId = ? ORDER BY courseTitle ASC";

// Prepare statement
$stmt = mysqli_prepare($conn, $query);

// Bind parameter
mysqli_stmt_bind_param($stmt, 'i', $deptId);

// Execute query
mysqli_stmt_execute($stmt);

// Get result
$result = mysqli_stmt_get_result($stmt);

// Check if result is not empty
if (mysqli_num_rows($result) > 0) {
    // Display course selection
    echo '<label for="select" class="form-control-label">Course</label>';
    echo '<select required name="courseId" class="custom-select form-control">';
    echo '<option value="">--Select Course--</option>';

    // Loop through courses
    while ($row = mysqli_fetch_array($result)) {
        echo '<option value="' . $row['courseId'] . '">' . $row['courseTitle'] . '</option>';
    }

    echo '</select>';
} else {
    echo 'No courses found';
}

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($conn);

?>