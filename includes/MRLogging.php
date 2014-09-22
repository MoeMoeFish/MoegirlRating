<?php

class MRLogging {
	public static $TRACE = 0;
	public static $DEBUG = 1;
	public static $INFO = 2;
	public static $WARN = 3;
	public static $ERROR = 4;
	public static $FATAL = 5;
	public static $NONE = 6;

	private $fileAbsoluteName;

	public function __construct ( $fileAbsoluteName ) {
		$this->fileAbsoluteName = $fileAbsoluteName;
	}

	public function trace( $lineNumber, $format ) {
		$varsArray = array();
		$varsArray[] = self::$TRACE;
		$varsArray[] = $this->fileAbsoluteName;


		for ( $i = 0; $i < func_num_args(); $i++ ) {
			$varsArray[] = func_get_arg($i);
		}

		call_user_func_array( 'MRLogging::logging', $varsArray);

	}
	
	public function debug( $lineNumber, $format ) {
		$varsArray = array();
		$varsArray[] = self::$DEBUG;
		$varsArray[] = $this->fileAbsoluteName;


		for ( $i = 0; $i < func_num_args(); $i++ ) {
			$varsArray[] = func_get_arg($i);
		}

		call_user_func_array( 'MRLogging::logging', $varsArray);

	}

	public function info( $lineNumber, $format ) {
		$varsArray = array();
		$varsArray[] = self::$INFO;
		$varsArray[] = $this->fileAbsoluteName;


		for ( $i = 0; $i < func_num_args(); $i++ ) {
			$varsArray[] = func_get_arg($i);
		}

		call_user_func_array( 'MRLogging::logging', $varsArray);

	}

	public function warn( $lineNumber, $format ) {
		$varsArray = array();
		$varsArray[] = self::$WARN;
		$varsArray[] = $this->fileAbsoluteName;


		for ( $i = 0; $i < func_num_args(); $i++ ) {
			$varsArray[] = func_get_arg($i);
		}

		call_user_func_array( 'MRLogging::logging', $varsArray);

	}

	public function error( $lineNumber, $format ) {
		$varsArray = array();
		$varsArray[] = self::$ERROR;
		$varsArray[] = $this->fileAbsoluteName;


		for ( $i = 0; $i < func_num_args(); $i++ ) {
			$varsArray[] = func_get_arg($i);
		}

		call_user_func_array( 'MRLogging::logging', $varsArray);

	}

	public function fatal( $lineNumber, $format ) {
		$varsArray = array();
		$varsArray[] = self::$FATAL;
		$varsArray[] = $this->fileAbsoluteName;


		for ( $i = 0; $i < func_num_args(); $i++ ) {
			$varsArray[] = func_get_arg($i);
		}

		call_user_func_array( 'MRLogging::logging', $varsArray);

	}

	public static function logging( $level, $fileabsoluteName, $lineNumber, $format ) {
		global $wgMoegirlRatingLogLevel, $wgMoegirlRatingLogDir;
		
		if ( $level >= $wgMoegirlRatingLogLevel && isset($wgMoegirlRatingLogDir) ) {
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
		2 => 'INFOM',
		3 => 'WARNN',
		4 => 'ERROR',
		5 => 'FATAL' );
}
