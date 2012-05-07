<?php
require('notifier.php');

$notifier = new Notifier('travis.i.wyatt@gmail.com', 'travis.i.wyatt@gmail.com');

$test = array(
	'Sect' => 1234,
	'Days' => '',
	'Hours' => 'Online',
	'Instructor' => 'Test I',
	'Room' => 'Online',
	'Units' => '3.00',
	'Status' => 'Open',
	'Seats' => 2,
	'Date Begin/End' => '06/11-08/05',
	'Date Final Exam' => '',
);

if ($notifier->notify('TEST 101', $test)) {
	echo "Success.\n";
} else {
	echo "Failed.\n";
};