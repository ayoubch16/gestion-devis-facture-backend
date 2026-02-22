<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\SharedController;
use App\Http\Controllers\BlController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PdfExportController;
use App\Http\Controllers\TraceabilityController;

// Article routes
Route::get('/articles', [ArticleController::class, 'index']);
Route::post('/articles', [ArticleController::class, 'store']);
Route::get('/articles/{id}', [ArticleController::class, 'show']);
Route::put('/articles/{id}', [ArticleController::class, 'update']);
Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);
Route::get('/articles/categories', [ArticleController::class, 'categories']);

// Client routes
Route::get('/clients', [ClientController::class, 'index']);
Route::post('/clients', [ClientController::class, 'store']);
Route::get('/clients/{id}', [ClientController::class, 'show']);
Route::put('/clients/{id}', [ClientController::class, 'update']);
Route::delete('/clients/{id}', [ClientController::class, 'destroy']);
Route::get('/clients/villes', [ClientController::class, 'villes']);

// Devis routes
Route::get('/devis', [DevisController::class, 'index']);
Route::get('/last-devis', [DevisController::class, 'latest']);
Route::post('/devis', [DevisController::class, 'store']);
Route::get('/devis/{id}', [DevisController::class, 'show']);
Route::put('/devis/{id}', [DevisController::class, 'update']);
Route::delete('/devis/{id}', [DevisController::class, 'destroy']);
Route::put('/devis/{id}/{statut}', [DevisController::class, 'updateStatut']);
Route::get('/devis/client/{clientId}', [DevisController::class, 'getByClient']);
Route::get('/devis/{id}/articles', [DevisController::class, 'getArticles']);

// Facture routes
Route::post('/factures/{id}', [FactureController::class, 'createFromDevis']);
Route::get('/factures/{id}', [FactureController::class, 'show']);
Route::get('/factures', [FactureController::class, 'index']);
Route::get('/last-factures', [FactureController::class, 'latest']);
Route::put('/factures/{id}', [FactureController::class, 'update']);
Route::delete('/factures/{id}', [FactureController::class, 'destroy']);
Route::put('/factures/{id}/{statut}', [FactureController::class, 'updateStatut']);

// BL routes
Route::post('/bls/{id}', [BlController::class, 'createFromDevis']);
Route::get('/bls', [BlController::class, 'index']);
Route::get('/last-bls', [BlController::class, 'latest']);
Route::put('/bls/{id}', [BlController::class, 'update']);
Route::delete('/bls/{id}', [BlController::class, 'destroy']);
Route::put('/bls/{id}/{statut}', [BlController::class, 'updateStatut']);

// User routes

Route::post('/users', [UserController::class, 'store']);
Route::get('/users', [UserController::class, 'index']);        // Liste tous
Route::get('/users/{id}', [UserController::class, 'show']);    // Un utilisateur
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

// Shared routes
Route::get('/shared/cptfacture', [SharedController::class, 'getCptFacture']);
Route::get('/shared/cptdevis', [SharedController::class, 'getCptDevis']);
Route::get('/shared/cptbl', [SharedController::class, 'getCptBl']);
Route::get('/shared/cptclient', [SharedController::class, 'getCptClient']);
Route::post('/shared/devis/send-email', [SharedController::class, 'sendDevisByEmail']);
Route::post('/shared/emails/send-with-attachment', [SharedController::class, 'sendEmail']);


Route::post('/users/login', [AuthController::class, 'login']);
Route::post('/users/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// PDF export route
Route::post('/download-pdf', [PdfExportController::class, 'downloadPdf']);

// Routes correspondant exactement à votre frontend Angular
Route::post('/traceability', [TraceabilityController::class, 'store']); // logOperation
Route::get('/traceability', [TraceabilityController::class, 'index']); // getAllOperations
Route::get('/traceability/{id}', [TraceabilityController::class, 'getByEntityId']); // getOperationsByEntity