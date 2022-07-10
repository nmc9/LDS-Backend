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


}