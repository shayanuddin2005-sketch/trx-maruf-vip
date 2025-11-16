<?php
// Vercel-এর জন্য সেশন সেভ করার লোকেশন ঠিক করা
ini_set('session.save_path', '/tmp');

// PHP session শুরু করা (লগইন মনে রাখার জন্য)
session_start();

// আপনার গোপন পাসওয়ার্ড এখানে সেট করুন
// এই পাসওয়ার্ডটি পরিবর্তন করলেই সবার জন্য পাসওয়ার্ড পরিবর্তন হয়ে যাবে
$ADMIN_PASSWORD = getenv('ADMIN_PASSWORD');
?>
