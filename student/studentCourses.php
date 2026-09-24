<?php
require_once '../includes/dbconnection.php';
require_once '../includes/session.php';
$page = 'courses'; $pageTitle = 'My courses'; $pageEyebrow = 'Academics'; $pageDescription = 'Courses assigned to your department and current level.';
require 'includes/layoutTop.php';

$courses = [];
$stmt = mysqli_prepare($conn, 'SELECT c.courseTitle,c.courseCode,c.courseUnit,l.levelName,f.facultyName,d.departmentName,s.semesterName FROM tblcourse c INNER JOIN tbllevel l ON l.Id=c.levelId INNER JOIN tblsemester s ON s.Id=c.semesterId INNER JOIN tblfaculty f ON f.Id=c.facultyId INNER JOIN tbldepartment d ON d.Id=c.departmentId WHERE c.departmentId=? AND c.levelId=? ORDER BY c.semesterId,c.courseCode');
mysqli_stmt_bind_param($stmt, 'ii', $departmentId, $levelId); mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt); while ($row = mysqli_fetch_assoc($result)) $courses[] = $row;
$totalUnits = array_sum(array_map(function ($course) { return (int) $course['courseUnit']; }, $courses));
?>
<div class="portal-summary-grid">
    <div class="portal-summary"><span>Total courses</span><strong><?php echo count($courses); ?></strong></div>
    <div class="portal-summary"><span>Total units</span><strong><?php echo $totalUnits; ?></strong></div>
    <div class="portal-summary"><span>Current level</span><strong><?php echo htmlspecialchars($courses[0]['levelName'] ?? '—'); ?></strong></div>
    <div class="portal-summary"><span>Department</span><strong style="font-size:16px"><?php echo htmlspecialchars($courses[0]['departmentName'] ?? '—'); ?></strong></div>
</div>
<section class="portal-card"><div class="portal-card-title"><div><h2>Course catalogue</h2><p>Your complete course load, grouped by semester.</p></div></div>
<?php if (!$courses): ?><div class="portal-empty"><i class="fa fa-book"></i><strong>No courses found</strong><span>Your department has no courses assigned at this level yet.</span></div>
<?php else: ?><div class="portal-table-wrap"><table class="portal-table"><thead><tr><th>Course</th><th>Code</th><th>Units</th><th>Semester</th><th>Level</th></tr></thead><tbody>
<?php foreach ($courses as $course): ?><tr><td><strong><?php echo htmlspecialchars($course['courseTitle']); ?></strong></td><td><span class="portal-code"><?php echo htmlspecialchars($course['courseCode']); ?></span></td><td><?php echo (int) $course['courseUnit']; ?></td><td><span class="portal-badge"><?php echo htmlspecialchars($course['semesterName']); ?></span></td><td><?php echo htmlspecialchars($course['levelName']); ?></td></tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?></section>
<?php require 'includes/layoutBottom.php'; ?>
