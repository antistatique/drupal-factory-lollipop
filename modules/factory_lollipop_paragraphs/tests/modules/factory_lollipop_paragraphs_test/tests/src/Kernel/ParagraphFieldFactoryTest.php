<?php

namespace Drupal\Tests\factory_lollipop_paragraphs_test\Kernel;

use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Tests\factory_lollipop\Kernel\LollipopKernelTestBase;

/**
 * Example of Factory Lollipop usage for Paragraph with fields.
 *
 * @group factory_lollipop
 * @group factory_lollipop_paragraphs
 * @group factory_lollipop_example
 */
class ParagraphFieldFactoryTest extends LollipopKernelTestBase {

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
    // Required by Paragraphs.
    'paragraphs',
    'entity_reference_revisions',
    'file',
    // Factory Lollipop.
    'factory_lollipop_paragraphs',
    'factory_lollipop_paragraphs_test',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

    $this->installEntitySchema('paragraph');
  }

  /**
   * Ensure defined Paragraph can be created with fields.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::association
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\EntityFieldFactoryType::create
   */
  public function testCreateParagraphWithFields(): void {
    $this->factoryLollipop->loadDefinitions(['paragraph_faq']);

    $paragraph = $this->factoryLollipop->create('paragraph_faq', ['field_question' => 'Magna cursus tempor ?']);

    // Assert the paragraph is created.
    self::assertInstanceOf(Paragraph::class, $paragraph);
    self::assertEquals('faq', $paragraph->bundle());

    // Assert a field w/o default values is then empty by default.
    self::assertTrue($paragraph->hasField('field_tag'));
    self::assertTrue($paragraph->get('field_tag')->isEmpty());
    self::assertEmpty($paragraph->get('field_tag')->value);

    // Assert a field may be filled with value.
    self::assertTrue($paragraph->hasField('field_question'));
    self::assertFalse($paragraph->get('field_question')->isEmpty());
    self::assertSame('Magna cursus tempor ?', $paragraph->get('field_question')->value);

    // Assert a field with default values is filled by default.
    self::assertTrue($paragraph->hasField('field_answer'));
    self::assertFalse($paragraph->get('field_answer')->isEmpty());
    self::assertSame('We have not answer for this question.', $paragraph->get('field_answer')->value);
  }

  /**
   * Ensure defined Paragraph Field values can be overridden.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::association
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\EntityFieldFactoryType::create
   */
  public function testCreateParagraphWithFieldsValues(): void {
    $this->factoryLollipop->loadDefinitions(['paragraph_faq']);

    $paragraph = $this->factoryLollipop->create('paragraph_faq', [
      'field_question' => 'Netus ex tortor ?',
      'field_answer' => 'Facilisis metus ut massa molestie habitant arcu.',
    ]);

    self::assertInstanceOf(Paragraph::class, $paragraph);
    self::assertEquals('faq', $paragraph->bundle());
    self::assertTrue($paragraph->hasField('field_question'));
    self::assertFalse($paragraph->get('field_question')->isEmpty());
    self::assertSame('Netus ex tortor ?', $paragraph->get('field_question')->value);
    self::assertTrue($paragraph->hasField('field_answer'));
    self::assertFalse($paragraph->get('field_answer')->isEmpty());
    self::assertSame('Facilisis metus ut massa molestie habitant arcu.', $paragraph->get('field_answer')->value);
  }

}
