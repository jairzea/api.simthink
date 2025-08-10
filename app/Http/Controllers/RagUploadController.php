<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RagUploadStoreRequest;
use App\Http\Resources\RagUploadResource;
use App\Models\RagUpload;
use App\Services\RagUploadService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RagUploadController extends Controller {
    public function __construct(private readonly RagUploadService $service) {}

    public function store(RagUploadStoreRequest $request): JsonResponse {
        $userId = $request->user()->id ?? Auth::user()->id;
        $uploads = $this->service->uploadFiles($userId, $request->file('files'), $request->string('investigation_id'));

        return response()->json([
            'message' => 'Archivos cargados correctamente',
            'data' => RagUploadResource::collection(collect($uploads))
        ]); 
    } 

    public function destroy(RagUpload $upload): JsonResponse {
        $this->authorize('delete', $upload); // política opcional
        $this->service->delete($upload);
        return response()->json(['message' => 'Archivo eliminado.']);
    }
}