<?php
$content = file_get_contents('.env');
// Clean out null bytes created by powershell
$content = str_replace("\0", "", $content);
file_put_contents('.env', $content);
echo "Fixed .env encoding.\n";
