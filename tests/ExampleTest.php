<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/bootstrap.php';

Assert::same(1, 1);
Assert::true(true);

$arr = [1, 2, 3];
Assert::count(3, $arr);
Assert::contains(2, $arr);

echo "Passed!\n";
