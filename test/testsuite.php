<?php
// require the SimpleTest framework
require_once("/usr/lib/php5/simpletest/autorun.php");

class GroupTestSuite extends TestSuite {
	// the constructor for a TestSuite just sets up all the file names
	public function __construct() {
		// run the parent constructor
		parent::__construct();

		// stuff the test files into an array
		$testFiles = glob("*.php");
		$suiteFile = basename($_SERVER["SCRIPT_NAME"]);
		$suiteIndex = array_search($suiteFile, $testFiles);
		unset($testFiles[$suiteIndex]);
		$testFiles = array_values($testFiles);

		// run them forward
		foreach($testFiles as $testFile) {
			$this->addFile($testFile);
		}
		// run them backward
		$testFiles = array_reverse($testFiles, false);
		foreach($testFiles as $testFile) {
			$this->addFile($testFile);
		}
		// run them randomly
		shuffle($testFiles);
		foreach($testFiles as $testFile) {
			$this->addFile($testFile);
		}
	}
}
?>