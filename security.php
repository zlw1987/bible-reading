<?php
function h($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function url_param($value) {
    return rawurlencode((string) $value);
}

function input_int($source, $key, $default = 0) {
    if (!isset($source[$key])) {
        return $default;
    }

    $value = filter_var($source[$key], FILTER_VALIDATE_INT);
    return $value === false ? $default : (int) $value;
}

function redirect_to($location) {
    header('Location: ' . $location);
    exit;
}

function db_statement($connection, $sql, $types = '', &...$params) {
    $stmt = mysqli_prepare($connection, $sql);
    if (!$stmt) {
        error_log('Database prepare failed: ' . mysqli_error($connection));
        http_response_code(500);
        exit('Server error. Please try again later.');
    }

    if ($types !== '') {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    if (!mysqli_stmt_execute($stmt)) {
        error_log('Database execute failed: ' . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        http_response_code(500);
        exit('Server error. Please try again later.');
    }

    return $stmt;
}

function db_all($connection, $sql, $types = '', &...$params) {
    $stmt = db_statement($connection, $sql, $types, ...$params);
    $result = mysqli_stmt_get_result($stmt);
    $rows = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }

    mysqli_stmt_close($stmt);
    return $rows;
}

function db_one($connection, $sql, $types = '', &...$params) {
    $rows = db_all($connection, $sql, $types, ...$params);
    return $rows[0] ?? null;
}

function db_execute($connection, $sql, $types = '', &...$params) {
    $stmt = db_statement($connection, $sql, $types, ...$params);
    mysqli_stmt_close($stmt);
}

function csrf_token() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . h(csrf_token()) . '">';
}

function verify_csrf() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('Method not allowed.');
    }

    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals(csrf_token(), $token)) {
        http_response_code(403);
        exit('Invalid request token.');
    }
}
