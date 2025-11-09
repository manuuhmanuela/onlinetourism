<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {    
    header('location:index.php');
    exit();
}

// Handle date range filtering
$date_filter = "";
$filter_text = "";
if(isset($_POST['generate_report'])) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    
    if(!empty($from_date) && !empty($to_date)) {
        $date_filter = " WHERE (tblbooking.FromDate BETWEEN '$from_date' AND '$to_date')";
        $filter_text = " (From ".date('M j, Y', strtotime($from_date))." to ".date('M j, Y', strtotime($to_date)).")";
    }
}

// Get booking data
$sql = "SELECT tblbooking.BookingId as bookid, tblusers.FullName as fname, 
        tblusers.EmailId as email, tblusers.MobileNumber as mobile,
        tbltourpackages.PackageName as pckname, tblbooking.PackageId as pid,
        tblbooking.FromDate as fdate, tblbooking.ToDate as tdate, 
        tblbooking.Comment as comment, tblbooking.status as status, 
        tblbooking.RegDate as regdate
        FROM tblusers 
        JOIN tblbooking ON tblbooking.UserEmail = tblusers.EmailId 
        JOIN tbltourpackages ON tbltourpackages.PackageId = tblbooking.PackageId
        $date_filter
        ORDER BY tblbooking.BookingId DESC";

$query = $dbh->prepare($sql);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);
$total_bookings = $query->rowCount();
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Booking Reports</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    body { font-family: Arial; padding: 20px; }
    .report-header { margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
    .filter-box { background: #f8f9fa; padding: 20px; margin-bottom: 30px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
    th { background-color: #343a40; color: white; text-align: left; }
    th, td { padding: 12px; border: 1px solid #ddd; }
    tr:nth-child(even) { background-color: #f9f9f9; }
    tr:hover { background-color: #f1f1f1; }
    .status-pending { color: #ffc107; font-weight: bold; }
    .status-confirmed { color: #28a745; font-weight: bold; }
    .status-cancelled { color: #dc3545; font-weight: bold; }
    .btn { margin-right: 5px; }
    .action-btns { white-space: nowrap; }
    .no-print { display: block; }
    .print-only { display: none; }
    @media print {
        body { padding: 0; font-size: 12px; }
        .no-print { display: none !important; }
        .print-only { display: block !important; }
        .report-header { border-bottom: 2px solid #000; }
        table { width: 100%; font-size: 11px; }
        th { background-color: #333 !important; -webkit-print-color-adjust: exact; }
    }
    .summary-card {
        background: #f8f9fa;
        border-left: 4px solid #007bff;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }
</style>
</head>
<body>

<div class="container">
    <div class="report-header">
        <h2>Booking Management System</h2>
        <h3>Booking Reports <?php echo $filter_text; ?></h3>
        <p class="text-muted">Generated on: <?php echo date('F j, Y, g:i a'); ?></p>
    </div>
    
    <!-- Report Generation Form -->
    <div class="filter-box no-print">
        <h4><i class="fas fa-filter"></i> Generate Custom Report</h4>
        <form method="post">
            <div class="row">
                <div class="col-md-3">
                    <label>From Date</label>
                    <input type="date" name="from_date" class="form-control" value="<?php echo $_POST['from_date'] ?? ''; ?>" required>
                </div>
                <div class="col-md-3">
                    <label>To Date</label>
                    <input type="date" name="to_date" class="form-control" value="<?php echo $_POST['to_date'] ?? ''; ?>" required>
                </div>
                <div class="col-md-3" style="margin-top: 30px;">
                    <button type="submit" name="generate_report" class="btn btn-primary">
                        <i class="fas fa-chart-bar"></i> Generate Report
                    </button>
                    <a href="reports.php" class="btn btn-secondary">
                        <i class="fas fa-sync-alt"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Summary Card -->
    <div class="summary-card">
        <h5><i class="fas fa-info-circle"></i> Report Summary</h5>
        <div class="row">
            <div class="col-md-3">
                <p><strong>Total Bookings:</strong> <?php echo $total_bookings; ?></p>
            </div>
            <?php if(isset($_POST['generate_report'])): ?>
            <div class="col-md-6">
                <p><strong>Date Range:</strong> <?php echo date('M j, Y', strtotime($_POST['from_date'])); ?> to <?php echo date('M j, Y', strtotime($_POST['to_date'])); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Print All Button -->
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" class="btn btn-success">
            <i class="fas fa-print"></i> Print All Reports
        </button>
    </div>
    
    <!-- Booking Data Table -->
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Booking ID</th>
                <th>Customer Details</th>
                <th>Package</th>
                <th>Travel Dates</th>
                <th>Status</th>
                <th class="no-print">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $counter = 1; foreach($results as $result): ?>
            <tr>
                <td><?php echo $counter++; ?></td>
                <td>BK-<?php echo $result->bookid; ?></td>
                <td>
                    <strong><?php echo $result->fname; ?></strong><br>
                    <?php echo $result->email; ?><br>
                    <?php echo $result->mobile; ?>
                </td>
                <td><?php echo $result->pckname; ?></td>
                <td>
                    <?php echo date('M j, Y', strtotime($result->fdate)); ?><br>
                    to<br>
                    <?php echo date('M j, Y', strtotime($result->tdate)); ?>
                </td>
                <td>
                    <?php 
                    if($result->status==0) echo '<span class="status-pending">Pending</span>';
                    elseif($result->status==1) echo '<span class="status-confirmed">Confirmed</span>';
                    else echo '<span class="status-cancelled">Cancelled</span>';
                    ?>
                </td>
                <td class="no-print action-btns">
                    <button onclick="printIndividualReport(<?php echo $result->bookid; ?>)" class="btn btn-sm btn-info">
                        <i class="fas fa-print"></i> Print
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <!-- Individual Report Templates (Hidden) -->
    <?php foreach($results as $result): ?>
    <div id="report-<?php echo $result->bookid; ?>" class="print-content" style="display:none;">
        <div class="report-header">
            <h2>Booking Management System</h2>
            <h3>Booking Details</h3>
            <p class="text-muted">Generated on: <?php echo date('F j, Y, g:i a'); ?></p>
        </div>
        
        <table class="table">
            <tr>
                <th width="30%">Booking ID</th>
                <td>BK-<?php echo $result->bookid; ?></td>
            </tr>
            <tr>
                <th>Customer Name</th>
                <td><?php echo $result->fname; ?></td>
            </tr>
            <tr>
                <th>Email Address</th>
                <td><?php echo $result->email; ?></td>
            </tr>
            <tr>
                <th>Mobile Number</th>
                <td><?php echo $result->mobile; ?></td>
            </tr>
            <tr>
                <th>Package</th>
                <td><?php echo $result->pckname; ?></td>
            </tr>
            <tr>
                <th>Travel Dates</th>
                <td>
                    <?php echo date('M j, Y', strtotime($result->fdate)); ?> to 
                    <?php echo date('M j, Y', strtotime($result->tdate)); ?>
                </td>
            </tr>
            <tr>
                <th>Booking Date</th>
                <td><?php echo date('M j, Y', strtotime($result->regdate)); ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <?php 
                    if($result->status==0) echo '<span class="status-pending">Pending</span>';
                    elseif($result->status==1) echo '<span class="status-confirmed">Confirmed</span>';
                    else echo '<span class="status-cancelled">Cancelled</span>';
                    ?>
                </td>
            </tr>
            <tr>
                <th>Special Requests</th>
                <td><?php echo $result->comment ? $result->comment : 'None'; ?></td>
            </tr>
        </table>
    </div>
    <?php endforeach; ?>
</div>

<script>
// Print individual report
function printIndividualReport(bookingId) {
    var printContents = document.getElementById('report-' + bookingId).innerHTML;
    var originalContents = document.body.innerHTML;
    
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
</script>

</body>
</html>