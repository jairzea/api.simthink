<?php

namespace App\Http\Controllers;

use App\Http\Requests\RagUploadStoreRequest;
use App\Http\Requests\RagUploadUpdateRequest;
use App\Http\Resources\RagUploadResource;
use App\Models\RagUpload;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RagUploadController extends Controller
{
    public function index(Request $request): Response
    {
        $ragUploads = RagUpload::all();
    }

    public function store(RagUploadStoreRequest $request): Response
    {
        $ragUpload = RagUpload::create($request->validated());
    }

    public function show(Request $request, RagUpload $ragUpload): RagUploadResource
    {
        return new RagUploadResource($ragUpload);
    }

    public function update(RagUploadUpdateRequest $request, RagUpload $ragUpload): RagUploadResource
    {
        $ragUpload->update($request->validated());

        return new RagUploadResource($ragUpload);
    }

    public function destroy(Request $request, RagUpload $ragUpload): Response
    {
        $ragUpload->delete();

        return response()->noContent();
    }
}
