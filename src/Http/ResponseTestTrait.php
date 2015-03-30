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
    /**
     * Asserts, that response contains a specified string
     *
     * @param string $str
     * @param bool   $ignoreCase
     */
    public function assertResponseContainsString($str, $ignoreCase = false)
    {
        $regExp = $this->getRegExpForContains($str, $ignoreCase);
        $this->assertRegExp(
            $regExp,
            $this->response->getContent(),
            "Expected that response contains string '$str', but it does not"
        );
    }

    /**
     * Asserts, that response does not contain a specified string
     *
     * @param string $str
     * @param bool   $ignoreCase
     */
    public function assertResponseNotContainsString($str, $ignoreCase = false)
    {
        $regExp = $this->getRegExpForContains($str, $ignoreCase);
        $this->assertNotRegExp(
            $regExp,
            $this->response->getContent(),
            "Expected that response does not contain string '$str', but it does"
        );
    }

    /**
     * Asserts, that returned DOM HTML model contains exactly one element, matching filter (CSS selector) provided
     *
     * @param string $filter Filter, supported by Symfony DOM crawler. CSS selector for example
     */
    public function assertDomHasOneElement($filter)
    {
        $this->assertDomHasElements($filter, 1);
    }

    /**
     * Asserts, that returned DOM HTML model contains exactly specified number of elements, matching filter (CSS
     * selector) provided
     *
     * @param string $filter Filter, supported by Symfony DOM crawler. CSS selector for example
     * @param int    $count
     */
    public function assertDomHasElements($filter, $count)
    {
        $crawler = (new Crawler($this->response->getContent()))->filter($filter);
        $this->assertCount($count, $crawler, "Expected DOM component count $count, actual ".count($crawler));
    }

    /**
     * Asserts, that returned DOM HTML model contains exactly one element, matching filter (CSS selector) provided
     *
     * @param string $filter Filter, supported by Symfony DOM crawler. CSS selector for example
     * @param int    $count
     */
    public function assertDomHasAtLeastElements($filter, $count)
    {
        $crawler = (new Crawler($this->response->getContent()))->filter($filter);
        $this->assertGreaterThanOrEqual(
            $count,
            count($crawler),
            "Expected DOM component count to be at least $count, actual ".count($crawler)
        );
    }

    /**
     * @param $str
     * @param $ignoreCase
     *
     * @return string
     */
    protected function getRegExpForContains($str, $ignoreCase)
    {
        $regExp = preg_quote($str, '|');
        $regExp = "|$regExp|";
        if ($ignoreCase) {
            $regExp .= 'i';

            return $regExp;
        }

        return $regExp;
    }
}
