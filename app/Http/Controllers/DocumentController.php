<?php

namespace App\Http\Controllers;

use App\Http\Resources\DocumentResource;
use App\Libraries\LLama;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return DocumentResource::collection(auth()->user()->documents);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(501, 'Not implemented');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'file' => 'required|file|max:10240|mimes:pdf,docx,csv,doc,txt',
        ]);

        $file = $request->file('file');

        // Upload file to LLama
        $response = LLama::documentUpload($file);

        if ($response->status() !== 200) {
            abort($response->status(), ['message' => 'Failed to upload document to LLama. Please try again.']);
        }

        $body = json_decode($response->content());

        $docId = $body->data[0]->doc_id;

        // save the file to the storage
        $path = $file->store('documents');
        $name = $file->getClientOriginalName();
        $ext = $file->extension();
        $mime = $file->getMimeType();
        $size = $file->getSize();

        // Store the document
        $document = $request->user()->documents()->create([
            'uuid' => $docId,
            'name' => $name,
            'path' => $path,
            'ext' => $ext,
            'mime' => $mime,
            'size' => $size,
        ]);

        return new DocumentResource($document);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort(501, 'Not implemented');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort(501, 'Not implemented');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort(501, 'Not implemented');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        // Delete the document
        $document = $request->user()->documents()->findOrFail($id);

        // Delete the document from LLama
        $response = LLama::deleteDocument($document->uuid);

        if ($response->status() !== 204) {
            abort($response->status(), ['message' => 'Failed to delete document from LLama. Please try again.']);
        }

        $document->delete();

        return response()->noContent();
    }
}
