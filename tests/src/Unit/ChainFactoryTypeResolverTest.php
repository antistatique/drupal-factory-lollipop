<?php

namespace Drupal\Tests\factory_lollipop\Unit\Resolver;

use Drupal\factory_lollipop\FactoryType\FactoryTypeInterface;
use Drupal\factory_lollipop\Resolver\ChainFactoryTypeResolver;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\Resolver\ChainFactoryTypeResolver
 *
 * @group factory_lollipop
 */
class ChainFactoryTypeResolverTest extends UnitTestCase {

  /**
   * The resolver.
   *
   * @var \Drupal\factory_lollipop\Resolver\ChainFactoryTypeResolver
   */
  protected $chainFactoryTypeResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->chainFactoryTypeResolver = new ChainFactoryTypeResolver();
  }

  /**
   * Tests the resolver and priority.
   *
   * @covers ::addResolver
   * @covers ::getResolvers
   */
  public function testResolver(): void {
    $container = new ContainerBuilder();

    $mock_builder = $this->getMockBuilder(FactoryTypeInterface::class)
      ->disableOriginalConstructor();

    $first_resolver = $mock_builder->getMock();
    $container->set('factory_lollipop.first_resolver', $first_resolver);

    $second_resolver = $mock_builder->getMock();
    $container->set('factory_lollipop.second_resolver', $second_resolver);

    $third_resolver = $mock_builder->getMock();
    $container->set('factory_lollipop.third_resolver', $third_resolver);

    // Mimic how the container would add the services.
    // @see \Drupal\Core\DependencyInjection\Compiler\TaggedHandlersPass::process
    $resolvers = [
      'factory_lollipop.first_resolver' => 900,
      'factory_lollipop.second_resolver' => 400,
      'factory_lollipop.third_resolver' => -100,
    ];
    arsort($resolvers, SORT_NUMERIC);
    foreach ($resolvers as $id => $priority) {
      $this->chainFactoryTypeResolver->addResolver($container->get($id));
    }

    $resolvers = $this->chainFactoryTypeResolver->getResolvers();
    self::assertCount(3, $resolvers);
  }

}
