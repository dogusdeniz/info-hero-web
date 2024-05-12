<?php

namespace App\Libraries;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LLama
{
    private static function getClient(): Client
    {
        $uri = new Uri(config('llama.host') . ':' . config('llama.port') . config('llama.path'));

        return new Client([
            'base_uri'        => $uri,
            'timeout'         => 0,
            'allow_redirects' => false,
            'http_errors'     => false,
        ]);
    }

    public static function healthcheck(): \Illuminate\Http\Response
    {
        $client = self::getClient();

        $response = $client->get('_health');

        return Response::make($response->getBody()->getContents(), $response->getStatusCode());
    }


    public static function embedding(string $input): \Illuminate\Http\Response
    {
        $client = self::getClient();

        $response = $client->post('embedding', [
            'json' => [
                'input' => $input,
            ],
        ]);

        return Response::make($response->getBody()->getContents(), $response->getStatusCode());
    }

    #region ------------------- Document ---------------------

    public static function documentUpload(UploadedFile $file): \Illuminate\Http\Response
    {
        $client = self::getClient();

        $response = $client->post('document/file', [
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => fopen($file->getRealPath(), 'r'),
                    'filename' => $file->getClientOriginalName(),
                ],
            ],
        ]);

        return Response::make($response->getBody()->getContents(), $response->getStatusCode());
    }

    public static function textUpload(string $fileName, string $text): \Illuminate\Http\Response
    {
        $client = self::getClient();

        $response = $client->post('document/text', [
            'json' => [
                'file_name' => $fileName,
                'text'     => $text,
            ],
        ]);

        return Response::make($response->getBody()->getContents(), $response->getStatusCode());
    }

    public static function deleteDocument(string $docId): \Illuminate\Http\Response
    {
        $client = self::getClient();

        $response = $client->delete('document/' . $docId);

        return Response::make($response->getBody()->getContents(), $response->getStatusCode());
    }

    public static function documentList(): \Illuminate\Http\Response
    {
        $client = self::getClient();

        $response = $client->get('document/list');

        return Response::make($response->getBody()->getContents(), $response->getStatusCode());
    }

    #endregion ------------------- Document ---------------------

    #region ------------------- Completion ---------------------

    public static function completion(
        string $prompt,
        string $systemPrompt = null,
        bool $useContext = false,
        array $contextFilter = null,
        bool $includeSources = true,
        bool $stream = false
    ): \Illuminate\Http\Response | StreamedResponse {
        $client = self::getClient();

        $response = $client->post('completions', [
            'json' => [
                'prompt'         => $prompt,
                'system_prompt'  => $systemPrompt,
                'use_context'    => $useContext,
                'context_filter' => $contextFilter,
                'include_sources' => $includeSources,
                'stream'         => $stream,
            ],
            'stream' => $stream,
        ]);

        if (!$stream) {
            return Response::make($response->getBody()->getContents(), $response->getStatusCode());
        }

        return response()->stream(function () use ($response, $client) {
            $remoteBody = $response->getBody();

            while (!$remoteBody->eof()) {
                $text = $remoteBody->read(10); // Adjust buffer size as needed

                if (connection_aborted()) {
                    $remoteBody->close();
                    break;
                }

                $text = str_replace("\t", "\\t", $text);
                $text = str_replace("\r", "\\r", $text);
                $text = str_replace("\n", "\\n", $text);

                try {
                    $charset = mb_detect_encoding($text);
                    $text = iconv($charset, 'UTF-8', $text);
                } catch (\Exception $e) {
                    // do nothing
                }

                echo "event:update\n";
                echo 'data:' . $text;
                echo "\n\n";
                ob_flush();
                flush();
            }

            echo "event:end\n";
            echo 'data:<END_STREAMING_SSE>';
            echo "\n\n";
            ob_flush();
            flush();
        }, 200, [
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Content-Type' => 'text/event-stream',
        ]);
    }

    #endregion ------------------- Completion ---------------------

    #region ------------------- Chat ---------------------

    public static function chat(
        array $messages,
        bool $useContext = false,
        array $contextFilter = null,
        bool $includeSources = true,
        bool $stream = false
    ): \Illuminate\Http\Response | StreamedResponse {
        $client = self::getClient();

        $response = $client->post('chat/completions', [
            'json' => [
                'messages'       => $messages,
                'use_context'    => $useContext,
                'context_filter' => $contextFilter,
                'include_sources' => $includeSources,
                'stream'         => $stream,
            ],
        ]);

        if (!$stream) {
            return Response::make($response->getBody()->getContents(), $response->getStatusCode());
        }

        return Response::stream(function () use ($response) {
            echo $response->getBody()->getContents();
        }, $response->getStatusCode());
    }

    #endregion ------------------- Chat ---------------------
}
