<?= $this->Html->css('events.css') ?>
<?= $this->Html->css('clndr.css') ?>
<!--Календарь -->
<div class="event-calendar clearfix">
            <div class="col-lg-7 calendar-block">
                <div class="cal1"><div class="clndr"><div class="clndr-controls"><div class="clndr-control-button"><span class="clndr-previous-button"><i class="fa fa-chevron-left"></i></span></div><div class="month">April 2019</div><div class="clndr-control-button leftalign"><span class="clndr-next-button"><i class="fa fa-chevron-right"></i></span></div></div><table class="clndr-table" border="0" cellspacing="0" cellpadding="0"><thead><tr class="header-days"><td class="header-day">S</td><td class="header-day">M</td><td class="header-day">T</td><td class="header-day">W</td><td class="header-day">T</td><td class="header-day">F</td><td class="header-day">S</td></tr></thead><tbody><tr><td class="day past adjacent-month last-month calendar-day-2019-03-31"><div class="day-contents">31</div></td><td class="day past calendar-day-2019-04-01"><div class="day-contents">1</div></td><td class="day past calendar-day-2019-04-02"><div class="day-contents">2</div></td><td class="day past calendar-day-2019-04-03"><div class="day-contents">3</div></td><td class="day past calendar-day-2019-04-04"><div class="day-contents">4</div></td><td class="day past calendar-day-2019-04-05"><div class="day-contents">5</div></td><td class="day past calendar-day-2019-04-06"><div class="day-contents">6</div></td></tr><tr><td class="day past calendar-day-2019-04-07"><div class="day-contents">7</div></td><td class="day past calendar-day-2019-04-08"><div class="day-contents">8</div></td><td class="day past calendar-day-2019-04-09"><div class="day-contents">9</div></td><td class="day past event calendar-day-2019-04-10"><div class="day-contents">10</div></td><td class="day past event calendar-day-2019-04-11"><div class="day-contents">11</div></td><td class="day past event calendar-day-2019-04-12"><div class="day-contents">12</div></td><td class="day past event calendar-day-2019-04-13"><div class="day-contents">13</div></td></tr><tr><td class="day past event calendar-day-2019-04-14"><div class="day-contents">14</div></td><td class="day past calendar-day-2019-04-15"><div class="day-contents">15</div></td><td class="day past calendar-day-2019-04-16"><div class="day-contents">16</div></td><td class="day past calendar-day-2019-04-17"><div class="day-contents">17</div></td><td class="day past calendar-day-2019-04-18"><div class="day-contents">18</div></td><td class="day past calendar-day-2019-04-19"><div class="day-contents">19</div></td><td class="day past calendar-day-2019-04-20"><div class="day-contents">20</div></td></tr><tr><td class="day past event calendar-day-2019-04-21"><div class="day-contents">21</div></td><td class="day today event calendar-day-2019-04-22"><div class="day-contents">22</div></td><td class="day event calendar-day-2019-04-23"><div class="day-contents">23</div></td><td class="day calendar-day-2019-04-24"><div class="day-contents">24</div></td><td class="day calendar-day-2019-04-25"><div class="day-contents">25</div></td><td class="day calendar-day-2019-04-26"><div class="day-contents">26</div></td><td class="day calendar-day-2019-04-27"><div class="day-contents">27</div></td></tr><tr><td class="day calendar-day-2019-04-28"><div class="day-contents">28</div></td><td class="day calendar-day-2019-04-29"><div class="day-contents">29</div></td><td class="day calendar-day-2019-04-30"><div class="day-contents">30</div></td><td class="day adjacent-month next-month calendar-day-2019-05-01"><div class="day-contents">1</div></td><td class="day adjacent-month next-month calendar-day-2019-05-02"><div class="day-contents">2</div></td><td class="day adjacent-month next-month calendar-day-2019-05-03"><div class="day-contents">3</div></td><td class="day adjacent-month next-month calendar-day-2019-05-04"><div class="day-contents">4</div></td></tr></tbody></table></div></div>
            </div>
            <div class="col-lg-5 event-list-block">
                <div class="cal-day">
                    <span>Today</span>
                    <?= date('l') ?>
                </div>
                <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 305px;"><ul class="event-list" style="overflow: hidden; width: auto; height: 305px;">
                    <li>Lunch with jhon @ 3:30 <a href="#" class="event-close"><i class="ico-close2"></i></a></li>
                    <li>Coffee meeting with Lisa @ 4:30 <a href="#" class="event-close"><i class="ico-close2"></i></a></li>
                    <li>Skypee conf with patrick @ 5:45 <a href="#" class="event-close"><i class="ico-close2"></i></a></li>
                    <li>Gym @ 7:00 <a href="#" class="event-close"><i class="ico-close2"></i></a></li>
                    <li>Dinner with daniel @ 9:30 <a href="#" class="event-close"><i class="ico-close2"></i></a></li>

                </ul><div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 305px;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
                <input type="text" class="form-control evnt-input" placeholder="NOTES">
            </div>
        </div>

        <!-- Конец календаря-->

<script src="/js/moment-2.2.1.js"></script>
  <script src="/js/calendar.js"></script>
  <script>
  $('.cal1').clndr({
        classes: {
            past: "past",
            today: "today",
            event: "event",
            inactive: "inactive",
            lastMonth: "last-month",
            nextMonth: "next-month",
            adjacentMonth: "adjacent-month"
        }});
</script>