<?php
require __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\Storage;

$path = 'profile-photos/BZ299LmSOPdi1U8SiQoo3Filv3RLLm7OsdItOeZX.jpg';
$url = Storage::url($path);
echo "Path: $path\n";
echo "URL: $url\n";
echo "File exists: " . (file_exists(public_path('uploads/profile-photos/BZ299LmSOPdi1U8SiQoo3Filv3RLLm7OsdItOeZX.jpg')) ? 'Yes' : 'No') . "\n";
?>
