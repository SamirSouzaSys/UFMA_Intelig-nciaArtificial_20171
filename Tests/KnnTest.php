<?php
use KNN\Knn\Knn;
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
      $knn->trainingSet[0], $knn->trainingSet[1], $knn->length);
//    $this->assertEquals(3.4641016151378,$result);
    $this->assertEquals(4,$result);
//    3,4641
  }

  public function euclideanDistanceProvider()
  {
    $knn = new Knn(null, null, 4);
    $knn->trainingSet = [ [2, 2, 2, 2, "a"],[4, 4, 4, 4, "b"] ];
    return [[$knn]];
  }

  /**
   * @dataProvider neighborsProvider
   */
  public function testGetNeighbors(Knn $knn)
  {
    $ideal[] = [4,4,4,4,"b"];

    $k = 3;
    $result = $knn->getNeighbors($k);
    $this->assertEquals($ideal , $result );
  }

  public function neighborsProvider()
  {
    $knn = new Knn(null, null, 4);
    $knn->trainingSet = [ [2, 2, 2, 2, "a"],[4, 4, 4, 4, "b"] ];
    $knn->testSet = [[5, 5, 5, 5]];
    return [[$knn]];
  }

  /**
   * @dataProvider responseProvider
   */
  public function testResponse(Knn $knn, $neighbors)
  {
    $result = $knn->getResponse($neighbors);

    $this->assertEquals(2 , $result );
  }

  public function responseProvider()
  {
    $knn = new Knn();
    $neighbors = [ [1, 1, 1,"a"],[2, 2, 2, "a"],[3, 3, 3, "b"] ];
    return [[$knn, $neighbors]];
  }

  /**
   * @dataProvider accuracyProvider
   */
  public function testAccuracy(Knn $knn, $predictions)
  {
    $result = $knn->getAccuracy($predictions);
    print $result;
    $this->assertEquals(66 , intval($result) );
  }

  public function accuracyProvider()
  {
    $testSet = [ [1, 1, 1, 1, "a"],[2, 2, 2, 2, "a"],[3, 3, 3, 3, "b"] ];
    $predictions = ["a","a","a","a"];
    $knn = new Knn(null,null,4);
    $knn->testSet = $testSet;
    return [[$knn, $predictions]];
  }
}