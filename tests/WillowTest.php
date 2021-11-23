<?php declare(strict_types=1);

namespace Oreto\Willow\Test;

use Base;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Oreto\F3Willow\Willow;
use Oreto\Willow\controllers\App;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class WillowTest extends TestCase {
    private static string $MODE = "test";
    private static ?Client $http;
    private static Process $process;

    /**
     * This method is called before the first test of this test class is run.
     */
    public static function setUpBeforeClass(): void {
        putenv("mode=".self::$MODE);
        $PORT = "8002";
        // run the server in test mode here
        self::$process = new Process(["php" ,"-S", "localhost:$PORT", "server.php"]
            , "../webapp"
            , ["mode" => self::$MODE]);
        self::$process->start();
        usleep(100000); //wait for server to get going
        self::$http = new Client(['base_uri' => "http://localhost:$PORT", 'http_errors' => false]);

        Willow::equip(Base::instance(), [App::routes()]);
    }

    /**
     * This method is called after the last test of this test class is run.
     */
    public static function tearDownAfterClass(): void {
        // stop the server
        self::$process->stop();
        self::$http = null;
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
    public function test404() {
        $response = self::$http->request('GET', '/nope');
        self::assertEquals(404, $response->getStatusCode());
        $this->assertStringContainsString(Willow::dict("404.message"), $response->getBody()->getContents());
    }
}