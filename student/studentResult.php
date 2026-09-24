<?php
require_once '../includes/dbconnection.php'; require_once '../includes/session.php';
$levels=[]; $sessions=[]; $semesters=[];
foreach ([['tbllevel','levelName',&$levels],['tblsession','sessionName',&$sessions],['tblsemester','semesterName',&$semesters]] as &$source) {
    $query=mysqli_query($conn,"SELECT Id, {$source[1]} AS label FROM {$source[0]} ORDER BY Id"); while($query && $row=mysqli_fetch_assoc($query)) $source[2][]=$row;
}
$studentLevelQuery=mysqli_prepare($conn,'SELECT levelId FROM tblstudent WHERE matricNo=? LIMIT 1');
mysqli_stmt_bind_param($studentLevelQuery,'s',$matricNo); mysqli_stmt_execute($studentLevelQuery); $studentLevelRow=mysqli_fetch_assoc(mysqli_stmt_get_result($studentLevelQuery));
$selectedLevel=(int)($_GET['levelId'] ?? ($studentLevelRow['levelId'] ?? 0));
$activeSessionQuery=mysqli_query($conn,'SELECT Id FROM tblsession WHERE isActive=1 LIMIT 1'); $activeSessionRow=$activeSessionQuery?mysqli_fetch_assoc($activeSessionQuery):null;
$selectedSession=(int)($_GET['sessionId'] ?? ($activeSessionRow['Id'] ?? 0));
$selectedSemester=(int)($_GET['semesterId'] ?? ($semesters[0]['Id'] ?? 0));
$results=[]; $summary=null;
if($selectedLevel && $selectedSession && $selectedSemester){
    $stmt=mysqli_prepare($conn,'SELECT r.courseCode,r.courseUnit,r.score,r.scoreGradePoint,r.scoreLetterGrade,r.totalScoreGradePoint,c.courseTitle FROM tblresult r LEFT JOIN tblcourse c ON c.courseCode=r.courseCode WHERE r.levelId=? AND r.sessionId=? AND r.semesterId=? AND r.matricNo=? ORDER BY r.courseCode');
    mysqli_stmt_bind_param($stmt,'iiis',$selectedLevel,$selectedSession,$selectedSemester,$matricNo); mysqli_stmt_execute($stmt); $result=mysqli_stmt_get_result($stmt); while($row=mysqli_fetch_assoc($result))$results[]=$row;
    $stmt=mysqli_prepare($conn,'SELECT totalCourseUnit,totalScoreGradePoint,gpa,classOfDiploma FROM tblfinalresult WHERE levelId=? AND sessionId=? AND semesterId=? AND matricNo=? LIMIT 1');
    mysqli_stmt_bind_param($stmt,'iiis',$selectedLevel,$selectedSession,$selectedSemester,$matricNo); mysqli_stmt_execute($stmt); $summary=mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))?:null;
}
$page='result'; $pageTitle='Semester results'; $pageEyebrow='Performance'; $pageDescription='Review scores, grade points, and semester standing.'; require 'includes/layoutTop.php';
?>
<section class="portal-card"><div class="portal-card-title"><div><h2>Choose an academic period</h2><p>Results are shown only after they have been published.</p></div></div>
<form class="portal-filter" method="get"><div class="portal-field"><label for="levelId">Level</label><select id="levelId" name="levelId"><?php foreach($levels as $option): ?><option value="<?php echo (int)$option['Id']; ?>" <?php echo $selectedLevel===(int)$option['Id']?'selected':''; ?>><?php echo htmlspecialchars($option['label']); ?></option><?php endforeach; ?></select></div>
<div class="portal-field"><label for="sessionId">Session</label><select id="sessionId" name="sessionId"><?php foreach($sessions as $option): ?><option value="<?php echo (int)$option['Id']; ?>" <?php echo $selectedSession===(int)$option['Id']?'selected':''; ?>><?php echo htmlspecialchars($option['label']); ?></option><?php endforeach; ?></select></div>
<div class="portal-field"><label for="semesterId">Semester</label><select id="semesterId" name="semesterId"><?php foreach($semesters as $option): ?><option value="<?php echo (int)$option['Id']; ?>" <?php echo $selectedSemester===(int)$option['Id']?'selected':''; ?>><?php echo htmlspecialchars($option['label']); ?></option><?php endforeach; ?></select></div><button class="portal-button" type="submit"><i class="fa fa-search"></i> View results</button></form></section>

<?php if($summary): ?><div class="portal-summary-grid"><div class="portal-summary"><span>Courses</span><strong><?php echo count($results); ?></strong></div><div class="portal-summary"><span>Course units</span><strong><?php echo htmlspecialchars($summary['totalCourseUnit']); ?></strong></div><div class="portal-summary"><span>Semester GPA</span><strong><?php echo number_format((float)$summary['gpa'],2); ?></strong></div><div class="portal-summary"><span>Standing</span><strong style="font-size:16px"><?php echo htmlspecialchars($summary['classOfDiploma']); ?></strong></div></div><?php endif; ?>

<section class="portal-card" style="margin-top:20px"><div class="portal-card-title"><div><h2>Course performance</h2><p><?php echo count($results); ?> published course result<?php echo count($results)===1?'':'s'; ?> for this period.</p></div><?php if($results): ?><a class="portal-button secondary" target="_blank" href="studentPrintResult.php?semesterId=<?php echo $selectedSemester; ?>&amp;matricNo=<?php echo urlencode($matricNo); ?>&amp;levelId=<?php echo $selectedLevel; ?>&amp;sessionId=<?php echo $selectedSession; ?>"><i class="fa fa-print"></i> Print</a><?php endif; ?></div>
<?php if(!$results): ?><div class="portal-empty"><i class="fa fa-file-text-o"></i><strong>No published results</strong><span>There are no results for the academic period you selected.</span></div><?php else: ?><div class="portal-table-wrap"><table class="portal-table"><thead><tr><th>Course</th><th>Code</th><th>Units</th><th>Score</th><th>Grade</th><th>Grade point</th><th>Quality points</th></tr></thead><tbody>
<?php foreach($results as $row): $failed=$row['scoreLetterGrade']==='F'; ?><tr><td><strong><?php echo htmlspecialchars($row['courseTitle'] ?: 'Course'); ?></strong></td><td><span class="portal-code"><?php echo htmlspecialchars($row['courseCode']); ?></span></td><td><?php echo htmlspecialchars($row['courseUnit']); ?></td><td><?php echo htmlspecialchars($row['score']); ?></td><td><span class="portal-badge <?php echo $failed?'danger':''; ?>"><?php echo htmlspecialchars($row['scoreLetterGrade']); ?></span></td><td><?php echo htmlspecialchars($row['scoreGradePoint']); ?></td><td><?php echo htmlspecialchars($row['totalScoreGradePoint']); ?></td></tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?></section>
<?php require 'includes/layoutBottom.php'; ?>
