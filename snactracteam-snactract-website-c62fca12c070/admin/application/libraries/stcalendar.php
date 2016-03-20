<?php 

/**
 * Utility library that help with calendar related stuff
 * @author naveed
 *
 */
class Stcalendar {

  const DAY_MON = 1;
  const DAY_TUE = 2;
  const DAY_WED = 3;
  const DAY_THU = 4;
  const DAY_FRI = 5;
  const DAY_SAT = 6;
  const DAY_SUN = 7;
  
  private static $daysOfWeek = array(
    self::DAY_SUN => 'Sunday',
    self::DAY_MON => 'Monday',
    self::DAY_TUE => 'Tuesday',
    self::DAY_WED => 'Wednesday',
    self::DAY_THU => 'Thursday',
    self::DAY_FRI => 'Friday',
    self::DAY_SAT => 'Saturday'
  );
  
  /**
   * Returns all days of week
   * @return array
   */
  public function getDaysOfWeek(){
    return self::$daysOfWeek;
  }
  
  /**
   * Gets a day of the week from a given date
   * @param int $date
   */
  public function getDayOfWeekTitle($dayIdx){
    return self::$daysOfWeek[$dayIdx];
  }
  
}