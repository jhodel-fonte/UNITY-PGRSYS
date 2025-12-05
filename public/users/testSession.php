<?php
session_start();

echo "<h2>Current Session Variables:</h2>";

// Check if the $_SESSION array is not empty
if (!empty($_SESSION)) {
    // 2. Loop through the $_SESSION array
    foreach ($_SESSION as $key => $value) {
        // Output the key (variable name) and its value

        // Handle arrays and objects separately, otherwise, just print the value
        if (is_array($value) || is_object($value)) {
            echo "<strong>Key:</strong> " . htmlspecialchars($key) . "<br>";
            echo "<strong>Value:</strong> <pre>" . htmlspecialchars(print_r($value, true)) . "</pre><hr>";
        } else {
            echo "<strong>Key:</strong> " . htmlspecialchars($key) . "<br>";
            echo "<strong>Value:</strong> " . htmlspecialchars($value) . "<br><hr>";
        }
    }
} else {
    echo "<p>No session variables are currently set.</p>";
}
?>