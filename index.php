<?php
require __DIR__ . '/vendor/autoload.php';

use KNN\Knn\Knn;

define("DEBUG", true);

if (DEBUG) print "<pre>";

$url = "https://archive.ics.uci.edu/ml/machine-learning-databases/iris/iris.data";

$knn = new Knn($url,0.66);
$knn->handle();

if (DEBUG){
  print "Trainig: " . count($knn->trainingSet)."\n";
  print "Test: " . count($knn->testSet)."\n";
};

if (DEBUG) print "</pre>";