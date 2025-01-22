<?php

    // include('../includes/dbconnection.php');

    // $fid = intval($_GET['fid']);//gradeId

    //     $queryss=mysqli_query($con,"select * from tbldepartment where facultyId=".$fid." ORDER BY departmentName ASC");                        
    //     $countt = mysqli_num_rows($queryss);

    //     if($countt > 0){                       
    //     echo '<label for="select" class=" form-control-label">Department</label>
    //     <select required name="departmentId" onchange="showCourses(this.value)" class="custom-select form-control">';
    //     echo'<option value="">--Select Department--</option>';
    //     while ($row = mysqli_fetch_array($queryss)) {
    //     echo'<option value="'.$row['Id'].'" >'.$row['departmentName'].'</option>';
    //     }
    //     echo '</select>';
    //     }

?>

<?php

// Include the database connection file
require_once '../includes/dbconnection.php';

// Validate and sanitize the faculty ID
$fid = filter_var($_GET['fid'], FILTER_VALIDATE_INT);

if ($fid === false) {
    // Handle invalid faculty ID
    http_response_code(400);
    echo 'Invalid faculty ID';
    exit;
}

// Prepare the SQL query
$query = "SELECT Id, departmentName FROM tbldepartment WHERE facultyId = $fid ORDER BY departmentName ASC";

// Execute the query
$result = mysqli_query($con, $query);

// Check if there are any departments
if (mysqli_num_rows($result) > 0) {
    // Output the department select options
    echo '<label for="select" class="form-control-label">Department</label>';
    echo '<select required name="departmentId" onchange="showCourses(this.value)" class="custom-select form-control">';
    echo '<option value="">--Select Department--</option>';

    // Loop through the departments
    while ($row = mysqli_fetch_array($result)) {
        echo '<option value="' . $row['Id'] . '">' . $row['departmentName'] . '</option>';
    }

    echo '</select>';
} else {
    // Handle no departments found
    echo '<p>No departments found for the selected faculty.</p>';
}

// Close the connection
mysqli_close($con);

?>