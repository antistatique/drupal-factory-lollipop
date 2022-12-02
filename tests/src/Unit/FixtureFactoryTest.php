<?php

namespace Drupal\Tests\factory_lollipop\Unit\Resolver;

use Drupal\factory_lollipop\FactoryType\FactoryTypeInterface;
use Drupal\factory_lollipop\FixtureFactory;
use Drupal\factory_lollipop\Resolver\ChainFactoryTypeResolver;
use Drupal\factory_lollipop\Resolver\ChainFactoryResolver;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FixtureFactory
 *
 * @group factory_lollipop
 */
class FixtureFactoryTest extends UnitTestCase {

  /**
   * The Factory Lollipop fixture factory.
   *
   * @var \Drupal\factory_lollipop\FixtureFactory
   */
  protected $factoryLollipop;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $chain_factory_type_resolver = new ChainFactoryTypeResolver();
    $chain_factory_resolver = new ChainFactoryResolver();
    $this->factoryLollipop = new FixtureFactory($chain_factory_type_resolver, $chain_factory_resolver);
  }

  /**
   * @covers ::create
   *
   * @see \Drupal\Tests\factory_lollipop_test\Kernel\FileFactoryTest
   * @see \Drupal\Tests\factory_lollipop_test\Kernel\MediaFactoryTest
   * @see \Drupal\Tests\factory_lollipop_test\Kernel\MediaFieldEntityReferenceFactoryTest
   * @see \Drupal\Tests\factory_lollipop_test\Kernel\MediaFieldFactoryTest
   * @see \Drupal\Tests\factory_lollipop_test\Kernel\MediaTypeFactoryTest
   * @see \Drupal\Tests\factory_lollipop_test\Kernel\MenuFactoryTest
   * @see \Drupal\Tests\factory_lollipop_test\Kernel\MenuLinkFactoryTest
   * @see \Drupal\Tests\factory_lollipop_test\Kernel\MenuLinksFactoryTest
   * @see \Drupal\Tests\factory_lollipop_test\Kernel\NodeFactoryTest
   * @see \Drupal\Tests\factory_lollipop_test\Kernel\NodeFieldEntityReferenceFactoryTest
   * @see \Drupal\Tests\factory_lollipop_test\Kernel\NodeFieldFactoryTest
   * @see \Drupal\Tests\factory_lollipop_test\Kernel\NodeTypeFactoryTest
   * @see \Drupal\Tests\factory_lollipop_test\Kernel\RoleFactoryTest
   * @see \Drupal\Tests\factory_lollipop_test\Kernel\TaxonomyTermFactoryTest
   * @see \Drupal\Tests\factory_lollipop_test\Kernel\TaxonomyTermFieldEntityReferenceFactoryTest
   * @see \Drupal\Tests\factory_lollipop_test\Kernel\TaxonomyTermFieldFactoryTest
   * @see \Drupal\Tests\factory_lollipop_test\Kernel\UserFactoryTest
   * @see \Drupal\Tests\factory_lollipop_test\Kernel\VocabularyFactoryTest
   */
  public function testCreate(): void {
    $factory_type_foo = $this->getMockBuilder(FactoryTypeInterface::class)
      ->disableOriginalConstructor()
      ->getMock();

    $factory_type_foo
      ->expects(self::once())
      ->method('shouldApply')
      ->with(self::equalTo('foo type'))
      ->willReturn(TRUE);

    $factory_type_foo
      ->expects(self::once())
      ->method('create')
      ->with(self::equalTo((object) ['default' => 'lorem', 'arg1' => 'bar']));

    $chain_factory_type_resolver = $this->getMockBuilder(ChainFactoryTypeResolver::class)
      ->disableOriginalConstructor()
      ->getMock();

    $chain_factory_type_resolver
      ->expects(self::once())
      ->method('getResolvers')
      ->willReturn([$factory_type_foo]);

    $chain_factory_resolver = $this->getMockBuilder(ChainFactoryResolver::class)
      ->disableOriginalConstructor()
      ->getMock();

    $factory_Lollipop = $this->getMockBuilder(FixtureFactory::class)
      ->setConstructorArgs([
        $chain_factory_type_resolver,
        $chain_factory_resolver,
      ])
      ->onlyMethods(['getDefaultOptions', 'getType'])
      ->getMock();

    // Ensure ::getDefaultOptions will be called with foo argument.
    $factory_Lollipop
      ->expects(self::once())
      ->method('getDefaultOptions')
      ->with(self::equalTo('foo'))
      ->willReturn(['default' => 'lorem']);

    // Ensure ::getType will be called with foo argument.
    $factory_Lollipop
      ->expects(self::once())
      ->method('getType')
      ->with(self::equalTo('foo'))
      ->willReturn('foo type');

    $factory_Lollipop->create('foo', ['arg1' => 'bar']);
  }

  /**
   * @covers ::define
   * @covers ::getDefinitions
   * @covers ::getDefinition
   */
  public function testDefinitions(): void {
    self::assertEmpty($this->factoryLollipop->getDefinitions());
    self::assertNull($this->factoryLollipop->getDefinition('foo'));

    $this->factoryLollipop->define('foo', 'foo_bar', [
      'foo' => 'bar',
      'foo_func' => function () {
        return 'callable';
      },
    ]);

    // When getting all definitions, callable should still be unprocessed.
    self::assertEquals([
      'foo_bar' => [
        'type' => 'foo',
        'opts' => [
          'foo' => 'bar',
          'foo_func' => function () {
            return 'callable';
          },
        ],
      ],
    ], $this->factoryLollipop->getDefinitions());

    // Element are retrievable by name and not by type.
    self::assertNull($this->factoryLollipop->getDefinition('foo'));
    self::assertNull($this->factoryLollipop->getDefinition('bar'));
    self::assertEquals([
      'type' => 'foo',
      'opts' => [
        'foo' => 'bar',
        'foo_func' => function () {
          return 'callable';
        },
      ],
    ], $this->factoryLollipop->getDefinition('foo_bar'));
  }

  /**
   * @covers ::getDefaultOptions
   */
  public function testGetDefaultOptionsProcessCallableClosure(): void {
    $this->factoryLollipop->define('foo', 'foo_bar', [
      'foo' => 'bar',
      'foo_func' => function () {
        return 'callable';
      },
    ]);

    $default_options = $this->factoryLollipop->getDefaultOptions('foo_bar');
    self::assertEquals([
      'foo' => 'bar',
      'foo_func' => 'callable',
    ], $default_options);
  }

  /**
   * @covers ::getDefaultOptions
   */
  public function testGetDefaultOptionsDoesNotProcessGlobalFunction(): void {
    $this->factoryLollipop->define('foo', 'foo_bar', [
      'foo' => 'count',
    ]);

    // Ensure global function are not detected as callable closure.
    $default_options = $this->factoryLollipop->getDefaultOptions('foo_bar');
    self::assertEquals([
      'foo' => 'count',
    ], $default_options);
  }

  /**
   * @covers ::getDefaultOptions
   */
  public function testGetDefaultOptionsUndefinedFactoryDefinition(): void {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('There is no factory definition called foo_bar.');
    $this->factoryLollipop->getDefaultOptions('foo_bar');
  }

  /**
   * @covers ::create
   */
  public function testCreateUndefinedFactoryDefinition(): void {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('There is no factory definition called foo_bar.');
    $this->factoryLollipop->create('foo_bar');
  }

  /**
   * @covers ::create
   */
  public function testCreateDefinitionOfUnexistingResolver(): void {
    $this->factoryLollipop->define('foo bar', 'foo_bar', []);

    $this->expectException(\RuntimeException::class);
    $this->expectExceptionMessage("Factories of type 'foo bar' are not supported.");
    $this->factoryLollipop->create('foo_bar');
  }

  /**
   * @covers ::getType
   */
  public function testGetType(): void {
    self::assertNull($this->factoryLollipop->getType('foo_bar'));
    $this->factoryLollipop->define('foo bar', 'foo_bar', []);
    self::assertEquals('foo bar', $this->factoryLollipop->getType('foo_bar'));
  }

  /**
   * @covers ::association
   */
  public function testAssociationIsClosure(): void {
    self::assertInstanceOf(\Closure::class, $this->factoryLollipop->association('foo'));
  }

  /**
   * @covers ::association
   */
  public function testAssociationUndefinedFactoryType(): void {
    // Define a unsupported Factory Type to trigger error.
    $this->factoryLollipop->define('foo', 'foo_bar', []);

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage("Factories of type 'foo' are not supported.");
    $this->factoryLollipop->association('foo_bar')();
  }

  /**
   * @covers ::sequence
   */
  public function testSequenceGeneratorCallsFunctionWithAnIncrementingArgument(): void {
    $this->factoryLollipop->define('foo', 'foo_bar', [
      'foo' => FixtureFactory::sequence(function ($n) {
        return "Alpha $n";
      }),
    ]);

    self::assertSame('Alpha 1', $this->factoryLollipop->getDefaultOptions('foo_bar')['foo']);
    self::assertSame('Alpha 2', $this->factoryLollipop->getDefaultOptions('foo_bar')['foo']);
    self::assertSame('Alpha 3', $this->factoryLollipop->getDefaultOptions('foo_bar')['foo']);
    self::assertSame('Alpha 4', $this->factoryLollipop->getDefaultOptions('foo_bar')['foo']);
  }

  /**
   * @covers ::sequence
   */
  public function testSequenceGeneratorCanTakePlaceholderString(): void {
    $this->factoryLollipop->define('foo', 'foo_bar', [
      'name' => FixtureFactory::sequence("Beta %d"),
    ]);

    self::assertSame('Beta 1',
    $this->factoryLollipop->getDefaultOptions('foo_bar')['name']);
    self::assertSame('Beta 2',
    $this->factoryLollipop->getDefaultOptions('foo_bar')['name']);
    self::assertSame('Beta 3',
    $this->factoryLollipop->getDefaultOptions('foo_bar')['name']);
    self::assertSame('Beta 4',
    $this->factoryLollipop->getDefaultOptions('foo_bar')['name']);
  }

  /**
   * @covers ::sequence
   */
  public function testSequenceGeneratorCanTakeStringToAppendTo(): void {
    $this->factoryLollipop->define('foo', 'foo_bar', [
      'label' => FixtureFactory::sequence("Gamma "),
    ]);
    self::assertSame('Gamma 1', $this->factoryLollipop->getDefaultOptions('foo_bar')['label']);
    self::assertSame('Gamma 2', $this->factoryLollipop->getDefaultOptions('foo_bar')['label']);
    self::assertSame('Gamma 3', $this->factoryLollipop->getDefaultOptions('foo_bar')['label']);
    self::assertSame('Gamma 4', $this->factoryLollipop->getDefaultOptions('foo_bar')['label']);
  }

}
