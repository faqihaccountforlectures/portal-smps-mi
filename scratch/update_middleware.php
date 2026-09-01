<?php

$file = 'd:\pkl\portal-smps-mi\routes\web.php';
$content = file_get_contents($file);

// 1. Wrap Admin routes (between line 81 and 233)
// Wait, to be safe, let's find the start and end of admin routes.
$admin_start_marker = "// ==========================================\n// ROUTES MASTER DATA TAHUN AJARAN (ACADEMIC YEARS)\n// ==========================================";
$admin_end_marker = "// ==========================================\n// ROUTES HALAMAN KHUSUS GURU\n// ==========================================";

$parts = explode($admin_start_marker, $content);
$before_admin = $parts[0];
$rest = $parts[1];

$parts2 = explode($admin_end_marker, $rest);
$admin_routes = $parts2[0];
$after_admin = $parts2[1];

// Indent admin routes
$lines = explode("\n", $admin_routes);
$indented_admin_routes = "";
foreach ($lines as $line) {
    if (trim($line) === "") {
        $indented_admin_routes .= "\n";
    } else {
        $indented_admin_routes .= "    " . $line . "\n";
    }
}
// Remove trailing newline
$indented_admin_routes = rtrim($indented_admin_routes, "\n");

$new_admin_block = "Route::middleware(['auth', 'role:admin'])->group(function () {\n\n    " . trim($admin_start_marker) . "\n" . $indented_admin_routes . "\n\n});\n\n";

$new_content = $before_admin . $new_admin_block . $admin_end_marker . $after_admin;

// 2. Update Guru Middleware
$new_content = str_replace(
    "Route::middleware(['auth'])->prefix('guru')->group(function () {",
    "Route::middleware(['auth', 'role:guru'])->prefix('guru')->group(function () {",
    $new_content
);

// 3. Update Siswa Middleware
$new_content = str_replace(
    "Route::middleware(['auth'])->group(function () {\n    // Menampilkan profil siswa dan form pengaturan akun\n    Route::get('/siswa/profile'",
    "Route::middleware(['auth', 'role:siswa'])->group(function () {\n    // Menampilkan profil siswa dan form pengaturan akun\n    Route::get('/siswa/profile'",
    $new_content
);

file_put_contents($file, $new_content);

echo "Middleware updated successfully!\n";
