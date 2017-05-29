<?php
/**
 * @package    scrape class
 *
 * @author     Peter Martin <joomla@db8.nl>
 * @copyright  Copyright 2017 Peter Martin. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       https://db8.nl
 */


/**
 * Defining the basic cURL function
 * by Jacob Ward
 * source: http://www.jacobward.co.uk/web-scraping-with-php-curl-part-1/
 *
 * @param $url
 *
 * @return mixed
 */
function curl($url)
{
	// Assigning cURL options to an array
	$options = Array(
		CURLOPT_RETURNTRANSFER => TRUE,  // Setting cURL's option to return the webpage data
		CURLOPT_FOLLOWLOCATION => TRUE,  // Setting cURL to follow 'location' HTTP headers
		CURLOPT_AUTOREFERER => TRUE, // Automatically set the referer where following 'location' HTTP headers
		CURLOPT_CONNECTTIMEOUT => 120,   // Setting the amount of time (in seconds) before the request times out
		CURLOPT_TIMEOUT => 120,  // Setting the maximum amount of time for cURL to execute queries
		CURLOPT_MAXREDIRS => 10, // Setting the maximum number of redirections to follow
		CURLOPT_USERAGENT => randomUserAgent(), // Setting the user agent
		CURLOPT_URL => $url, // Setting cURL's URL option with the $url variable passed into the function
	);

	$ch = curl_init();  // Initialising cURL
	curl_setopt_array($ch, $options);   // Setting cURL's options using the previously assigned array data in $options
	$data = curl_exec($ch); // Executing the cURL request and assigning the returned data to the $data variable
	curl_close($ch);    // Closing cURL
	return $data;   // Returning the data from the function
}


/**
 * Defining the basic scraping function
 * by Jacob Ward
 * source: http://www.jacobward.co.uk/web-scraping-with-php-curl-part-1/
 *
 * @param $data
 * @param $start
 * @param $end
 *
 * @return bool|string
 */
function scrape_between($data, $start, $end)
{
	$data = stristr($data, $start); // Stripping all data from before $start
	$data = substr($data, strlen($start));  // Stripping $start
	$stop = stripos($data, $end);   // Getting the position of the $end of the data to scrape
	$data = substr($data, 0, $stop);    // Stripping all data from after and including the $end of the data to scrape

	return $data;   // Returning the scraped data from the function
}


/**
 * Defining the most used User Agents
 * list of User Agents available at https://techblog.willshouse.com/2012/01/03/most-common-user-agents/
 *
 * @return mixed
 */
function randomUserAgent()
{
	$userAgents = Array (
		'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36',
		'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36',
		'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36',
		'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_2) AppleWebKit/601.3.9 (KHTML, like Gecko) Version/9.0.2 Safari/601.3.9',
		'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:43.0) Gecko/20100101 Firefox/43.0',
		'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36',
		'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36',
		'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:43.0) Gecko/20100101 Firefox/43.0',
		'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko',
		'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:43.0) Gecko/20100101 Firefox/43.0',
		'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36',
		'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36',
		'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:43.0) Gecko/20100101 Firefox/43.0',
		'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/601.3.9 (KHTML, like Gecko) Version/9.0.2 Safari/601.3.9',
		'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36',
		'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:43.0) Gecko/20100101 Firefox/43.0',
		'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/601.2.7 (KHTML, like Gecko) Version/9.0.1 Safari/601.2.7',
		'Mozilla/5.0 (iPad; CPU OS 9_2 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13C75 Safari/601.1',
		'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.80 Safari/537.36'
	);

	return $userAgents[ mt_rand( 0, count($userAgents)-1 )];
}


/**
 * Get recursive list of all index.html files + folders
 *
 * @param       $dir
 * @param array $results
 *
 * @return array
 */
function getDirContents($dir, &$results = array())
{

	$di = new RecursiveDirectoryIterator($dir,RecursiveDirectoryIterator::SKIP_DOTS);
	$it = new RecursiveIteratorIterator($di);
	//$results = Array();

	foreach($it as $file) {
		if (pathinfo($file, PATHINFO_EXTENSION) == "html") {
			//echo $file, PHP_EOL, '<br>';
			$results = array_keys(iterator_to_array($it));
		}
	}

	return $results;
}

/**
 * Create Alias from title for nice SEO URL
 *
 * @param $string
 *
 * @return mixed|string
 */
function seoUrl($string)
{
	//Lower case everything
	$string = strtolower($string);
	//Make alphanumeric (removes all other characters)
	$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
	//Clean up multiple dashes or whitespaces
	$string = preg_replace("/[\s-]+/", " ", $string);
	//Convert whitespaces and underscore to dash
	$string = preg_replace("/[\s_]/", "-", $string);
	return $string;
}

