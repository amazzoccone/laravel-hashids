<?php

namespace Bondacom\LaravelHashids;

/**
 * Class ResponseEncoder
 *
 * Convert response ids to public ids
 */
class ResponseEncoder
{
    /**
     * @param $response
     * @return mixed
     */
    public function handle($response)
    {
        $content = json_decode($response->getContent(), true);
        $encodedContent = json_encode($this->converter->encode($content));
        $response->setContent($encodedContent);

        return $response;
    }
}