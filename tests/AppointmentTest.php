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
 * @since     15.03.2017
 * @link      http://github.com/heiglandreas/org.heigl.CalendarAggregator
 */

namespace Org_Heigl\CalendarAggregatorTest;

use Org_Heigl\CalendarAggregator\Appointment;
use PHPUnit\Framework\TestCase;
use Sabre\VObject\Component\VEvent;
use Sabre\VObject\Reader;

class AppointmentTest extends TestCase
{
    /** @dataProvider intersectionProvider */
    public function testThatIntersectionIsCalculatedCorrectly($start, $end, $result)
    {
        $event = Reader::read(fopen(__DIR__  . '/_assets/testIntersection.ics', 'r'))->VEVENT;
        $appointment = new Appointment($event);

        $this->assertSame($result, $appointment->intersects($start, $end));
    }

    public function intersectionProvider()
    {
        return [[
            new \DateTimeImmutable('2012-01-09T14:00:00', new \DateTimeZone('UTC')),
            new \DateTimeImmutable('2012-01-09T16:00:00', new \DateTimeZone('UTC')),
            true
        ],[
            new \DateTimeImmutable('2012-01-09T13:00:00', new \DateTimeZone('UTC')),
            new \DateTimeImmutable('2012-01-09T15:00:00', new \DateTimeZone('UTC')),
            true
        ],[
            new \DateTimeImmutable('2012-01-09T15:00:00', new \DateTimeZone('UTC')),
            new \DateTimeImmutable('2012-01-09T17:00:00', new \DateTimeZone('UTC')),
            true
        ],[
            new \DateTimeImmutable('2012-01-09T14:30:00', new \DateTimeZone('UTC')),
            new \DateTimeImmutable('2012-01-09T15:30:00', new \DateTimeZone('UTC')),
            true
        ],[
            new \DateTimeImmutable('2012-01-09T16:00:01', new \DateTimeZone('UTC')),
            new \DateTimeImmutable('2012-01-09T17:00:00', new \DateTimeZone('UTC')),
            false
        ],[
            new \DateTimeImmutable('2012-01-09T13:00:00', new \DateTimeZone('UTC')),
            new \DateTimeImmutable('2012-01-09T13:59:59', new \DateTimeZone('UTC')),
            false
        ]];
    }
}
