document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('calendar');
  if (!calendarEl) return;

  function convertTo24Hour(timeStr) {
    if (!timeStr) return '00:00:00';
    const [time, modifier] = timeStr.split(' ');
    let [hours, minutes] = time.split(':');
    hours = parseInt(hours, 10);

    if (modifier.toLowerCase() === 'pm' && hours !== 12) {
      hours += 12;
    } else if (modifier.toLowerCase() === 'am' && hours === 12) {
      hours = 0;
    }

    return `${hours.toString().padStart(2, '0')}:${minutes}:00`;
  }

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    events: async function (fetchInfo, successCallback, failureCallback) {
      try {
        const response = await fetch('/northern-shaolin-kungfu/wp-json/wp/v2/event?per_page=100');
        const data = await response.json();
        const allEvents = [];

        data.forEach(event => {
          const acf = event.acf;
          if (!acf || !acf.location_schedule) return;

          acf.location_schedule.forEach(schedule => {
            const levels = schedule.level || [];

            levels.forEach(level => {
              const days = level.day_of_the_week || [];
              if (days.length === 0) return;

              const startTime = convertTo24Hour(level.start_time);
              const endTime = convertTo24Hour(level.end_time || level.start_time);

      
              const dayMap = {
                'Sunday': 0,
                'Monday': 1,
                'Tuesday': 2,
                'Wednesday': 3,
                'Thursday': 4,
                'Friday': 5,
                'Saturday': 6
              };

              const daysOfWeekNums = days.map(d => dayMap[d]).filter(d => d !== undefined);

       
              if (daysOfWeekNums.length === 0) return;

              allEvents.push({
                title: `${event.title.rendered} - ${level.level_select || ''}`.trim(),
                daysOfWeek: daysOfWeekNums,
                startTime: startTime,
                endTime: endTime,
                url: event.link,
                startRecur: '2025-01-01', 
                endRecur: '2025-12-31'  
              });
            });
          });
        });

        console.log('Events loaded for FullCalendar:', allEvents);
        successCallback(allEvents);
      } catch (error) {
        console.error('Failed to fetch events:', error);
        failureCallback(error);
      }
    }
  });

  calendar.render();
});