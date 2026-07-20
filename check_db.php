<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$categorias = DB::table('categoria_platillos')->get();
echo "Categorias:\n";
foreach($categorias as $c) {
    echo " - [{$c->id}] {$c->nombre}\n";
}

$platillos = DB::table('platillos')->get();
echo "\nPlatillos:\n";
foreach($platillos as $p) {
    echo " - [{$p->id}] {$p->nombre} (Cat: {$p->categoria_platillo_id})\n";
}
