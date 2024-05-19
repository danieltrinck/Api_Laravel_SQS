<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SendMessageSQS;
use App\Http\Controllers\Auth;

Route::post('/sendMessageSQS', [SendMessageSQS::class,'sendSQS'])->middleware('auth:sanctum');
Route::post('/getToken', [Auth::class,'getToken']);