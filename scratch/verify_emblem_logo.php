<?php
$html = file_get_contents('http://localhost:8000/login.php');

$checks = [
    'Official Gold Crest Image' => str_contains($html, 'official-gold-crest.png'),
    'Customer Portal Login Title' => str_contains($html, 'Customer Portal'),
    'Login Accent Text' => str_contains($html, 'Login'),
    'Diamond Divider' => str_contains($html, '◆'),
    'Access your project quotes' => str_contains($html, 'Access your project quotes'),
    'Email Field' => str_contains($html, 'name="email"'),
    'Password Field' => str_contains($html, 'name="password"'),
    'Log In Button' => str_contains($html, 'Log In to Portal'),
    'Register Here Link' => str_contains($html, 'register.php'),
    'Admin Portal Link' => str_contains($html, 'admin/login.php'),
];

foreach ($checks as $name => $ok) {
    echo "$name: " . ($ok ? "PASS" : "FAIL") . "\n";
}
