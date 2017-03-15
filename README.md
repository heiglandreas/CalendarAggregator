# CalendarAggregator

Aggregate iCalendar and CalDav calendars

## Usage

```php
$aggregator = new Aggregator();
$aggregator->add(new Icalendar('https://example.com/icalendar'));
// $aggregator->add(new CalDav('https://example.com/caldav'));

$range = $aggregator->getRange(
    new DateTimeImmutable('2017-01-01'), 
    new DateTimeImmutable('2018-01-01')
);

foreach ($range as $event) {
    echo sprintf(
        'Event %s starts %s and ends %s',
        $event->getTitle,
        $event->getStart()->format('c'),
        $event->getEnd()->format('c')
    );
}
```

Alternatively you can also retrieve the events in "lanes" where each lane 
contains a list of non-overlapping events. 

```php
$aggregator = new Aggregator();
$aggregator->add(new Icalendar('https://example.com/icalendar'));
// $aggregator->add(new CalDav('https://example.com/caldav'));

$range = $aggregator->getRange(
    new DateTimeImmutable('2017-01-01'), 
    new DateTimeImmutable('2018-01-01')
);

foreach ($range->getLanes() as $lane) {
    foreach ($lane as $event) {
        echo sprintf(
            'Event %s starts %s and ends %s',
            $event->getTitle,
            $event->getStart()->format('c'),
            $event->getEnd()->format('c')
        );
    }
}
```
