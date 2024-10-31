<?php


class PeriodicTable {

	protected $path;
	protected $url; 
	public function __construct($path, $url) {
		$this->path = $path;
		$this->url = $url;
		
		add_action('pt_cron_hook', array($this, 'cron_job'));
		add_shortcode('periodic-table', array($this, 'shortcode'));
		date_default_timezone_set(PeriodicTable::get_timezone());
	}

	public static function activation() {
		$zone = new DateTimeZone(PeriodicTable::get_timezone());
      		$today = new DateTime('today', $zone);
		wp_schedule_event($today->getTimestamp(), 'daily', 'pt_cron_hook');	
	}

	public static function deactivation() {
		wp_clear_scheduled_hook('pt_cron_hook');
	}	

	public static function get_timezone() {
	    $timezone = get_option('timezone_string');
	    if(isset($timezone) && !empty($timezone)) {
	      return $timezone;
	    }
	    $offset = get_option('gmt_offset');
	    $time = explode('.', $offset)[0];
	    $hours = $time[0];
	    $minutes = 0;
	    if(count($offset)  == 2) {
		$minutes = $time[1];
	    }
	    $seconds = $hours * 60 * 60 + $minutes * 60;
	    $tz = timezone_name_from_abbr('', $seconds, 1);
	    // Workaround for bug #44780
	    if($tz === false) $tz = timezone_name_from_abbr('', $seconds, 0);
	    return $tz;
	}

	public function shortcode() {
		$active = get_option('active_pt_element');
		ob_start();
		require($this->path . 'templates/shortcode.php');
		$contents = ob_get_clean();
		return $contents;
	}


	public function cron_job() {
		$table = json_decode(file_get_contents($this->path . 'assets/periodic.json'));
		$random = rand(0, count($table)-1);

		update_option('active_pt_element', $table[$random]);
		return;
	}
};
