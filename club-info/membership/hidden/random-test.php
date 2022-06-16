#!/usr/bin/php

	$stuff = random_bytes(12);
	echo($stuff . "\n");
	$renewKey = bin2hex($stuff);
	echo ($renewKey . "\n");
