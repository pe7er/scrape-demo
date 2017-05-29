<?php
/**
 * @package    scrape class
 *
 * @author     Peter Martin <joomla@db8.nl>
 * @copyright  Copyright 2017 Peter Martin. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       https://db8.nl
 */


/*
 * Import website to local PC using wget
 wget --recursive --no-clobber --page-requisites --html-extension --convert-links --restrict-file-names=windows --domains example.com --no-parent http://example.com
 *
 * Change parameters to reflect local settings
 * Find surrounding tags in HTML & change accordingly
 * Run script
 */

$dbHost = 'localhost';
$dbUsername = 'user';
$dbPassword = 'password';
$dbName = 'dbName';
$dbTable = 'imported_content';
$scrapedAbsPath = '/home/pe7er/www/jab17/jandbeyond.org';

include("scrape.class.php");

// store content in database in Joomla content format
$mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

/**
 * Scan all html files
 *
 * change start of content blocks:
 * - start + end of content
 * - start + end of title
 * - start + end of introtext
 *
 */
$links = getDirContents($scrapedAbsPath);

foreach($links AS $link):

	if(mime_content_type($link) == 'text/html')
	{
		$link = "http://localhost/".substr($link, 16);
		$link = str_replace('?', '%3F', $link);

//echo "<br>".$link."<br>";

		$results_page = curl($link);
		//$content = scrape_between($results_page, '<div id="content">', '<div id="sidebar">');
		$content = scrape_between($results_page, '<div class="contentwrapper">', '<!-- div.contentwrapper -->');

		print_r($content);

		$title = scrape_between($content, '<h1>', '</h1>');
		if(!$title){
			$title = scrape_between($content, '<h2>', '</h2>');
		}
		if(!$title){
			$title = scrape_between($content, '<a href="index.html">', '</a>');
		}

		$alias = seoUrl($title);

		$introtext = scrape_between($content, '</h1>', '<div class="clearfix"></div>');
		if(!$introtext){
			$introtext = '<div class="paragraph">'.scrape_between($content, '<div class="paragraph">', '<div id="more">');
		}

		$introtext = str_replace ( '../../../images/', 'images/', $introtext );
		$introtext = str_replace ( '../../images/', 'images/', $introtext );
		$introtext = str_replace ( '../images/', 'images/', $introtext );

		$query = 'INSERT INTO `' . $dbTable . '` (`id`, `title`, `alias`, `introtext`, `fulltext`, `state`, `catid`, '.
			'`created`, `created_by`, `created_by_alias` ) '.
			' VALUES( '.
			' null, '.
			"'". $mysqli->real_escape_string( $title ) . "', " .
			"'". $mysqli->real_escape_string( $alias ) . "', " .
			"'". $mysqli->real_escape_string( $introtext ) . "', " .
			"'". $mysqli->real_escape_string( $link ) . "', " .
			//	"'', " .
			"0, 2, '2016-03-03 00:00:01', 604, 'Peter'); ";

		//echo $query;
		//echo "<br/>";
		//echo "<hr/>";

		if (!$mysqli->query($query))
		{
			printf("%d Row inserted.\n", $mysqli->affected_rows);
			"error";
		}

	}
endforeach;

$mysqli->close();
