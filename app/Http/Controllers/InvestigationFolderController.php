<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvestigationFolderStoreRequest;
use App\Http\Requests\InvestigationFolderUpdateRequest;
use App\Http\Resources\InvestigationFolderResource;
use App\Models\InvestigationFolder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvestigationFolderController extends Controller
{
    public function index(Request $request): Response
    {
        $investigationFolders = InvestigationFolder::all();
    }

    public function store(InvestigationFolderStoreRequest $request): Response
    {
        $investigationFolder = InvestigationFolder::create($request->validated());
    }

    public function show(Request $request, InvestigationFolder $investigationFolder): InvestigationFolderResource
    {
        return new InvestigationFolderResource($investigationFolder);
    }

    public function update(InvestigationFolderUpdateRequest $request, InvestigationFolder $investigationFolder): InvestigationFolderResource
    {
        $investigationFolder->update($request->validated());

        return new InvestigationFolderResource($investigationFolder);
    }

    public function destroy(Request $request, InvestigationFolder $investigationFolder): Response
    {
        $investigationFolder->delete();

        return response()->noContent();
    }
}
