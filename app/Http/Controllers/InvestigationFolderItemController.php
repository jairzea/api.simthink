<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvestigationFolderItemStoreRequest;
use App\Http\Requests\InvestigationFolderItemUpdateRequest;
use App\Http\Resources\InvestigationFolderItemCollection;
use App\Http\Resources\InvestigationFolderItemResource;
use App\Models\InvestigationFolderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvestigationFolderItemController extends Controller
{
    public function index(Request $request): InvestigationFolderItemCollection
    {
        $investigationFolderItems = InvestigationFolderItem::all();

        return new InvestigationFolderItemCollection($investigationFolderItems);
    }

    public function store(InvestigationFolderItemStoreRequest $request): Response
    {
        $investigationFolderItem = InvestigationFolderItem::create($request->validated());
    }

    public function show(Request $request, InvestigationFolderItem $investigationFolderItem): InvestigationFolderItemResource
    {
        return new InvestigationFolderItemResource($investigationFolderItem);
    }

    public function update(InvestigationFolderItemUpdateRequest $request, InvestigationFolderItem $investigationFolderItem): InvestigationFolderItemResource
    {
        $investigationFolderItem->update($request->validated());

        return new InvestigationFolderItemResource($investigationFolderItem);
    }

    public function destroy(Request $request, InvestigationFolderItem $investigationFolderItem): Response
    {
        $investigationFolderItem->delete();

        return response()->noContent();
    }
}
