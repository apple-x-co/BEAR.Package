<?php
/**
 * This file is part of the BEAR.Package package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\Package\Provide\Error;

use BEAR\Sunday\Extension\Router\RouterMatch;
use PHPUnit\Framework\TestCase;

class DevVndErrorPageTest extends TestCase
{
    /**
     * @var DevVndErrorPage
     */
    private $page;

    public function setUp()
    {
        parent::setUp();
        $e = new \LogicException('bear');
        $request = new RouterMatch();
        list($request->method, $request->path, $request->query) = ['get', '/', []];
        $this->page = (new DevVndErrorPageFactory())->newInstance($e, $request);
    }

    public function testToString()
    {
        $this->page->toString();
        $this->assertSame(500, $this->page->code);
        $this->assertArrayHasKey('content-type', $this->page->headers);
        $this->assertSame('application/vnd.error+json', $this->page->headers['content-type']);
        $this->assertContains('{
    "message": "Internal Server Error",
    "logref": "{logref}",
    "request": "get /",
    "exceptions": "LogicException(bear)",
    "file": "' . __FILE__, $this->page->view);
    }
}
