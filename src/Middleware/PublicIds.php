<?php

namespace Bondacom\LaravelHashids\Middleware;

use Bondacom\LaravelHashids\Converter;
use Closure;

/**
 * - Convert public ids (from query and headers) and route parameters to system ids before the request is handled by
 *   the application.
 *
 *   - IDs Query parameters :
 *     Ex: http://example.dev/users?name=John&provider_id=daQm9M4qejxl  decode only provider_id
 *   - Route parameters:
 *     Ex: http://example.dev/providers/daQm9M4qejxl/users/2AgmKnp29Bjv/orders decode ALL the route parameters
 *   - IDs header:
 *     Ex: Consumer-ID,
 *
 * - Convert system ids to public ids after the request is handled by the application.
 */
class PublicIds
{
    /**
     * @var \Bondacom\LaravelHashids\Converter
     */
    protected $converter;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->converter = app(Converter::class);

        $request = $this->decodeRequestIds($request);

        $response = $next($request);

        $this->encodeResponseIds($response);

        return $response;
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Request
     */
    protected function decodeRequestIds($request)
    {
        //Decode route parameters
        $parametersDecoded = $this->converter->decode($request->route()->parameters(), false);
        foreach ($parametersDecoded as $key => $value) {
            $request->route()->setParameter($key, $value);
        }

        //Decode header attributes
        $headers = array_map('current', $request->headers->all());
        $headersDecoded = $this->converter->decode($headers);
        $request->headers->replace($headersDecoded);

        //Decode request attributes
        $request->replace($this->converter->decode($request->all()));

        return $request;
    }

    /**
     * @param  $response
     */
    protected function encodeResponseIds($response)
    {
        $content = json_decode($response->getContent(), true);
        $encodedContent = json_encode($this->converter->encode($content));
        $response->setContent($encodedContent);
    }
}