# scrape-demo

This demo script is used for presentation "Scraping your HTML site to Joomla" 
at the European Joomla conference JandBeyond 2017 in Kraków, Poland

- Slides: http://slides.db8.nl/jab17-scraping-your-html-site-to-joomla.html#/
- Video: https://www.youtube.com/watch?v=F7h5aI3nolM

## Usage ##
 * Import website to local PC by running wget in /home/username/folder/
 
```
wget --recursive --no-clobber --page-requisites 
--html-extension --convert-links 
--restrict-file-names=windows 
--domains example.com 
--no-parent 
http://example.com
```
 
* Change parameters in scrapesite.php to reflect local settings
* Find surrounding tags in HTML and change script accordingly
* Run scrapesite.php script
