<?php

namespace Drupal\Tests\factory_lollipop_paragraphs\Kernel\FactoryType;

use Drupal\factory_lollipop_paragraphs\FactoryType\ParagraphFactoryType;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\paragraphs\Entity\ParagraphsType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * @coversDefaultClass \Drupal\factory_lollipop_paragraphs\FactoryType\ParagraphFactoryType
 *
 * @group factory_lollipop
 * @group factory_lollipop_paragraphs
 */
#[CoversClass(\Drupal\factory_lollipop_paragraphs\FactoryType\ParagraphFactoryType::class)]
#[CoversMethod(\Drupal\factory_lollipop_paragraphs\FactoryType\ParagraphFactoryType::class, 'create')]
#[Group('factory_lollipop')]
#[Group('factory_lollipop_paragraphs')]
#[RunTestsInSeparateProcesses]
class ParagraphFactoryTypeTest extends EntityKernelTestBase {

  /**
   * The Paragraph Factory resolver.
   *
   * @var \Drupal\factory_lollipop\FactoryType\ParagraphFactoryType
   */
  protected $paragraphFactoryTypeResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

    $this->installEntitySchema('paragraph');

    $this->paragraphFactoryTypeResolver = new ParagraphFactoryType();
    $this->paragraphFactoryTypeResolver->setEntityTypeManager($this->container->get('entity_type.manager'));
  }

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'paragraphs',
    'entity_reference_revisions',
    'file',
  ];

  /**
   * @covers ::create
   */
  public function testCreate(): void {
    // Create a paragraph type for testing.
    $paragraph_type = ParagraphsType::create(['id' => 'faq']);
    $paragraph_type->save();

    $paragraph = $this->paragraphFactoryTypeResolver->create((object) ['type' => 'faq']);

    self::assertEquals('faq', $paragraph->bundle());

    // Paragraphs will always have a translation.
    self::assertEquals('en', $paragraph->getTranslationLanguages()['en']->getId());
  }

  /**
   * Prevent creating a Paragraph Factory without type attribute.
   *
   * @covers ::create
   */
  public function testCreateParagraphTypeMustExists(): void {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('The type attribute must be an existing Paragraph Type.');
    $this->paragraphFactoryTypeResolver->create((object) [
      'type' => 'foo',
    ]);
  }

}
