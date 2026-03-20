-- ================================================================
-- DataMaster Employee Integration: Database Migration Script
-- ================================================================
-- Version: 1.0
-- Date: 2026-02-05
-- Purpose: Extend existing schema to support Employee Time & Attendance
-- 
-- IMPORTANT: Create a backup before running this script!
-- Command: mysqldump -u root -p datamaster > backup_datamaster_20260205.sql
-- ================================================================

USE datamaster;

-- ================================================================
-- PHASE 1: Extend user_table to support Employees
-- ================================================================

-- Add new columns for employee functionality
ALTER TABLE user_table 
  ADD COLUMN user_type ENUM('visitor', 'employee', 'admin') NOT NULL DEFAULT 'visitor' 
    COMMENT 'Distinguishes between visitor, employee, and admin users',
  ADD COLUMN password_hash VARCHAR(255) NULL 
    COMMENT 'Hashed password for employee/admin authentication (NULL for visitors)',
  ADD COLUMN employee_id VARCHAR(50) NULL UNIQUE 
    COMMENT 'Unique employee identifier (e.g., EMP001, staff badge number)',
  ADD COLUMN department VARCHAR(100) NULL 
    COMMENT 'Employee department/division (e.g., IT, HR, Sales)',
  ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
    COMMENT 'Record creation timestamp',
  ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP 
    COMMENT 'Last modification timestamp';

-- Create index for faster employee lookups
CREATE INDEX idx_user_type ON user_table(user_type);
CREATE INDEX idx_employee_id ON user_table(employee_id);
CREATE INDEX idx_email_lookup ON user_table(email);

-- ================================================================
-- PHASE 2: Create attendance_log table for Employee Clocking
-- ================================================================

CREATE TABLE IF NOT EXISTS attendance_log (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL COMMENT 'Foreign key to user_table.id',
  employee_id VARCHAR(50) NOT NULL COMMENT 'Redundant employee_id for reporting efficiency',
  timestamp DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Exact clock-in or clock-out time',
  action ENUM('IN', 'OUT') NOT NULL COMMENT 'Clock IN or Clock OUT action',
  status VARCHAR(50) DEFAULT 'On Time' COMMENT 'Status: On Time, Late, Early Departure, etc.',
  ip_address VARCHAR(45) NULL COMMENT 'IP address for audit trail',
  notes TEXT NULL COMMENT 'Optional notes (e.g., reason for late arrival)',
  
  -- Foreign key constraint
  FOREIGN KEY (user_id) REFERENCES user_table(id) ON DELETE CASCADE,
  
  -- Indexes for performance
  INDEX idx_user_timestamp (user_id, timestamp),
  INDEX idx_employee_timestamp (employee_id, timestamp),
  INDEX idx_action (action),
  INDEX idx_date (DATE(timestamp))
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 
  COMMENT='Employee time and attendance tracking';

-- ================================================================
-- PHASE 3: Data Integrity & Constraints
-- ================================================================

-- Ensure all existing records are marked as visitors
UPDATE user_table 
SET user_type = 'visitor' 
WHERE user_type IS NULL OR user_type = '';

-- ================================================================
-- PHASE 4: Create Views for Reporting (Optional but Recommended)
-- ================================================================

-- View: Current Employee Status (who is clocked in right now)
CREATE OR REPLACE VIEW vw_current_employee_status AS
SELECT 
  u.employee_id,
  CONCAT(u.fname, ' ', u.lname) AS full_name,
  u.department,
  a.timestamp AS last_action_time,
  a.action AS last_action,
  CASE 
    WHEN a.action = 'IN' THEN 'Currently Working'
    WHEN a.action = 'OUT' THEN 'Clocked Out'
    ELSE 'Unknown'
  END AS current_status
FROM user_table u
LEFT JOIN attendance_log a ON u.id = a.user_id 
  AND a.timestamp = (
    SELECT MAX(timestamp) 
    FROM attendance_log 
    WHERE user_id = u.id
  )
WHERE u.user_type = 'employee'
ORDER BY u.employee_id;

-- View: Daily Attendance Summary
CREATE OR REPLACE VIEW vw_daily_attendance_summary AS
SELECT 
  DATE(timestamp) AS attendance_date,
  COUNT(DISTINCT CASE WHEN action = 'IN' THEN user_id END) AS total_clock_ins,
  COUNT(DISTINCT CASE WHEN action = 'OUT' THEN user_id END) AS total_clock_outs,
  COUNT(DISTINCT user_id) AS unique_employees
FROM attendance_log
GROUP BY DATE(timestamp)
ORDER BY attendance_date DESC;

-- ================================================================
-- VERIFICATION QUERIES (Run after migration to confirm success)
-- ================================================================

-- Check new columns in user_table
-- SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE 
-- FROM INFORMATION_SCHEMA.COLUMNS 
-- WHERE TABLE_SCHEMA = 'datamaster' AND TABLE_NAME = 'user_table';

-- Check attendance_log table structure
-- DESCRIBE attendance_log;

-- Verify indexes
-- SHOW INDEX FROM user_table WHERE Key_name IN ('idx_user_type', 'idx_employee_id');
-- SHOW INDEX FROM attendance_log;

-- Count existing records by user_type
-- SELECT user_type, COUNT(*) AS count FROM user_table GROUP BY user_type;

-- ================================================================
-- ROLLBACK SCRIPT (Keep safe in case you need to undo)
-- ================================================================

/*
-- WARNING: This will delete all employee data!
-- Only use if you need to completely reverse the migration

DROP VIEW IF EXISTS vw_current_employee_status;
DROP VIEW IF EXISTS vw_daily_attendance_summary;
DROP TABLE IF EXISTS attendance_log;

ALTER TABLE user_table
  DROP COLUMN user_type,
  DROP COLUMN password_hash,
  DROP COLUMN employee_id,
  DROP COLUMN department,
  DROP COLUMN created_at,
  DROP COLUMN updated_at,
  DROP INDEX idx_user_type,
  DROP INDEX idx_employee_id,
  DROP INDEX idx_email_lookup;
*/

-- ================================================================
-- END OF MIGRATION SCRIPT
-- ================================================================
