<?php

namespace Bondacom\LaravelHashids;

use Hashids\Hashids;
use Illuminate\Http\Request;

/**
 * Class RequestDecoder
 *
 * Convert public ids (from query and headers) and route parameters to system ids
 *   - IDs Query parameters :
 *     Ex: http://example.dev/users?name=John&provider_id=daQm9M4qejxl  decode only provider_id
 *   - Route parameters:
 *     Ex: http://example.dev/providers/daQm9M4qejxl/users/2AgmKnp29Bjv/orders decode ALL the route parameters
 *   - IDs header:
 *     Ex: Consumer-ID,
 */
class RequestDecoder extends Converter
{
    /**
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * @var \Hashids\Hashids;
     */
    private $hashids;

    /**
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Request
     */
    public function handle(Request $request)
    {
        $this->request = clone $request;
        $this->hashids = app(Hashids::class);

        $this->decodeHeaders()
            ->decodeRouteParameters()
            ->decodeQueryParameters();

        return $this->request;
    }

    /**
     * @return $this
     */
    protected function decodeHeaders()
    {
        $params = array_map('current', $this->request->headers->all());
        $newHeaders = $this->decode($params, $this->config('headers'));

        $this->request->headers->replace($newHeaders);

        return $this;
    }

    /**
     * @return $this
     */
    protected function decodeRouteParameters()
    {
        $params = $this->request->route()->parameters();
        $newRouteParams = $this->decode($params, $this->config('query_parameters'));

        foreach ($newRouteParams as $key => $value) {
            $this->request->route()->setParameter($key, $value);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function decodeQueryParameters()
    {
        $params = $this->request->all();
        $newQueryParams = $this->decode($params, $this->config('query_parameters'));
        $this->request->replace($newQueryParams);

        return $this;
    }

    /**
     * Decode hash ids to system ids
     *
     * @param array $parameters
     * @param array $config
     * @return array
     */
    private function decode(array $parameters, array $config)
    {
        return $this->mapValues($parameters, $config, function ($value) {
            return $this->hashids->decode($value)[0];
        }, true);
    }
}