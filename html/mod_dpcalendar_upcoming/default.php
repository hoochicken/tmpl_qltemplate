<?php
/**
 * @package   DPCalendar
 * @copyright Copyright (C) 2014 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */

defined('_JEXEC') or die();

use DPCalendar\Helper\Booking;
use DPCalendar\Helper\DPCalendarHelper;
use DPCalendar\HTML\Block\Icon;
use Joomla\CMS\Helper\ModuleHelper;

/** @var array $events */
/** @var array $groupedEvents */
/** @var DPCalendarHelper $dateHelper */
/** @var \DPCalendar\Translator\Translator $translator */

if (!$events) {
	echo $translator->translate('MOD_DPCALENDAR_UPCOMING_NO_EVENT_TEXT');

	return;
}
?>
<table class="table table-striped">
    <caption>Termine</caption>
    <thead>
    <tr>
        <th scope="col">Datum</th>
        <th scope="col">Uhrzeit</th>
        <th scope="col">Termin</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($groupedEvents as $groupHeading => $events) : ?>
        <?php if ($groupHeading) : ?>
        <tr>
            <th scope="row"><?php echo $groupHeading; ?></th>
            <td></td>
            <td></td>
        </tr>
        <?php endif; ?>
        <?php foreach ($events as $index => $event) : ?>
            <?php
            $startDate = date_format(date_create($event->start_date), 'd.m.Y');
            $startTime = date_format(date_create($event->start_date), 'H:i');
            $endDate = date_format(date_create($event->end_date), 'd.m.Y');
            $endTime = date_format(date_create($event->end_date), 'H:i');
            $date = ($startDate === $endDate)
                ? sprintf('%s', $startDate)
                : sprintf('%s - %s', $startTime, $endTime);
            $timeSlot = ($startDate === $endDate)
                ? sprintf('%s - %s', $startTime, $endTime)
                : sprintf('%s => %s', $startTime, $endTime);
            ?>
        <tr>
            <th scope="row"><?= $startDate ?></th>
            <td><?= $timeSlot ?></td>
            <td><?= $event->title ?>
                <?= ($event->state == 3) ? sprintf('<br /><span class="dp-event_canceled">[%s]</span>', $translator->translate('MOD_DPCALENDAR_UPCOMING_CANCELED')) : '' ?>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
    </tbody>
</table>
