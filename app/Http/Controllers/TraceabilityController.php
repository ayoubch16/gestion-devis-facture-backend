<?php
// app/Http/Controllers/TraceabilityController.php

namespace App\Http\Controllers;

use App\Models\TraceOperation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TraceabilityController extends Controller
{
    /**
     * Enregistrer une opération de traçabilité
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'timestamp' => 'required|date',
            'operationType' => 'required|in:CREATE,UPDATE,DELETE,READ,STATUS_CHANGE,EMAIL_SENT,PDF_GENERATED',
            'entityType' => 'required|in:DEVIS,CLIENT,ARTICLE,FACTURE,BON_LIVRAISON,USER',
            'entityId' => 'required|integer|min:1',
            'userId' => 'required|integer|min:1',
            'userName' => 'required|string|max:255',
            'details' => 'required|string',
            'previousState' => 'nullable|array',
            'newState' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Convertir les noms de champ camelCase vers snake_case pour la base
            $operationData = [
                'timestamp' => $request->timestamp,
                'operation_type' => $request->operationType,
                'entity_type' => $request->entityType,
                'entity_id' => $request->entityId,
                'user_id' => $request->userId,
                'user_name' => $request->userName,
                'details' => $request->details,
                'previous_state' => $request->previousState,
                'new_state' => $request->newState
            ];

            $operation = TraceOperation::create($operationData);

            // Retourner les données au format camelCase pour Angular
            return response()->json([
                'id' => $operation->id,
                'timestamp' => $operation->timestamp,
                'operationType' => $operation->operation_type,
                'entityType' => $operation->entity_type,
                'entityId' => $operation->entity_id,
                'userId' => $operation->user_id,
                'userName' => $operation->user_name,
                'details' => $operation->details,
                'previousState' => $operation->previous_state,
                'newState' => $operation->new_state,
                'createdAt' => $operation->created_at,
                'updatedAt' => $operation->updated_at
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement de l\'opération',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer toutes les opérations
     */
    public function index(): JsonResponse
    {
        try {
            $operations = TraceOperation::orderBy('timestamp', 'desc')->get();

            // Convertir en camelCase pour Angular
            $formattedOperations = $operations->map(function ($operation) {
                return [
                    'id' => $operation->id,
                    'timestamp' => $operation->timestamp,
                    'operationType' => $operation->operation_type,
                    'entityType' => $operation->entity_type,
                    'entityId' => $operation->entity_id,
                    'userId' => $operation->user_id,
                    'userName' => $operation->user_name,
                    'details' => $operation->details,
                    'previousState' => $operation->previous_state,
                    'newState' => $operation->new_state,
                    'createdAt' => $operation->created_at,
                    'updatedAt' => $operation->updated_at
                ];
            });

            return response()->json($formattedOperations);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des opérations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les opérations par entité (GET /api/traceability/{id})
     * Note: Votre frontend attend entityId dans l'URL, pas entityType
     */
    public function getByEntityId($entityId): JsonResponse
    {
        try {
            $operations = TraceOperation::where('entity_id', $entityId)
                ->orderBy('timestamp', 'desc')
                ->get();

            // Convertir en camelCase pour Angular
            $formattedOperations = $operations->map(function ($operation) {
                return [
                    'id' => $operation->id,
                    'timestamp' => $operation->timestamp,
                    'operationType' => $operation->operation_type,
                    'entityType' => $operation->entity_type,
                    'entityId' => $operation->entity_id,
                    'userId' => $operation->user_id,
                    'userName' => $operation->user_name,
                    'details' => $operation->details,
                    'previousState' => $operation->previous_state,
                    'newState' => $operation->new_state,
                    'createdAt' => $operation->created_at,
                    'updatedAt' => $operation->updated_at
                ];
            });

            return response()->json($formattedOperations);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des opérations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les opérations par type d'entité et ID (alternative)
     */
    public function getByEntity(Request $request): JsonResponse
    {
        $validator = Validator::make($request->query(), [
            'entityType' => 'required|in:DEVIS,CLIENT,ARTICLE,FACTURE,BON_LIVRAISON,USER',
            'entityId' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $operations = TraceOperation::where('entity_type', $request->entityType)
                ->where('entity_id', $request->entityId)
                ->orderBy('timestamp', 'desc')
                ->get();

            $formattedOperations = $operations->map(function ($operation) {
                return [
                    'id' => $operation->id,
                    'timestamp' => $operation->timestamp,
                    'operationType' => $operation->operation_type,
                    'entityType' => $operation->entity_type,
                    'entityId' => $operation->entity_id,
                    'userId' => $operation->user_id,
                    'userName' => $operation->user_name,
                    'details' => $operation->details,
                    'previousState' => $operation->previous_state,
                    'newState' => $operation->new_state,
                    'createdAt' => $operation->created_at,
                    'updatedAt' => $operation->updated_at
                ];
            });

            return response()->json($formattedOperations);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des opérations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer une opération spécifique
     */
    public function show($id): JsonResponse
    {
        try {
            $operation = TraceOperation::findOrFail($id);

            return response()->json([
                'id' => $operation->id,
                'timestamp' => $operation->timestamp,
                'operationType' => $operation->operation_type,
                'entityType' => $operation->entity_type,
                'entityId' => $operation->entity_id,
                'userId' => $operation->user_id,
                'userName' => $operation->user_name,
                'details' => $operation->details,
                'previousState' => $operation->previous_state,
                'newState' => $operation->new_state,
                'createdAt' => $operation->created_at,
                'updatedAt' => $operation->updated_at
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Opération non trouvée',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Statistiques des opérations
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = TraceOperation::selectRaw('
                COUNT(*) as totalOperations,
                COUNT(DISTINCT entity_id) as totalEntities,
                COUNT(DISTINCT user_id) as totalUsers,
                operation_type as operationType,
                entity_type as entityType
            ')
            ->groupBy('operation_type', 'entity_type')
            ->get();

            return response()->json($stats);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}