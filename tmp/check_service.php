<?php
require __DIR__ . "/../vendor/autoload.php";
$app = require __DIR__ . "/../bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$services = App\Models\ChurchService::where("church_id", 1)->get();
$codes = App\Models\QrCode::whereNull("church_service_id")->get();
echo "SERVICES:\n";
foreach ($services as $s) {
    echo $s->id . " " . $s->slug . " " . $s->name . " " . $s->status . "\n";
}
echo "CODES:\n";
foreach ($codes as $q) {
    echo $q->id . " " . $q->code . " " . $q->church_id . " " . var_export($q->church_service_id, true) . "\n";
}
