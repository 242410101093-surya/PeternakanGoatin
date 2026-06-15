<?php
$env = file_get_contents('.env');
$env = preg_replace('/FILESYSTEM_DISK=.*/', 'FILESYSTEM_DISK=supabase', $env);
file_put_contents('.env', $env);
echo "Updated .env\n";
