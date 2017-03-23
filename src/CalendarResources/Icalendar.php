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

namespace Org_Heigl\CalendarAggregator\CalendarResources;

use Org_Heigl\CalendarAggregator\CalendarResourceInterface;
use Org_Heigl\Color\Color;
use Org_Heigl\Color\Converter\XYZ2RGB;
use Org_Heigl\Color\Renderer\RendererFactory;
use Sabre\VObject\Property\FlatText;
use Sabre\VObject\Reader;
use Sabre\VObject\UUIDUtil;

class Icalendar implements CalendarResourceInterface
{
    private $entry;

    public function __construct(string $icalendarUrl, $streamcontext = null, $label = null, Color $color = null, Color $contrast = null)
    {
        $this->entry = Reader::read(fopen($icalendarUrl, 'r', null, $streamcontext), Reader::OPTION_FORGIVING);

        $uuid = 'X-WR-RELCALID';
        if (! $this->entry->$uuid) {
            $this->entry->add(new FlatText($this->entry, $uuid, UUIDUtil::getUUID()));
        }

        if (null !== $label) {
            $calName = 'X-WR-CALNAME';
            $this->entry->$calName = $this->entry->$calName . ' -   ' . $label;
        }

        if (null !== $color) {
            $converter = new XYZ2RGB();
            $content   = $converter->convertColor($color);
            $this->entry->add(new FlatText($this->entry, 'X-WDV-COLOR-RED', round($content[0], 0)));
            $this->entry->add(new FlatText($this->entry, 'X-WDV-COLOR-GREEN', round($content[1], 0)));
            $this->entry->add(new FlatText($this->entry, 'X-WDV-COLOR-BLUE', round($content[2], 0)));
        }

        if (null !== $contrast) {
            $converter = new XYZ2RGB();
            $content   = $converter->convertColor($contrast);
            $this->entry->add(new FlatText($this->entry, 'X-WDV-CONTRAST-RED', round($content[0] * 255, 0)));
            $this->entry->add(new FlatText($this->entry, 'X-WDV-CONTRAST-GREEN', round($content[1] * 255, 0)));
            $this->entry->add(new FlatText($this->entry, 'X-WDV-CONTRAST-BLUE', round($content[2] * 255, 0)));
        }

        error_log($this->entry->serialize());
    }

    public function getEntries(): \Traversable
    {
        return $this->entry;
    }
}
