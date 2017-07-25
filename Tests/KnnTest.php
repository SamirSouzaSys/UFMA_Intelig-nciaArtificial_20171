<?php
use KNN\Knn;
use PHPUnit\Framework\TestCase;

/**
 * Created by PhpStorm.
 * User: samirsouza
 * Date: 22/06/17
 * Time: 12:07
 */
class KnnTest extends TestCase
{
  /**
   * @dataProvider loadDatasetProvider
   */
  public function testLoadDataset(Knn $knn)
  {
    $knn->loadDataset();
    $this->assertEquals(150, count($knn->trainingSet) + count($knn->testSet));
  }

  public function loadDatasetProvider()
  {
    $url = "https://archive.ics.uci.edu/ml/machine-learning-databases/iris/iris.data";
    $knn = new Knn($url, 0.66, 4);
    return [[$knn]];
  }

  /**
   * @dataProvider euclideanDistanceProvider
   */
  public function testEuclideanDistance(Knn $knn)
  {
    $result = $knn->euclideanDistance(
      $knn->trainingSet[0], $knn->trainingSet[1], $knn->parameterListLength);
    $this->assertEquals(4, $result);
  }

  public function euclideanDistanceProvider()
  {
    $knn = new Knn(null, null, 4);
    $knn->trainingSet = [[2, 2, 2, 2, "a"], [4, 4, 4, 4, "b"]];
    return [[$knn]];
  }

  /**
   * @dataProvider neighborsProvider
   */
  public function testGetNeighbors(Knn $knn)
  {
    $ideal = [[4, 4, 4, 4, "b"], [4, 4, 4, 4, "b"], [2, 2, 2, 2, "a"],];

    $result = $knn->getNeighbors(0);
    $this->assertEquals($ideal, $result);
  }

  public function neighborsProvider()
  {
    $knn = new Knn(null, null, 4, 3);
    $knn->trainingSet = [[2, 2, 2, 2, "a"], [4, 4, 4, 4, "b"], [4, 4, 4, 4, "b"]];
    $knn->testSet = [[5, 5, 5, 5, "b"]];
    return [[$knn]];
  }

  /**
   * @dataProvider responseProvider
   */
  public function testResponse(Knn $knn, $neighbors)
  {
    $result = $knn->getResponse($neighbors);

    $this->assertEquals('a', $result);
  }

  public function responseProvider()
  {
    $knn = new Knn();
    $neighbors = [[1, 1, 1, "a"], [2, 2, 2, "a"], [3, 3, 3, "b"]];
    return [[$knn, $neighbors]];
  }

  /**
   * @dataProvider accuracyProvider
   */
  public function testAccuracy(Knn $knn, $predictions)
  {
    $result = $knn->getAccuracy($predictions);
    $this->assertEquals(66, intval($result));
  }

  public function accuracyProvider()
  {
    $testSet = [[1, 1, 1, 1, "a"], [2, 2, 2, 2, "a"], [3, 3, 3, 3, "b"]];
    $predictions = ["a", "a", "a", "a"];
    $knn = new Knn(null, null, 4);
    $knn->testSet = $testSet;
    return [[$knn, $predictions]];
  }

  /**
   * @dataProvider knnMetricsProvider
   */
  public function testGetAccuracyFinal(Knn $knn)
  {
//    TP+TN/(TP+TN+FP+FN) = (120+310)/(120+310+40+30) = .86
    $workingWell = true;
    $listValues = [
      "Iris-setosa" => 1,
      "Iris-versicolor" => 0.92727272727273,
      "Iris-virginica" => 0.92727272727273
    ];

    $knn->fillIndividualConfusionMatrix();
    foreach ($knn->classificationTable as $k => $v) {
      if ($v["accuracy"] !=
        number_format($listValues[$k], $knn->getDecimalNumberVars())
      ) {
        $workingWell = false;
      }
    }
    $this->assertEquals(true, $workingWell);
  }

  /**
   * @dataProvider knnMetricsProvider
   */
  public function testGetPrecisionFinal(Knn $knn)
  {
//    Precisão       = TP/(TP + FP) = 120 / (120+40) = .75
    $workingWell = true;
    $listValues = [
      "Iris-setosa" => 1,
      "Iris-versicolor" => 0.916666666666667,
      "Iris-virginica" => 0.882352941176471
    ];

    $knn->fillIndividualConfusionMatrix();
    foreach ($knn->classificationTable as $k => $v) {
      if ($v["precision"] != number_format($listValues[$k], $knn->getDecimalNumberVars())) {
        $workingWell = false;
      }
    }
    $this->assertEquals(true, $workingWell);
  }

  /**
   * @dataProvider knnMetricsProvider
   */
  public function testGetSensibilityRecallFinal(Knn $knn)
  {
//    Precisão       = TP/(TP + FP) = 120 / (120+40) = .75
    $workingWell = true;
    $listValues = [
      "Iris-setosa" => 1,
      "Iris-versicolor" => 0.917,
      "Iris-virginica" => 0.882
    ];

    $knn->fillIndividualConfusionMatrix();
    foreach ($knn->classificationTable as $k => $v) {
      if ($v["recall"] != number_format($listValues[$k], $knn->getDecimalNumberVars())) {
        $workingWell = false;
      }
    }
    $this->assertEquals(true, $workingWell);
  }

  /**
   * @dataProvider knnMetricsProvider
   */
  public function testGetSpecificityFinal(Knn $knn)
  {
//    Precisão       = TP/(TP + FP) = 120 / (120+40) = .75
    $workingWell = true;
    $listValues = [
      "Iris-setosa" => 1,
      "Iris-versicolor" => 0.935,
      "Iris-virginica" => 0.947
    ];

    $knn->fillIndividualConfusionMatrix();
    foreach ($knn->classificationTable as $k => $v) {
      if ($v["specificity"] != number_format($listValues[$k], $knn->getDecimalNumberVars())) {
        $workingWell = false;
      }
    }
    $this->assertEquals(true, $workingWell);
  }

  /**
   * @dataProvider knnMetricsProvider
   */
  public function testGetFMEasureFinal(Knn $knn)
  {
    //Medida - F     = 2 *(Precisão X Recall) / (Precisão+Recall)
    //      = 2 * .75 * .8 / (.75 + .8) = .77
    $workingWell = true;
    $listValues = [
      "Iris-setosa" => 1,
      "Iris-versicolor" => 0.917,
      "Iris-virginica" => 0.882
    ];

    $knn->fillIndividualConfusionMatrix();
    foreach ($knn->classificationTable as $k => $v) {
      if ($v["fMeasure"] != number_format($listValues[$k], $knn->getDecimalNumberVars())) {
        $workingWell = false;
      }
    }
    $this->assertEquals(true, $workingWell);
  }

  public function knnMetricsProvider()
  {
    $knn = new Knn(null, null, null);

    $knn->classificationTable["Iris-setosa"]["tp"] = doubleval(14);
    $knn->classificationTable["Iris-setosa"]["tn"] = doubleval(37);
    $knn->classificationTable["Iris-setosa"]["fp"] = doubleval(0);
    $knn->classificationTable["Iris-setosa"]["fn"] = doubleval(0);

    $knn->classificationTable["Iris-versicolor"]["tp"] = doubleval(22);
    $knn->classificationTable["Iris-versicolor"]["tn"] = doubleval(29);
    $knn->classificationTable["Iris-versicolor"]["fp"] = doubleval(2);
    $knn->classificationTable["Iris-versicolor"]["fn"] = doubleval(2);

    $knn->classificationTable["Iris-virginica"]["tp"] = doubleval(15);
    $knn->classificationTable["Iris-virginica"]["tn"] = doubleval(36);
    $knn->classificationTable["Iris-virginica"]["fp"] = doubleval(2);
    $knn->classificationTable["Iris-virginica"]["fn"] = doubleval(2);

    return [[$knn]];
  }
}