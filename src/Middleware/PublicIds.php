<?php

namespace Bondacom\LaravelHashids\Middleware;

use Bondacom\LaravelHashids\RequestDecoder;
use Bondacom\LaravelHashids\ResponseEncoder;
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
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request = app(RequestDecoder::class)->handle($request);

        $response = $next($request);

        return app(ResponseEncoder::class)->handle($response);
    }
}