<?php

namespace Bondacom\LaravelHashids;

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
class RequestDecoder
{
    /**
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * @var \Bondacom\LaravelHashids\Converter
     */
    private $converter;

    /**
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Request
     */
    public function handle(Request $request)
    {
        $this->converter = app(Converter::class);
        $this->request = clone $request;

        $this->decodeHeader()
            ->decodeRouteParameters()
            ->decodeQueryParameters();

        return $this->request;
    }

    /**
     * @return $this
     */
    protected function decodeHeader()
    {
        $headers = array_map('current', $this->request->headers->all());
        $headersDecoded = $this->converter->decode($headers);

        $this->request->headers->replace($headersDecoded);

        return $this;
    }

    /**
     * @return $this
     */
    protected function decodeRouteParameters()
    {
        $parameters = $this->request->route()->parameters();
        //IMPORTANT: Pass onlyids as false. Must decode all route parameters!
        $parametersDecoded = $this->converter->decode($parameters, false);

        foreach ($parametersDecoded as $key => $value) {
            $this->request->route()->setParameter($key, $value);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function decodeQueryParameters()
    {
        $parameters = $this->request->all();

        $this->request->replace($this->converter->decode($parameters));

        return $this;
    }
}