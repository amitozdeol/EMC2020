<?php

class ConvFunc  {
  /* function unitconv

     general function to convert units to abbreviated values
     also convert C symbol to F if called for in system configuation table

  */
 public static function unitconv($units,$degreeSym) {
    $newunit = strtolower($units);
    
    // blank empties
    $newunit = str_replace("-", "", $newunit);
    // shorten relative humidity
    $newunit = str_replace("relative", "", $newunit);
    // shorten volts
    $newunit = str_replace("v", "V", $newunit);
    // format pressure units
    $newunit = str_replace("in h2o", "<i>in&nbsp;H<sub>2</sub>O</i>", $newunit);

    //  degrees  to symbol  for temperature
    if ($degreeSym=="F") {
        $newunit = str_replace("degrees c", "&#176"."F", $newunit);

    } else    {
       $newunit = str_replace("degrees c", "&#176"."C", $newunit);
    }

    return $newunit;

}
 /* function valueconv

    general function to convert values of temperature from C to F
     as called for in system configuation table
     other conversion can be added to Switch as required

  */
 public static function valueconv($current_value,$commandparm)
{        // look up data_type based on commandparm
         $functype = DB::table('device_types')  // add'device_data_current'
                               ->where('command',$commandparm)
                               ->limit ('1')
                               ->get();
          $pfunctype="NA";
        
         foreach ($functype as $pf) {

            $pfunctype=$pf->function;
             
            
            }
      

        switch ($pfunctype)  {
         case "Temperature":  $newvalue = number_format(($current_value*9/5)+32,1);

             break;
        default :  $newvalue = number_format($current_value, 2);
             break;
        }
      return $newvalue;
}

 public static function sec2hms($secs)
 {
       $secs = round($secs);
       $secs = abs($secs);
       $hours = floor($secs / 3600) . ':';
       if ($hours == '0:') $hours = '00:';
       $minutes = substr('00' . floor(($secs / 60) % 60), -2) . ':';
       $seconds = substr('00' . $secs % 60, -2);
return ltrim($hours . $minutes . $seconds, '0');
}



public static function findzonename($zonelist,$zone)
{
    $ZName="Zone-".$zone;
    foreach ($zonelist as $zn)  {


        if ($zn->zone==$zone) {
            $ZName="Zone-".$zone;
            if ($zn->zonename!="")  {$ZName=$zn->zonename;}
        }
    }
    return $ZName;
}


  /**
   * Convert a celcius temperature to fahrenheit
   * @param  integer $celciusTemperature The original temperature in celcius
   * @return integer                     The converted temperature in fahrenheit
   */
  public static function convertCelciusToFarenheit ( $celciusTemperature )
  {
    $fahrenheitTemperature = ($celciusTemperature * 9/5) + 32;
    $fahrenheitTemperature = round($fahrenheitTemperature, 3);

    return $fahrenheitTemperature;
  }

  /**
   * Convert a fahrenheit temperature to celcius
   * @param  integer $fahrenheitTemperature The original temperature in celcius
   * @return integer                     The converted temperature in celcius
   */
  public static function convertFarenheittoCelcius ( $FarenheitTemperature )
  {
    $celciusTemperature = ($FarenheitTemperature - 32) * 5/9;
    $celciusTemperature = round($celciusTemperature, 3);

    return $celciusTemperature;
  }

  public static function GalFixing ( $celciusTemperature )
  {
    // if(gettype($celciusTemperature) !== 'float') {
    //   $celciusTemperature = intval($celciusTemperature);
    // }
    $fahrenheitTemperature = ($celciusTemperature *0) + 0;
    $fahrenheitTemperature = round($fahrenheitTemperature, 1);

    return $fahrenheitTemperature;
  }


}



?>
