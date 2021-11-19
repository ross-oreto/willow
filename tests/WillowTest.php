<?php declare(strict_types=1);

namespace Oreto\Willow\Test;

use Base;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Oreto\Willow\Willow;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class WillowTest extends TestCase {
    private static string $MODE = "test";
    private static ?Base $f3;
    private static ?Client $http;
    private static Process $process;

    /**
     * This method is called before the first test of this test class is run.
     */
    public static function setUpBeforeClass(): void {
        putenv("mode=".self::$MODE);
        $PORT = "8002";
        // run the server in test mode here
        self::$process = new Process(["php" ,"-S", "localhost:$PORT", "test-server.php"]
            , "../webapp"
            , ["mode" => self::$MODE]);
        self::$process->start();
        usleep(100000); //wait for server to get going

        // equip Willow for static testing
        self::$f3 = Base::instance();
        self::$f3 ->set('QUIET', true);
        Willow::equip(self::$f3 , [TestApp::routes()]);
        self::$http = new Client(['base_uri' => "http://localhost:$PORT", 'http_errors' => false]);
    }

    /**
     * This method is called after the last test of this test class is run.
     */
    public static function tearDownAfterClass(): void {
        // stop the server
        self::$process->stop();
        self::$http = null;
        self::$f3 = null;
    }

    public function testMode() {
       $this->assertTrue(Willow::isTest());
       $this->assertTrue(Willow::isMode(self::$MODE));
       $this->assertFalse(Willow::isDeployed());
       $this->assertFalse(Willow::isDev());
       $this->assertFalse(Willow::isStage());
       $this->assertFalse(Willow::isProd());
    }

    public function testAsset() {
        $f3 = self::$f3;
        self::assertEquals($f3->get("BASE").Willow::$ASSETS_PATH."/app.js", Willow::asset("app.js"));
        self::assertEquals($f3->get("BASE").Willow::$ASSETS_PATH."/dist/app.css", Willow::asset("app.css", true));
    }

    public function testLogging() {
        $f3 = self::$f3;
        Willow::getLogger()->info("testing");
        $fileName = $f3->get("LOGS").$f3->get("logName");
        $this->assertTrue(file_exists($fileName));
        $this->assertStringEndsWith("testing  \n", file_get_contents($fileName));
    }

    public function testRouter() {
        $router = Willow::getRouter();
        $this->assertEquals(3, sizeof($router->getRoutes()));
        $this->assertEquals(3, sizeof($router->getRoutes(TestApp::class)));
        $route = $router->getRoute('home');
        $this->assertNotNull($route);
        $this->assertEquals("GET", $route->getMethod());
        $this->assertEquals(TestApp::class."->index", $route->getHandler());
    }

    public function testHomePageWorks() {
        $f3 = self::$f3;
        $this->assertNull($f3->mock('GET /'));
        $test = $f3->get('RESPONSE');
        $this->assertStringContainsString(Willow::dict('name'), $test);
        $mode = self::$MODE;
        $this->assertStringContainsString("($mode)", $test);
    }

    // **************************************** INTEGRATIONS ****************************************

    /*** @throws GuzzleException */
    public function testHomePageResponse() {
        $response = self::$http->request('GET', '/');
        self::assertEquals(200, $response->getStatusCode());
        $mode = self::$MODE;
        $this->assertStringContainsString("($mode)", $response->getBody()->getContents());
    }

    /*** @throws GuzzleException */
    public function test1Response() {
        $response = self::$http->request('GET', '/test1');
        self::assertEquals(200, $response->getStatusCode());
        $this->assertSame(TestApp::$TEST1_RESPONSE, $response->getBody()->getContents());
    }

    /*** @throws GuzzleException */
    public function test404() {
        $response = self::$http->request('GET', '/nope');
        self::assertEquals(404, $response->getStatusCode());
        $this->assertStringContainsString(Willow::dict("404.message"), $response->getBody()->getContents());
    }

    /*** @throws GuzzleException */
    public function test500() {
        $response = self::$http->request('GET', '/server-error');
        self::assertEquals(500, $response->getStatusCode());
        $this->assertStringContainsString("<title>500</title>", $response->getBody()->getContents());
    }
}