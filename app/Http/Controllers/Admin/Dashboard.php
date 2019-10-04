<?php
namespace App\Http\Controllers\Admin; use App\Library\CurlRequest; use App\Library\Response; use App\Order; use Illuminate\Http\Request; use App\Http\Controllers\Controller; class Dashboard extends Controller { function index(Request $sp16eb02) { $sp2b7fb1 = array('today' => array('count' => 0, 'paid' => 0, 'profit' => 0), 'yesterday' => array('count' => 0, 'paid' => 0, 'profit' => 0)); $spc3d1e4 = Order::whereUserId(\Auth::Id())->whereDate('paid_at', \Carbon\Carbon::now()->toDateString())->where(function ($spf5b559) { $spf5b559->where('status', Order::STATUS_PAID)->orWhere('status', Order::STATUS_SUCCESS); })->selectRaw('COUNT(*) as `count`,SUM(`paid`) as `paid`,SUM(`paid`-`sms_price`-`cost`-`fee`) as `profit`')->get()->toArray(); $sp6af450 = Order::whereUserId(\Auth::Id())->whereDate('paid_at', \Carbon\Carbon::yesterday()->toDateString())->where(function ($spf5b559) { $spf5b559->where('status', Order::STATUS_PAID)->orWhere('status', Order::STATUS_SUCCESS); })->selectRaw('COUNT(*) as `count`,SUM(`paid`) as `paid`,SUM(`paid`-`sms_price`-`cost`-`fee`) as `profit`')->get()->toArray(); if (isset($spc3d1e4[0]) && isset($spc3d1e4[0]['count'])) { $sp2b7fb1['today'] = array('count' => (int) $spc3d1e4[0]['count'], 'paid' => (int) $spc3d1e4[0]['paid'], 'profit' => (int) $spc3d1e4[0]['profit']); } if (isset($sp6af450[0]) && isset($sp6af450[0]['count'])) { $sp2b7fb1['yesterday'] = array('count' => (int) $sp6af450[0]['count'], 'paid' => (int) $sp6af450[0]['paid'], 'profit' => (int) $sp6af450[0]['profit']); } $sp2b7fb1['need_ship_count'] = Order::whereUserId(\Auth::Id())->where('status', Order::STATUS_PAID)->count(); $sp2b7fb1['login'] = \App\Log::where('action', \App\Log::ACTION_LOGIN)->latest()->firstOrFail(); return Response::success($sp2b7fb1); } function clearCache() { if (function_exists('opcache_reset')) { opcache_reset(); } try { \Artisan::call('cache:clear'); \Artisan::call('route:cache'); \Artisan::call('config:cache'); } catch (\Throwable $sp803aea) { return Response::fail($sp803aea->getMessage()); } return Response::success(); } function version() { return Response::success(array('version' => config('app.version'))); } function checkUpdate() { return Response::success(@json_decode(CurlRequest::get('https://raw.githubusercontent.com/Tai7sy/card-system/master/.version'), true)); } }