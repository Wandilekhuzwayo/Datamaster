<?php
/**
 * Input Validation Helper
 * Provides functions to sanitize and validate user input
 * 
 * Usage:
 * - Include this file in form processing scripts
 * - Use sanitize functions before storing data
 * - Use validate functions to check input format
 */

/**
 * Sanitize a string for safe output/storage
 * @param string $input The raw input string
 * @return string Sanitized string
 */
function sanitize_string(string $input): string {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}

/**
 * Sanitize phone number to digits only
 * @param string $phone The phone input
 * @return string Digits only
 */
function sanitize_phone_number(string $phone): string {
    return preg_replace('/[^0-9]/', '', $phone);
}

/**
 * Validate email address format
 * @param string $email The email to validate
 * @return bool True if valid email format
 */
function validate_email(string $email): bool {
    return filter_var(trim($email), FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number (10-15 digits, allows + prefix)
 * @param string $phone The phone number to validate
 * @return bool True if valid phone format
 */
function validate_phone(string $phone): bool {
    $phone = preg_replace('/[\s\-\(\)]/', '', $phone);
    return preg_match('/^\+?[0-9]{10,15}$/', $phone) === 1;
}

/**
 * Validate name (letters, spaces, hyphens, apostrophes only)
 * @param string $name The name to validate
 * @param int $maxLength Maximum allowed length
 * @return bool True if valid name
 */
function validate_name(string $name, int $maxLength = 50): bool {
    $name = trim($name);
    if (strlen($name) > $maxLength || strlen($name) < 1) {
        return false;
    }
    return preg_match('/^[a-zA-Z\s\-\']+$/', $name) === 1;
}

/**
 * Validate required fields are not empty
 * @param array $fields Associative array of field names => values
 * @return array Array of empty field names, empty if all valid
 */
function validate_required(array $fields): array {
    $empty_fields = [];
    foreach ($fields as $name => $value) {
        if (empty(trim($value))) {
            $empty_fields[] = $name;
        }
    }
    return $empty_fields;
}

/**
 * Sanitize string for safe SQL (use with prepared statements)
 * @param mysqli $conn Database connection
 * @param string $input Raw input
 * @return string Escaped string
 */
function sanitize_for_db(mysqli $conn, string $input): string {
    return mysqli_real_escape_string($conn, trim($input));
}

/**
 * Validate file upload is an image
 * @param array $file The $_FILES array entry
 * @param int $maxSize Maximum file size in bytes (default 5MB)
 * @return array ['valid' => bool, 'error' => string|null]
 */
function validate_image_upload(array $file, int $maxSize = 5242880): array {
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
    $allowed_extensions = ['jpg', 'jpeg', 'png'];
    
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['valid' => false, 'error' => 'File upload failed.'];
    }
    
    // Check file size
    if ($file['size'] > $maxSize) {
        return ['valid' => false, 'error' => 'File too large. Maximum size is 5MB.'];
    }
    
    // Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime_type, $allowed_types)) {
        return ['valid' => false, 'error' => 'Invalid file type. Only JPEG and PNG allowed.'];
    }
    
    // Check extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowed_extensions)) {
        return ['valid' => false, 'error' => 'Invalid file extension.'];
    }
    
    return ['valid' => true, 'error' => null];
}

/**
 * Generate a secure random filename for uploads
 * @param string $extension The file extension
 * @return string Random filename
 */
function generate_secure_filename(string $extension = 'jpg'): string {
    return bin2hex(random_bytes(16)) . '_' . time() . '.' . $extension;
}
?>
