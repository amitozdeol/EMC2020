<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class SystemLog extends Model
{

  /**
   * The database table used by the model.
   *
   * @var string
   */
    protected $table = 'system_log';

    protected $primaryKey = 'recnum';

  /**
   * Set use of creation and update timestamps in the table.
   * @var boolean
   */
    public $timestamps = false;


  /**
   * Insert a logged event in the table
   * @param  integer $system_id The system where the event took place
   * @param  mixed   $report    A string describing the event of an Exception opject
   * @param  integer $log_type  An id linking to the `log_types` table
   * @return boolean            The return status from executing the database insert
   */
    private static function insertEvent($system_id, $report, $log_type, $severity)
    {
        if (gettype($report) === 'object') {
            $report_string  = 'EXCEPTION: ' . $report->getMessage();

            if ($url = Request::url()) {
                $report_string .= ' URL: ' . $url;
            }

            if ($file = $report->getFile() && $line = $report->getLine()) {
                if (strlen($file) > 1) {
                    $report_string .= ' FILE: ' . $file . ':' . $line;
                }
            }

            $report = $report_string;
        }

        $report = "[$severity] " . $report;

        $log = new SystemLog();
        $log->system_id        = intval($system_id);
        $log->application_name = 'WEB';
        $log->report           = substr($report, 0, 254);
        $log->datetime         = date('Y-m-d H:i:s');
        $log->log_type         = intval($log_type);
        $log->save();
    }


  /**
   * Log an informational event
   * @param  integer $system_id The system where the event took place
   * @param  mixed   $report    A string describing the event of an Exception opject
   * @param  integer $log_type  An id linking to the `log_types` table
   * @return
   */
    public static function info($system_id, $report, $log_type)
    {
        self::insertEvent($system_id, substr($report, 0, 254), $log_type, 'INFO');
        Log::info($report);

        return;
    }


  /**
   * Log a waring event
   * @param  integer $system_id The system where the event took place
   * @param  mixed   $report    A string describing the event of an Exception opject
   * @param  integer $log_type  An id linking to the `log_types` table
   * @return
   */
    public static function warning($system_id, $report, $log_type)
    {
        self::insertEvent($system_id, substr($report, 0, 254), $log_type, 'WARNING');
        Log::warning($report);

        return;
    }


  /**
   * Log an error event
   * @param  integer $system_id The system where the event took place
   * @param  mixed   $report    A string describing the event of an Exception opject
   * @param  integer $log_type  An id linking to the `log_types` table
   * @return
   */
    public static function error($system_id, $report, $log_type)
    {
        self::insertEvent($system_id, substr($report, 0, 254), $log_type, 'ERROR');
        Log::error($report);

        return;
    }
}
