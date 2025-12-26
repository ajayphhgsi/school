<?php
$page_title = 'Print Student List';
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List - <?php echo $school_name; ?></title>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 14px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .school-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .school-address {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .report-title {
            font-size: 20px;
            font-weight: bold;
            margin: 15px 0;
        }
        .report-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 12px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .photo-cell {
            width: 60px;
            text-align: center;
        }
        .photo-cell img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
        }
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }
        .no-print {
            display: none;
        }
        @media print {
            body {
                margin: 0;
            }
            .no-print {
                display: none !important;
            }
            .header {
                margin-bottom: 20px;
            }
            .table {
                font-size: 12px;
            }
            .table th, .table td {
                padding: 6px;
            }
            @page {
                margin: 1in;
                @bottom-center {
                    content: "Page " counter(page) " of " counter(pages);
                }
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px; padding: 20px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px;">
        <h5 style="margin-bottom: 15px;">Print Options</h5>
        <button onclick="window.print()" class="btn btn-primary btn-lg me-3">
            <i class="fas fa-print me-2"></i>Print Student List
        </button>
        <button onclick="window.close()" class="btn btn-secondary btn-lg">
            <i class="fas fa-times me-2"></i>Close Window
        </button>
    </div>

    <div class="header">
        <div class="school-name"><?php echo htmlspecialchars($school_name); ?></div>
        <?php if ($school_address): ?>
            <div class="school-address"><?php echo htmlspecialchars($school_address); ?></div>
        <?php endif; ?>
        <div class="report-title">STUDENT LIST REPORT</div>
    </div>

    <div class="report-info">
        <div><strong>Total Students:</strong> <?php echo count($students); ?></div>
        <div><strong>Generated on:</strong> <?php echo date('F d, Y \a\t h:i A'); ?></div>
    </div>

    <?php if (!empty($students)): ?>
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 50px;">S.No</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Scholar No.</th>
                    <th>Admission No.</th>
                    <th>Class</th>
                    <th>Contact</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $serial = 1; foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo $serial++; ?></td>
                        <td class="photo-cell">
                            <?php if ($student['photo']): ?>
                                <img src="/uploads/<?php echo $student['photo']; ?>" alt="Photo">
                            <?php else: ?>
                                <div style="width: 40px; height: 40px; background: #e9ecef; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; color: #6c757d;">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($student['scholar_number'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($student['admission_number'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($student['class_name'] ? $student['class_name'] . ' ' . $student['section'] : 'No Class'); ?></td>
                        <td>
                            <div><?php echo htmlspecialchars($student['mobile'] ?? ''); ?></div>
                            <?php if (!empty($student['email'])): ?>
                                <div style="font-size: 11px;"><?php echo htmlspecialchars($student['email']); ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status-badge <?php echo $student['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                                <?php echo $student['is_active'] ? 'Active' : 'Inactive'; ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div style="text-align: center; padding: 50px;">
            <h4>No Students Found</h4>
        </div>
    <?php endif; ?>

    <script>
        // Auto-print when page loads (optional)
        // window.onload = function() {
        //     window.print();
        // };
    </script>
</body>
</html>

<?php
$content = ob_get_clean();
// Don't include layout, as this is a standalone print page
echo $content;
?>