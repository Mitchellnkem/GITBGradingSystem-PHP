<?php

        include('../includes/dbconnection.php');

        $deptId = filter_var(
            $_GET['deptId'] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        if ($deptId === false || $deptId === null) {
            http_response_code(400);
            echo 'Invalid department ID';
            exit;
        }

        $studentStmt = mysqli_prepare(
            $conn,
            'SELECT matricNo, firstName, lastName, otherName
             FROM tblstudent
             WHERE departmentId = ?
             ORDER BY firstName ASC, lastName ASC'
        );
        mysqli_stmt_bind_param($studentStmt, 'i', $deptId);
        mysqli_stmt_execute($studentStmt);
        $queryss = mysqli_stmt_get_result($studentStmt);

        $courseStmt = mysqli_prepare(
            $conn,
            'SELECT Id, courseTitle
             FROM tblcourse
             WHERE departmentId = ?
             ORDER BY courseTitle ASC'
        );
        mysqli_stmt_bind_param($courseStmt, 'i', $deptId);
        mysqli_stmt_execute($courseStmt);
        $crsquery = mysqli_stmt_get_result($courseStmt);


        echo' <div class="row">
        <div class="col-6">
        <div class="form-group">';

        echo '<label for="matricNo" class="form-control-label">Student</label>
        <select required id="matricNo" name="matricNo" class="custom-select form-control">';
        echo'<option value="">--Select Student--</option>';
        while ($row = mysqli_fetch_assoc($queryss)) {
        $matricNo = htmlspecialchars($row['matricNo'], ENT_QUOTES, 'UTF-8');
        $fullName = htmlspecialchars(trim($row['firstName'].' '.$row['lastName'].' '.$row['otherName']), ENT_QUOTES, 'UTF-8');
        echo'<option value="'.$matricNo.'">'.$fullName.'</option>';
        }
        echo '</select>';

        echo' </div>
        </div>
        <div class="col-6">
        <div class="form-group">';

        echo '<label for="courseId" class="form-control-label">Course</label>
        <select required id="courseId" name="courseId" class="custom-select form-control">';
        echo'<option value="">--Select Course--</option>';
        while ($rows = mysqli_fetch_assoc($crsquery)) {
        $courseId = htmlspecialchars((string) $rows['Id'], ENT_QUOTES, 'UTF-8');
        $courseTitle = htmlspecialchars($rows['courseTitle'], ENT_QUOTES, 'UTF-8');
        echo'<option value="'.$courseId.'">'.$courseTitle.'</option>';
        }
        echo '</select>';  

        echo'</div>
        </div>
        </div>';



                    
       
      

?>

