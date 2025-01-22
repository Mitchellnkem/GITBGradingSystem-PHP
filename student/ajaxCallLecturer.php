<?php

    // include('../includes/dbconnection.php');

    // $deptId = intval($_GET['deptId']);//gradeId


    //     $queryss=mysqli_query($con,"SELECT tblassignedstaff.dateAssigned,tblassignedstaff.staffId, tblstaff.staffId,tblstaff.firstName, tblstaff.lastName, tblstaff.otherName
    //     from tblassignedstaff 
    //     INNER JOIN tblstaff ON tblstaff.staffId = tblassignedstaff.staffId
    //     where departmentId = '$deptId'");
    //     $countt = mysqli_num_rows($queryss);


    //     // $queryss=mysqli_query($con,"select * from tblassignedstaff where departmentId=".$deptId." ORDER BY staffId ASC");                        
    //     // $countt = mysqli_num_rows($queryss);

    //     if($countt > 0){                       
    //     echo '<label for="select" class=" form-control-label">Select Lecturer</label>
    //     <select required name="staffId" class="custom-select form-control">';
    //     echo'<option value="">--Select Lecturer--</option>';
    //     while ($row = mysqli_fetch_array($queryss)) {
    //     echo'<option value="'.$row['staffId'].'" >'.$row['firstName'].' '.$row['lastName'].'</option>';
    //     }
    //     echo '</select>';
    //     }

?>


<?php

// Include database connection file
include('../includes/dbconnection.php');

// Validate and sanitize input
$deptId = filter_var($_GET['deptId'], FILTER_VALIDATE_INT);

if ($deptId === false) {
    // Handle invalid input
    echo 'Invalid department ID';
    exit;
}

// Prepare SQL query
$query = "
    SELECT 
        tblassignedstaff.dateAssigned,
        tblassignedstaff.staffId,
        tblstaff.staffId,
        tblstaff.firstName,
        tblstaff.lastName,
        tblstaff.otherName
    FROM 
        tblassignedstaff 
    INNER JOIN 
        tblstaff ON tblstaff.staffId = tblassignedstaff.staffId
    WHERE 
        departmentId = ?
";

// Execute query with prepared statement
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 'i', $deptId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if query returned any rows
if (mysqli_num_rows($result) > 0) {
    // Display select dropdown
    echo '<label for="select" class="form-control-label">Select Lecturer</label>';
    echo '<select required name="staffId" class="custom-select form-control">';
    echo '<option value="">--Select Lecturer--</option>';
    while ($row = mysqli_fetch_array($result)) {
        echo '<option value="' . $row['staffId'] . '">' . $row['firstName'] . ' ' . $row['lastName'] . '</option>';
    }
    echo '</select>';
} else {
    // Handle no results
    echo 'No lecturers found';
}

?>