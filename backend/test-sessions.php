<?php

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $count = DB::table('sessions')->count();
    echo "sessions: {$count}\n";
} catch (Exception $e) {
    echo "error: {$e->getMessage()}\n";
}
