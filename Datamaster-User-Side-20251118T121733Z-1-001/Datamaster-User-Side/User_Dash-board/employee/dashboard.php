<?php
require_once('../session_config.php');
require_once('../csrf.php');
require_once('../connection.php');

// Check if user is logged in as employee
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'employee') {
    header('Location: login.php');
    exit();
}

// Get employee info
$employee_id = $_SESSION['employee_id'];
$employee_name = $_SESSION['employee_name'];
$department = $_SESSION['department'];
$user_id = $_SESSION['user_id'];

// Get current status (last action)
$stmt = $conn->prepare("SELECT action, timestamp FROM attendance_log WHERE user_id = ? ORDER BY timestamp DESC LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$last_action = $result->fetch_assoc();
$stmt->close();

$is_clocked_in = false;
$current_status = 'Not Clocked In';
$status_class = 'secondary';

if ($last_action) {
    if ($last_action['action'] === 'IN') {
        $is_clocked_in = true;
        $current_status = 'Currently Working';
        $status_class = 'success';
    } else {
        $current_status = 'Clocked Out';
        $status_class = 'danger';
    }
}

// Get today's summary
$today = date('Y-m-d');
$stmt = $conn->prepare("SELECT action, TIME(timestamp) as time FROM attendance_log WHERE user_id = ? AND DATE(timestamp) = ? ORDER BY timestamp ASC");
$stmt->bind_param("is", $user_id, $today);
$stmt->execute();
$today_log = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get this week's hours
$week_start = date('Y-m-d', strtotime('monday this week'));
$stmt = $conn->prepare("
    SELECT 
        DATE(timestamp) as date,
        SUM(CASE WHEN action = 'IN' THEN -UNIX_TIMESTAMP(timestamp) ELSE UNIX_TIMESTAMP(timestamp) END) as seconds
    FROM attendance_log 
    WHERE user_id = ? AND DATE(timestamp) >= ?
    GROUP BY DATE(timestamp)
");
$stmt->bind_param("is", $user_id, $week_start);
$stmt->execute();
$week_data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$total_hours_week = 0;
foreach ($week_data as $day) {
    if ($day['seconds'] > 0) {
        $total_hours_week += $day['seconds'] / 3600;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard - DataMaster</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .dashboard-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .status-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            text-align: center;
        }
        .status-badge {
            font-size: 18px;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            display: inline-block;
            margin: 20px 0;
        }
        .clock-button {
            font-size: 24px;
            padding: 20px 60px;
            border-radius: 50px;
            font-weight: 700;
            border: none;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .clock-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .btn-clock-in {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        .btn-clock-out {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
        }
        .info-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .info-card h5 {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .time-entry {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .time-entry:last-child {
            border-bottom: none;
        }
        .stat-box {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
        }
        .stat-box h3 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark navbar-custom">
        <div class="container-fluid">
            <span class="navbar-brand">
                <i class="fas fa-user-tie"></i> Employee Portal
            </span>
            <div class="d-flex align-items-center text-white">
                <span class="me-3">
                    <i class="fas fa-id-badge"></i> <?php echo htmlspecialchars($employee_name); ?>
                    <small class="ms-2">(<?php echo htmlspecialchars($employee_id); ?>)</small>
                </span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="dashboard-container">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <!-- Status Card -->
            <div class="col-md-8">
                <div class="status-card">
                    <h2><i class="fas fa-clock"></i> Current Status</h2>
                    <div class="status-badge badge bg-<?php echo $status_class; ?>">
                        <?php echo $current_status; ?>
                    </div>
                    
                    <?php if ($last_action): ?>
                        <p class="text-muted">
                            Last Action: <?php echo ucfirst(strtolower($last_action['action'])); ?> at 
                            <?php echo date('g:i A', strtotime($last_action['timestamp'])); ?>
                        </p>
                    <?php endif; ?>
                    
                    <div class="mt-4">
                        <form method="POST" action="clock_action.php">
                            <?php csrf_field(); ?>
                            <?php if ($is_clocked_in): ?>
                                <button type="submit" name="action" value="OUT" class="btn clock-button btn-clock-out">
                                    <i class="fas fa-sign-out-alt"></i> Clock Out
                                </button>
                            <?php else: ?>
                                <button type="submit" name="action" value="IN" class="btn clock-button btn-clock-in">
                                    <i class="fas fa-sign-in-alt"></i> Clock In
                                </button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- This Week Summary -->
            <div class="col-md-4">
                <div class="info-card">
                    <h5><i class="fas fa-calendar-week"></i> This Week</h5>
                    <div class="stat-box">
                        <h3><?php echo number_format($total_hours_week, 1); ?></h3>
                        <p class="mb-0">Total Hours</p>
                    </div>
                    <div class="mt-3 text-center">
                        <small class="text-muted">Week of <?php echo date('M d, Y', strtotime($week_start)); ?></small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Today's Activity -->
        <div class="row">
            <div class="col-md-12">
                <div class="info-card">
                    <h5><i class="fas fa-history"></i> Today's Activity</h5>
                    <?php if (count($today_log) > 0): ?>
                        <?php foreach ($today_log as $entry): ?>
                            <div class="time-entry">
                                <span>
                                    <i class="fas fa-<?php echo $entry['action'] === 'IN' ? 'arrow-right' : 'arrow-left'; ?>"></i>
                                    Clock <?php echo ucfirst(strtolower($entry['action'])); ?>
                                </span>
                                <strong><?php echo date('g:i A', strtotime($entry['time'])); ?></strong>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted text-center mb-0">No activity recorded today.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
