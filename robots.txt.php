<?php
header('Content-type: text/plain');

$blnRobotsOK = false;

$http_hostname = strtolower(getenv('HTTP_HOST'));
if ($http_hostname == "larry.meaney.name") { $blnRobotsOK = true; }
// if ($http_hostname == "meaney.webtrees.net") { $blnRobotsOK = true; }


if (!$blnRobotsOK) { ?>
# go away
User-agent: *
Disallow: /
<?php } else {

$wt_dir = '';
$wt_robots = $wt_dir.'robots-example.txt';
$main_robots = 'robots.txt';
$contents = '';

// process the main robots.txt file
if (file_exists($main_robots)) {
  $contents .= "# begin output from /$main_robots\n";
	$contents .= file_get_contents($main_robots);
  $contents .= "\n# end output from /$main_robots\n\n\n";
}

// process the webtrees robots.txt file
if (file_exists($wt_robots)) {
  $contents .= "# begin output from /$wt_robots\n";
	$lines = file($wt_robots);
	foreach ($lines as $line_num => $line) {
	  if (strpos($line, '#') !== 0) { // ignore comments
	    $line = preg_replace('#(Disallow:|Allow:)( *)\/#', '$1$2/'.$wt_dir, $line); // set the webtrees path
	    $contents .= $line;
	  }
	}
  $contents .= "\n# end output from /$wt_robots\n";
} else {
  $contents .= "# warning, /$wt_robots does not exist\n";
}
echo $contents;
} ?>