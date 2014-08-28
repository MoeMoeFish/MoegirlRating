<?php

class MRLogging {
	public static $TRACE = 0;
	public static $DEBUG = 1;
	public static $INFO = 2;
	public static $WARN = 3;
	public static $ERROR = 4;
	public static $FATAL = 5;
	public static $NONE = 6;

	public static function logging( $level, $fileabsoluteName, $lineNumber, $format ) {
		global $wgMoegirlRatingLogLevel, $wgMoegirlRatingLogDir;
		
		if ( $level >= $wgMoegirlRatingLogLevel &&  isset($wgMoegirlRatingLogDir)) {
			$currentTime = (string)microtime();
			$dateString = date( 'Ymd', substr($currentTime, 11, 20));
			$timeString1 = date( 'Ymd H:i:s', substr($currentTime, 11, 20));
			$timeString2 = substr( $currentTime, 1, 7 );

			$fileName = basename( $fileabsoluteName );

			$content = $format;
			if ( func_num_args() > 4 ) {
				$array = array();

				for ($i = 4; $i < func_num_args(); $i++ ) {
					$array[] = func_get_arg( $i );
				}

				$content = vsprintf( $format, $array );
			}


			$logEntry = sprintf( "%s%s %s %s -- %s:%s\n", $timeString1, $timeString2,  self::$logLevelStrings[ $level ], $content, $fileName, $lineNumber );
			$logFile = $wgMoegirlRatingLogDir . '/MoegirlRating-' . $dateString . '.log';

			wfErrorLog( $logEntry, $logFile );
		}
	}

	private static $logLevelStrings = array(
		0 => 'TRACE',
		1 => 'DEBUG',
		2 => 'INFO',
		3 => 'WARN',
		4 => 'ERROR',
		5 => 'FATAL' );

	
}
