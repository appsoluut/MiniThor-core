#!/usr/bin/php
<?php
date_default_timezone_set('Europe/Amsterdam');

echo "----------------------------------\n";
echo "MiniThor flow queue system started\n";
echo date('Y-m-d H:i:s') . "\n";
echo "----------------------------------\n";

$pheanstalkClassRoot = '../contrib/Pheanstalk';
require_once $pheanstalkClassRoot . '/Pheanstalk/ClassLoader.php';
Pheanstalk_ClassLoader::register($pheanstalkClassRoot);

$pheanstalk = new Pheanstalk('192.168.2.1');

$continue = true;
$restart = false;
do {
	$job = $pheanstalk->watch('testtube')->ignore('default')->reserve();
	$jobData = $job->getData();
	echo '> ' . date('H:i:s') . ' - ' . $jobData . "\n";
	
	if($jobData == 'restart') {
		$continue = false;
		$restart = true;
	} else if($jobData == 'stop') {
		$continue = false;
	}
	
	$pheanstalk->delete($job);
} while($continue);

if($restart) {
	$exitCode = 98; // Planned restart
} else {
	$exitCode = 99; // Planned stop
}

echo "\n\n";

exit($exitCode);

// cli-beanstalk-worker.php
// for testing of the BASH script
//exit (rand(95, 100));

/* normally we would return one of
# 97  - planned pause/restart
# 98  - planned restart
# 99  - planned stop, exit.
# anything else is an unplanned restart
*/