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
 * @since     14.03.2017
 * @link      http://github.com/heiglandreas/org.heigl.CalendarAggregator
 */

namespace Org_Heigl\CalendarAggregator;

class Aggregator
{
    private $events;

    public function __construct()
    {
        $this->events = new EventList();
    }

    public function add(CalendarResourceInterface $calResource)
    {
        foreach ($calResource->getEntries() as $entry) {
            $this->events->append($entry);
        }
    }

    public function getList()
    {
        return $this->events;
    }

    public function getRange(\DateTimeInterface $start, \DateTimeInterface $end) : Range
    {
        $list = new Range();

        /** @var \Sabre\VObject\Component\VCalendar $calendar */
        foreach ($this->events as $calendar) {
            $cal = $calendar->expand($start, $end);
            if (! isset($cal->VEVENT)) {
                continue;
            }
            foreach ($cal->VEVENT as $event) {
                $list->add(new Appointment($event), $calendar);
            }
        }

        return $list;
    }
}
