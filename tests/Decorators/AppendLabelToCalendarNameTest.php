<?php
/**
 * Copyright (c) Andreas Heigl<andreas@heigl.org>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @author    Andreas Heigl<andreas@heigl.org>
 * @copyright Andreas Heigl
 * @license   http://www.opensource.org/licenses/mit-license.php MIT-License
 * @since     29.03.2017
 * @link      http://github.com/heiglandreas/org.heigl.CalendarAggregator
 */

namespace Org_Heigl\CalendarAggregatorTest\Decorators;

use Org_Heigl\CalendarAggregator\CalendarResourceInterface;
use Org_Heigl\CalendarAggregator\Decorators\AppendLabelToCalendarName;
use PHPUnit\Framework\TestCase;
use Mockery as M;
use Sabre\VObject\Component\VCalendar;

class AppendLabelToCalendarNameTest extends TestCase
{
    public function testThatEventsAreModified()
    {
        $name = 'X-WR-CALNAME';
        $calendar = new VCalendar([]);
        $calendar->$name = 'Foo';

        $default = M::mock(CalendarResourceInterface::class);
        $default->shouldReceive('getEntries')->andReturn($calendar);

        $decorator = new AppendLabelToCalendarName($default, 'Label');

        $entries = $decorator->getEntries();
        $this->assertSame($calendar, $entries);
        $this->assertEquals('Foo - Label', (string) $entries->$name);
    }
}
