<?php
// submit-form.php

require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    if (!empty($name) && !empty($email) && !empty($message)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact_leads (name, email, subject, message) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $subject, $message]);
            
            // Send Email Notification to Admin
            notify_admin_new_lead("General Contact", $name, $message);

            header('Location: index.php?success=1#contact');
            exit;
        } catch (PDOException $e) {
            die("Error submitting form: " . $e->getMessage());
        }
    } else {
        header('Location: index.php?error=1#contact');
        exit;
    }
}
?>
