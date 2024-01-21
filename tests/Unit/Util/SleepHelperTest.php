<?php

declare(strict_types=1);

use SamihSoylu\CrunchyrollSyncer\Util\SleepHelper;

it('should sleep for one second', function () {
    $sleepHelper = new SleepHelper();

    $start = time();
    $sleepHelper->sleep(1);
    $stop = time();

    $timePassed = $stop - $start;
    expect($timePassed)->toEqual(1);
});
