<?php

namespace App\Library;

class Constants {
	
	const INVITATION_PENDING = 0;
	const INVITATION_ACCEPTED = 1;
	const INVITATION_DECLINED = 2;
	const INVITATION_MAYBE = 3;

	// 2022-07-09 13:30:00
	const DATETIME_FORMAT = "Y-m-d H:i:s";

    // July 9, 2022, 1:30pm
	const DATETIME_READABLE_SHORT = 'M jS, Y, g:ia';

	const BRINGABLE_REQUIRED = 1;
	const BRINGABLE_IMPORTANT = 2;
	const BRINGABLE_USEFUL = 3;
	const BRINGABLE_OPTIONAL = 4;

	const BRINGABLE_LEVELS = [
		self::BRINGABLE_REQUIRED,
		self::BRINGABLE_IMPORTANT,
		self::BRINGABLE_USEFUL,
		self::BRINGABLE_OPTIONAL,
	];


}