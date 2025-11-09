<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-01');
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-t');
$report_data = [];
$total_bookings = 0;

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $stmt = $conn->prepare("SELECT * FROM tblbooking WHERE FromDate BETWEEN ? AND ? OR ToDate BETWEEN ? AND ?");
    $stmt->bind_param("ssss", $start_date, $end_date, $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $report_data[] = $row;
        }
        $total_bookings = $result->num_rows;
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TMS Booking Reports</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0 0 10px #ccc;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }

        input[type="date"] {
            padding: 10px;
            font-size: 16px;
        }

        button {
            padding: 10px 15px;
            background: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
        }

        button:hover {
            background: #2980b9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        table th {
            background: #3498db;
            color: white;
        }

        .status-confirmed {
            color: green;
            font-weight: bold;
        }

        .status-pending {
            color: orange;
            font-weight: bold;
        }

        .status-cancelled {
            color: red;
            font-weight: bold;
        }

        .summary {
            margin-top: 10px;
            font-weight: bold;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>TMS Booking Reports</h1>

    <form method="POST" class="no-print">
        <label for="start_date">From:</label>
        <input type="date" name="start_date" value="<?= $start_date ?>" required>

        <label for="end_date">To:</label>
        <input type="date" name="end_date" value="<?= $end_date ?>" required>

        <button type="submit"><i class="fas fa-search"></i> Generate Report</button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
        <div class="summary">
            Report Period: <strong><?= date('d M Y', strtotime($start_date)) ?></strong> to
            <strong><?= date('d M Y', strtotime($end_date)) ?></strong> |
            Total Bookings: <strong><?= $total_bookings ?></strong>
        </div>

        <?php if (!empty($report_data)): ?>
            <table>
                <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Package ID</th>
                    <th>User Email</th>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Status</th>
                    <th>Registered</th>
                    <th class="no-print">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($report_data as $row): ?>
                    <tr>
                        <td><?= $row['BookingId'] ?></td>
                        <td><?= $row['PackageId'] ?></td>
                        <td><?= $row['UserEmail'] ?></td>
                        <td><?= date('d M Y', strtotime($row['FromDate'])) ?></td>
                        <td><?= date('d M Y', strtotime($row['ToDate'])) ?></td>
                        <td class="status-<?= strtolower($row['status']) ?>"><?= $row['status'] ?></td>
                        <td><?= date('d M Y H:i', strtotime($row['RegDate'])) ?></td>
                        <td class="no-print">
                            <button onclick="printSingle(<?= $row['BookingId'] ?>)">Print</button>
                        </td>
                    </tr>

                    <!-- Hidden printable details -->
                    <div id="print-<?= $row['BookingId'] ?>" style="display: none;">
                        <h2>Booking Receipt</h2>
                        <p><strong>Booking ID:</strong> <?= $row['BookingId'] ?></p>
                        <p><strong>Package ID:</strong> <?= $row['PackageId'] ?></p>
                        <p><strong>User Email:</strong> <?= $row['UserEmail'] ?></p>
                        <p><strong>From:</strong> <?= date('d M Y', strtotime($row['FromDate'])) ?></p>
                        <p><strong>To:</strong> <?= date('d M Y', strtotime($row['ToDate'])) ?></p>
                        <p><strong>Status:</strong> <?= $row['status'] ?></p>
                        <p><strong>Registered:</strong> <?= date('d M Y H:i', strtotime($row['RegDate'])) ?></p>
                    </div>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No bookings found during the selected period.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>
    function printSingle(id) {
        const content = document.getElementById('print-' + id).innerHTML;
        const original = document.body.innerHTML;

        document.body.innerHTML = content;
        window.print();
        document.body.innerHTML = original;
        location.reload(); // reload to restore form
    }
</script>
</body>
</html>
