<?php

    // include('../includes/dbconnection.php');

    // $fid = intval($_GET['fid']);//gradeId

    //     $queryss=mysqli_query($con,"select * from tbldepartment where facultyId=".$fid." ORDER BY departmentName ASC");                        
    //     $countt = mysqli_num_rows($queryss);

    //     if($countt > 0){                       
    //     echo '<label for="select" class=" form-control-label">Select Department</label>
    //     <select required name="departmentId" class="custom-select form-control">';
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
$facultyId = filter_var($_GET['fid'], FILTER_VALIDATE_INT);

if ($facultyId === false) {
    // Handle invalid faculty ID
    http_response_code(400);
    echo 'Invalid faculty ID';
    exit;
}

// Prepare the SQL query
$query = "SELECT * FROM tbldepartment WHERE facultyId = ? ORDER BY departmentName ASC";

// Prepare the statement
$stmt = mysqli_prepare($con, $query);

// Bind the faculty ID parameter
mysqli_stmt_bind_param($stmt, 'i', $facultyId);

// Execute the query
mysqli_stmt_execute($stmt);

// Get the result
$result = mysqli_stmt_get_result($stmt);

// Check if there are any departments
if (mysqli_num_rows($result) > 0) {
    // Output the department select options
    echo '<label for="select" class="form-control-label">Select Department</label>';
    echo '<select required name="departmentId" class="custom-select form-control">';
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

// Close the statement and connection
mysqli_stmt_close($stmt);
mysqli_close($con);

?>