<?php
/*
Plugin Name: Clickable Date
Plugin URI: http://www.wabbieworks.com/en/wordpress-plugins/clickable-date/
Version: 1.0.1
Description: Converts publication dates of posts into clickable links which point back to the archive.
Author: WabbieWorks
Author URI: http://www.wabbieworks.com/
*/ 

$clickable_date2link = array (
	'd' => 'day_url',
	'D' => 'day_url',
	'j' => 'day_url',	
	'l' => 'day_url',	
	'dS' => 'day_url',
	'jS' => 'day_url',
	'd<\sup>S</\sup>' => 'day_url',
	'j<\sup>S</\sup>' => 'day_url',			
		
	'F' => 'month_url',
	'm' => 'month_url',
	'M' => 'month_url',
	'n' => 'month_url',						
			
	'Y' => 'year_url',
	'y' => 'year_url'
);

function clickable_time_filter($str, $d) {
	if ( !is_admin() && !empty($str) ) {
		$str = clickable_time($d, '', '', false);
	}
	return $str;
}
add_filter('the_time', 'clickable_time_filter', 1, 2);

function clickable_date_filter($str, $d, $before, $after) {
	if ( !is_admin() && !empty($str) ) {
		if ($d == '') {
			$d = get_settings('date_format');
		}
		$str = clickable_time($d, $before, $after, false);
	}
	return $str;
}
add_filter('the_date', 'clickable_time_filter', 1, 4);

function clickable_date($d='', $before='', $after='', $echo=true) {
	global $day, $previousday;
	if ( function_exists('is_new_day') )
		$is_new_day = is_new_day();
	else
		$is_new_day = ( $day != $previousday );
	
	$the_date = '';
	if ( $is_new_day ) {
		if ( $d == '' ) {
			$d = get_settings('date_format');
		}
		clickable_time($d, $before, $after, $echo);
	}
}

function clickable_time($d='', $before='', $after='', $echo=true) {
	global $post, $clickable_date2link;
	
	if ( $d == '' ) {
		$d = get_settings('time_format');
	}
	
	$post_date = $post->post_date;

	$post_year 		= mysql2date('Y', $post_date);
	$post_monthnum 	= mysql2date('m', $post_date);
	$post_month 	= mysql2date('F', $post_date);	
	$post_day 		= mysql2date('d', $post_date);				

	$year_url 	= array(get_year_link($post_year), $post_year);
	$month_url 	= array(get_month_link($post_year, $post_monthnum), "$post_month, $post_year");
	$day_url 	= array(get_day_link($post_year, $post_monthnum, $post_day), "$post_month $post_day, $post_year");	
	
	$the_time = '';
	for ( $i=0; $i<strlen($d); $i++ ) {
		$_d = substr($d,$i,2);
		if ( !empty($clickable_date2link[$_d]) ) {
			$url 	= ${$clickable_date2link[$_d]}[0];
			$title 	= ${$clickable_date2link[$_d]}[1];
			$text 	= mysql2date(substr($d,$i,2), $post_date);
			$the_time .= '<a href="'. $url .'" title="'. sprintf(__("View all posts in %s"), $title) .'">'. $text .'</a>'; 
			$i++;
		}	
		else if (!empty($clickable_date2link[$d[$i]])) {
			$url 	= ${$clickable_date2link[$d[$i]]}[0];
			$title	= ${$clickable_date2link[$d[$i]]}[1];			
			$text 	= mysql2date($d[$i], $post_date);
			$the_time .= '<a href="'. $url .'" title="'. sprintf(__("View all posts in %s"), $title) .'">'. $text .'</a>'; 			
		}	
		else if ($d[$i] == '\\') {
			$i++;
			$the_time .= $d[$i];
		}	else {
			$the_time .= mysql2date($d[$i], $post_date);
		}	
	}
	
	$the_time = $before . $the_time . $after;
	
	if ($echo) {
		echo $the_time;
	} else {
		return $the_time;
	}			
}



if ( !function_exists('get_year_link') ) {
	function get_year_link($year) {
    global $querystring_start, $querystring_equal;
    if (!$year) $year = gmdate('Y', time()+(get_settings('gmt_offset') * 3600));
    if ('' != get_settings('permalink_structure')) {
        $off = strpos(get_settings('permalink_structure'), '%monthnum%');
        $offset = $off + 11;
        $monthlink = substr(get_settings('permalink_structure'), 0, $offset);
        if ('/' != substr($monthlink, -1)) $monthlink = substr($monthlink, 0, -1);
        $monthlink = str_replace('%year%', $year, $monthlink);
        $monthlink = str_replace('%monthnum%', '', $monthlink);
        $monthlink = str_replace('%post_id%', '', $monthlink);
        return get_settings('home') . $monthlink;
    } else {
        return get_settings('home') .'/'. get_settings('blogfilename') .$querystring_start.'m'.$querystring_equal.$year;
    }
	}	
}
?>