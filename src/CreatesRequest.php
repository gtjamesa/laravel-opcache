<?php

namespace Appstract\Opcache;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

trait CreatesRequest
{
    /**
     * @param string $command
     * @param array  $parameters
     *
     * @return \Illuminate\Http\Client\Response
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function sendRequest(string $command, array $parameters = []): Response
    {
        return Http::withHeaders(config('opcache.headers'))
            ->withOptions(['verify' => config('opcache.verify')])
            ->get(
                $this->buildOpcacheUrl($command),
                array_merge(['key' => Crypt::encrypt('opcache')], $parameters)
            )
            ->throw();
    }

    private function buildOpcacheUrl(string $command): string
    {
        return rtrim(config('opcache.url'), '/') . '/' . trim(config('opcache.prefix'), '/') . '/' . ltrim($command, '/');
    }
}
