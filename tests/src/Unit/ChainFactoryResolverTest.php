<?php

namespace Drupal\Tests\factory_lollipop\Unit\Resolver;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\factory_lollipop\FactoryInterface;
use Drupal\factory_lollipop\Resolver\ChainFactoryResolver;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\Resolver\ChainFactoryResolver
 *
 * @group factory_lollipop
 */
class ChainFactoryResolverTest extends UnitTestCase {

  /**
   * The resolver.
   *
   * @var \Drupal\factory_lollipop\Resolver\ChainFactoryResolver
   */
  protected $chainFactoryResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->chainFactoryResolver = new ChainFactoryResolver();
  }

  /**
   * Tests the resolver and priority.
   *
   * @covers ::addResolver
   * @covers ::getResolvers
   */
  public function testResolver(): void {
    $container = new ContainerBuilder();

    $mock_builder = $this->getMockBuilder(FactoryInterface::class)
      ->disableOriginalConstructor();

    $first_resolver = $mock_builder->getMock();
    $container->set('factory_lollipop.factory_first_resolver', $first_resolver);

    $second_resolver = $mock_builder->getMock();
    $container->set('factory_lollipop.factory_second_resolver', $second_resolver);

    // Mimic how the container would add the services.
    // @see \Drupal\Core\DependencyInjection\Compiler\TaggedHandlersPass::process
    $resolvers = [
      'factory_lollipop.factory_first_resolver' => 600,
      'factory_lollipop.factory_second_resolver' => 10,
    ];
    arsort($resolvers, SORT_NUMERIC);
    foreach ($resolvers as $id => $priority) {
      $this->chainFactoryResolver->addResolver($container->get($id));
    }

    $resolvers = $this->chainFactoryResolver->getResolvers();
    self::assertCount(2, $resolvers);
  }

}
