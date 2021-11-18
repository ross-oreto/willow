<?php declare(strict_types=1);

namespace Oreto\Willow\tests;

use Base;
use Oreto\Willow\App;
use Oreto\Willow\Willow;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase {
    private static Base $f3;

    /**
     * This method is called before the first test of this test class is run.
     */
    public static function setUpBeforeClass(): void {
        putenv("mode=test");
        $f3 = Base::instance();
        $f3->set('QUIET', true);
        self::$f3 = Willow::equip($f3, [App::routes()]);
    }

    public function testHomePageWorks() {
        $f3 = self::$f3;
        $this->assertNull($f3->mock('GET /'));
        $test = $f3->get('RESPONSE');
        $this->assertStringContainsString(Willow::dict('name'), $test);
        $this->assertStringContainsString("(test)", $test);
    }
}