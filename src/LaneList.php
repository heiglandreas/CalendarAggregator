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

namespace Org_Heigl\CalendarAggregator;

use Org_Heigl\IteratorTrait\IteratorTrait;

class LaneList implements \Iterator, \Countable
{
    use IteratorTrait;

    private $lanes;

    /**
     * Get the array the iterator shall iterate over.
     *
     * @return array
     */
    protected function & getIterableElement()
    {
        return $this->lanes;
    }

    /**
     * Count elements of an object
     *
     * @link  http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->lanes);
    }

    public function add(Appointment $appointment)
    {
        foreach ($this->lanes as $lane) {
            if (! $lane->isFree($appointment->getStart(), $appointment->getEnd())) {
                continue;
            }
            $lane->add($appointment);

            return;
        }

        $lane = new Lane();
        $lane->add($appointment);

        $this->lanes[] = $lane;
    }


}
