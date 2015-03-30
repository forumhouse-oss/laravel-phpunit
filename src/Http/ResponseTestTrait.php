<?php

namespace FHTeam\LaravelPHPUnit\Http;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Trait to help working with response contents
 *
 * @mixin \FHTeam\LaravelPHPUnit\SimpleTestBase
 */
trait ResponseTestTrait
{
    public function assertResponseContainsString($str)
    {
        $this->assertContains($str, $this->response->getContent());
    }

    public function assertDomHasOneElement($filter)
    {
        $this->assertDomHasElements($filter, 1);
    }

    public function assertDomHasElements($filter, $count)
    {
        $crawler = new Crawler($this->response->getContent());
        $this->assertCount($count, $crawler, "Expected DOM component count $count, actual ".count($crawler));
    }
}
