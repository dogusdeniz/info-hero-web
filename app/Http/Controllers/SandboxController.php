<?php

namespace App\Http\Controllers;

use App\Libraries\LLama;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class SandboxController extends Controller
{
    public function llmHealthcheck()
    {
        return LLama::healthcheck();
    }

    public function llmEmbedding()
    {
        return LLama::embedding("Example text for embedding : lorem ipsum dolor sit amet");
    }

    public function llmDocumentUpload(Request $request)
    {
        // create fake UploadFile
        $tmpFile = tmpfile();

        fwrite($tmpFile, 'Hello, World!');

        $streamMetaData = stream_get_meta_data($tmpFile);

        $uri = $streamMetaData['uri'];

        $file = new UploadedFile($uri, 'file.txt', 'text/plain', 1024, true);

        return LLama::documentUpload($file);
    }

    public function llmTextUpload()
    {
        return LLama::textUpload("test-file.txt", "Example text for embedding : lorem ipsum dolor sit amet 2");
    }

    public function llmDeleteDocument(string $uuid)
    {
        return LLama::deleteDocument($uuid);
    }

    public function llmDocumentList()
    {
        return LLama::documentList();
    }

    public function llmCompletion(Request $request)
    {
        $stream = $request->query('stream', false);
        return LLama::completion("Gök yüzü neden mavi?", stream: $stream);
    }
}
