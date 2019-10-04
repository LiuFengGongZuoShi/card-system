<?php
use Illuminate\Support\Facades\Schema; use Illuminate\Database\Schema\Blueprint; use Illuminate\Database\Migrations\Migration; class AddInventoryToProducts extends Migration { public function up() { if (!Schema::hasColumn('products', 'inventory')) { Schema::table('products', function (Blueprint $sp758f0c) { $sp758f0c->tinyInteger('inventory')->default(\App\User::INVENTORY_AUTO)->after('enabled'); $sp758f0c->tinyInteger('fee_type')->default(\App\User::FEE_TYPE_AUTO)->after('inventory'); }); } } public function down() { foreach (array('inventory', 'fee_type') as $sp1d345f) { try { Schema::table('products', function (Blueprint $sp758f0c) use($sp1d345f) { $sp758f0c->dropColumn($sp1d345f); }); } catch (\Throwable $sp803aea) { } } } }