<?php 
$CalendarDates = array();
foreach ($this->dates as $date) {
    $CalendarDates[] = $date->full_date;  
}
?>
<table>
        <tr  valign="top">
            <td class="calendar_container"><?php echo $this->monthCalendar('2010','01', $CalendarDates); ?></td>
            <td class="calendar_container"><?php echo $this->monthCalendar('2010','02', $CalendarDates); ?></td>
            <td class="calendar_container"><?php echo $this->monthCalendar('2010','03', $CalendarDates); ?></td>
            <td class="calendar_container"><?php echo $this->monthCalendar('2010','04', $CalendarDates); ?></td>
        </tr>

        <tr  valign="top">
            <td class="calendar_container"><?php echo $this->monthCalendar('2010','05', $CalendarDates); ?></td>
            <td class="calendar_container"><?php echo $this->monthCalendar('2010','06', $CalendarDates); ?></td>
            <td class="calendar_container"><?php echo $this->monthCalendar('2010','07', $CalendarDates); ?></td>
            <td class="calendar_container"><?php echo $this->monthCalendar('2010','08', $CalendarDates); ?></td>
        </tr>

        <tr  valign="top">
            <td class="calendar_container"><?php echo $this->monthCalendar('2010','09', $CalendarDates); ?></td>
            <td class="calendar_container"><?php echo $this->monthCalendar('2010','10', $CalendarDates); ?></td>
            <td class="calendar_container"><?php echo $this->monthCalendar('2010','11', $CalendarDates); ?></td>
            <td class="calendar_container"><?php echo $this->monthCalendar('2010','12', $CalendarDates); ?></td>
        </tr>
    </table>