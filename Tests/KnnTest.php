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
   * @dataProvider knnProvider
   */
  public function testHandleOk($knn)
  {
    $knn->handle();
    $this->assertEquals(150, count($knn->trainingSet) + count($knn->testSet));
  }

  public function knnProvider()
  {
    $url = "https://archive.ics.uci.edu/ml/machine-learning-databases/iris/iris.data";
    $knn = new Knn($url, 0.66);
    return [
      [$knn]
    ];
  }
  public function testSimilarity($knn)
  {
    $knn->handle();
    $this->assertEquals(150, count($knn->trainingSet) + count($knn->testSet));
  }
}
