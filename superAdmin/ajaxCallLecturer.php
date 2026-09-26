<?php

include('../includes/dbconnection.php');

$deptId = filter_var(
    $_GET['deptId'] ?? null,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

if ($deptId === false || $deptId === null) {
    http_response_code(400);
    echo 'Invalid department ID.';
    exit;
}

$query = 'SELECT DISTINCT
            staff.staffId,
            staff.firstName,
            staff.lastName,
            staff.otherName
          FROM tblassignedstaff AS assigned
          INNER JOIN tblstaff AS staff ON staff.staffId = assigned.staffId
          WHERE assigned.departmentId = ?
          ORDER BY staff.firstName ASC, staff.lastName ASC, staff.otherName ASC';

$statement = mysqli_prepare($conn, $query);

if ($statement === false) {
    error_log('Unable to prepare lecturer lookup: ' . mysqli_error($conn));
    http_response_code(500);
    echo 'Unable to load lecturers.';
    exit;
}

mysqli_stmt_bind_param($statement, 'i', $deptId);

if (!mysqli_stmt_execute($statement)) {
    error_log('Unable to execute lecturer lookup: ' . mysqli_stmt_error($statement));
    mysqli_stmt_close($statement);
    http_response_code(500);
    echo 'Unable to load lecturers.';
    exit;
}

$result = mysqli_stmt_get_result($statement);

if ($result !== false && mysqli_num_rows($result) > 0) {
    echo '<label for="staffId" class="form-control-label">Select Lecturer/Tutor</label>';
    echo '<select required id="staffId" name="staffId" class="custom-select form-control">';
    echo '<option value="">--Select Lecturer/Tutor--</option>';

    while ($row = mysqli_fetch_assoc($result)) {
        $staffId = htmlspecialchars((string) $row['staffId'], ENT_QUOTES, 'UTF-8');
        $fullName = trim(
            ($row['firstName'] ?? '') . ' ' .
            ($row['lastName'] ?? '') . ' ' .
            ($row['otherName'] ?? '')
        );
        $fullName = htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8');

        echo '<option value="' . $staffId . '">' . $fullName . '</option>';
    }

    echo '</select>';
}

mysqli_stmt_close($statement);

?>
