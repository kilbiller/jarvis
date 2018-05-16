<?php

$commandLine = $this->commandLine();
$commandLine->option('spec', 'default', 'tests');
$commandLine->option('grep', 'default', '*Test.php');
