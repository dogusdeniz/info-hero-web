<?php

return [
    /**
     * The host of the LLama server.
     */
    'host' => env('LLAMA_HOST', 'localhost'),

    /**
     * The port of the LLama server.
     */
    'port' => env('LLAMA_PORT', 8080),

    /**
     * The path of the LLama server.
     */
    'path' => env('LLAMA_PATH', '/api/'),

    /**
     * The key to authenticate with the LLama server.
     */
    'key' => env('LLAMA_KEY'),
];
