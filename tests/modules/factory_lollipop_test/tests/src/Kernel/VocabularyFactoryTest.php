<?php

namespace Drupal\Tests\factory_lollipop_test\Kernel;

use Drupal\taxonomy\Entity\Vocabulary;
use Drupal\taxonomy\VocabularyInterface;
use Drupal\Tests\factory_lollipop\Kernel\LollipopKernelTestBase;

/**
 * Example of Factory Lollipop usage for Vocabulary.
 *
 * @group factory_lollipop
 * @group factory_lollipop_example
 */
class VocabularyFactoryTest extends LollipopKernelTestBase {

  /**
   * The Factory Lollipop fixture factory.
   *
   * @var \Drupal\factory_lollipop\FixtureFactory
   */
  protected $factoryLollipop;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'taxonomy',
    'factory_lollipop_test',
  ];

  /**
   * Ensure a defined node type can be created.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\VocabularyFactoryType::create
   */
  public function testDefine(): void {
    $this->factoryLollipop->loadDefinitions(['vocabulary']);

    // Ensure the defined vocabulary is not created before call of ::create().
    $vocabulary = Vocabulary::load('tags');
    self::assertNull($vocabulary);

    /** @var \Drupal\taxonomy\VocabularyInterface $vocabulary */
    $vocabulary = $this->factoryLollipop->create('vocabulary_tags');
    self::assertInstanceOf(VocabularyInterface::class, $vocabulary);
    self::assertEquals('tags', $vocabulary->id());
  }

}
