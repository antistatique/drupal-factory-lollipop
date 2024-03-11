<?php

namespace Drupal\Tests\factory_lollipop\Kernel\FactoryType;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\File\FileSystemInterface;
use Drupal\factory_lollipop\FactoryType\FileFactoryType;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\FileFactoryType
 *
 * @group factory_lollipop
 */
class FileFactoryTypeTest extends EntityKernelTestBase {

  use ProphecyTrait;
  /**
   * The Node Factory resolver.
   *
   * @var \Drupal\factory_lollipop\FactoryType\FileFactoryType
   */
  protected $fileFactoryTypeResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

    $this->installEntitySchema('file');

    $file_system = $this->prophesize(FileSystemInterface::class);
    $system_file_config = $this->prophesize(ImmutableConfig::class);
    $system_file_config->get('default_scheme')->willReturn('public');
    $config_factory = $this->prophesize(ConfigFactoryInterface::class);
    $config_factory->get('system.file')->willReturn($system_file_config->reveal());

    $this->fileFactoryTypeResolver = new FileFactoryType($config_factory->reveal(), $file_system->reveal());
    $this->fileFactoryTypeResolver->setEntityTypeManager($this->container->get('entity_type.manager'));
  }

  /**
   * Revert files and permissions changes.
   */
  public function tearDown(): void {
    // Delete unreadable tests file.
    @chmod('temporary://test-unreadable.txt', 0755);
    @unlink('temporary://test-unreadable.txt');
    @chmod('/var/tmp/test-unreadable.txt', 0755);
    @unlink('/var/tmp/test-unreadable.txtg');
  }

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'file',
  ];

  /**
   * Prevent creating a File Factory with a none existing file path attribute.
   *
   * @covers ::create
   */
  public function testCreateNotFilePathAttribute(): void {
    $file_path = $this->root . '/core/tests/fixtures/foo';
    $this->expectException(\InvalidArgumentException::class);
    if (method_exists($this, 'expectExceptionMessageMatches')) {
      $this->expectExceptionMessageMatches('#^File ".+/core/tests/fixtures/foo" does not exist\.$#');
    }
    else {
      $this->expectExceptionMessageMatches('#^File ".+/core/tests/fixtures/foo" does not exist\.$#');
    }
    $this->fileFactoryTypeResolver->create((object) ['path' => $file_path]);
  }

  /**
   * Prevent creating a File Factory with a not readable file path attribute.
   *
   * @covers ::create
   */
  public function testCreateNotReadableFilePathAttribute(): void {
    @file_put_contents('/var/tmp/test-unreadable.txt', $this->randomString());
    @chmod('/var/tmp/test-unreadable.txt', 000);
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('File "/var/tmp/test-unreadable.txt" cannot be read.');
    $this->fileFactoryTypeResolver->create((object) ['path' => '/var/tmp/test-unreadable.txt']);
  }

  /**
   * Prevent creating a File Factory with a none existing file uri attribute.
   *
   * @covers ::create
   */
  public function testCreateNotFileUriAttribute(): void {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('File "public://example-2.txt" does not exist.');
    $this->fileFactoryTypeResolver->create((object) ['uri' => 'public://example-2.txt']);
  }

  /**
   * Prevent creating a File Factory with a not readable file uri attribute.
   *
   * @covers ::create
   */
  public function testCreateNotReadableFileUriAttribute(): void {
    @file_put_contents('temporary://test-unreadable.txt', $this->randomString());
    @chmod('temporary://test-unreadable.txt', 000);
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('File "temporary://test-unreadable.txt" cannot be read.');
    $this->fileFactoryTypeResolver->create((object) ['uri' => 'temporary://test-unreadable.txt']);
  }

  /**
   * @covers ::create
   */
  public function testCreateFromPath(): void {
    $file_path = $this->root . '/core/tests/fixtures/files/image-test.png';
    $file = $this->fileFactoryTypeResolver->create((object) ['path' => $file_path]);

    self::assertEquals('image-test', $file->getFilename());
    self::assertEquals('public://image-test.png', $file->getFileUri());
    self::assertEquals('image/png', $file->getMimeType());

    $file = $this->fileFactoryTypeResolver->create((object) [
      'path' => $file_path,
      'scheme' => 'temporary',
    ]);
    self::assertEquals('temporary://image-test.png', $file->getFileUri());
  }

  /**
   * @covers ::create
   */
  public function testCreateFromUri(): void {
    file_put_contents('temporary://test.txt', $this->randomString());
    $file = $this->fileFactoryTypeResolver->create((object) ['uri' => 'temporary://test.txt']);

    self::assertEquals('test', $file->getFilename());
    self::assertEquals('temporary://test.txt', $file->getFileUri());
    self::assertEquals('text/plain', $file->getMimeType());

    $file = $this->fileFactoryTypeResolver->create((object) [
      'path' => 'temporary://test.txt',
      'scheme' => 'public',
    ]);
    self::assertEquals('public://test.txt', $file->getFileUri());
  }

  /**
   * @covers ::create
   */
  public function testCreateRandom(): void {
    $file = $this->fileFactoryTypeResolver->create((object) []);

    self::assertNotEmpty('test', $file->getFilename());
    self::assertStringStartsWith('public://', $file->getFileUri());
    self::assertStringEndsWith('.txt', $file->getFileUri());
    self::assertEquals('text/plain', $file->getMimeType());

    $file = $this->fileFactoryTypeResolver->create((object) ['scheme' => 'temporary']);
    self::assertStringStartsWith('temporary://', $file->getFileUri());
  }

  /**
   * @covers ::create
   *
   * @dataProvider providerFileValues
   */
  public function testCreate($filename, $expected): void {
    $file_path = $this->root . '/core/tests/fixtures/files/' . $filename;
    $file = $this->fileFactoryTypeResolver->create((object) ['path' => $file_path]);

    self::assertEquals($expected['filename'], $file->getFilename());
    self::assertEquals($expected['uri'], $file->getFileUri());
    self::assertEquals($expected['mimetype'], $file->getMimeType());
  }

  /**
   * @covers ::create
   */
  public function testCreateGetAutomaticFilename(): void {
    $file_path = $this->root . '/core/tests/fixtures/files/html-1.txt';
    $file = $this->fileFactoryTypeResolver->create((object) ['path' => $file_path]);

    self::assertEquals('html-1', $file->getFilename());
    self::assertEquals('public://html-1.txt', $file->getFileUri());
    self::assertEquals('text/plain', $file->getMimeType());
  }

  /**
   * Data provider for ::testCreate.
   *
   * @return array
   *   Data provided.
   */
  public function providerFileValues(): array {
    return [
      '.txt' => [
        'html-1.txt',
        [
          'filename' => 'html-1',
          'uri' => 'public://html-1.txt',
          'mimetype' => 'text/plain',
        ],
      ],
      '.png' => [
        'image-1.png',
        [
          'filename' => 'image-1',
          'uri' => 'public://image-1.png',
          'mimetype' => 'image/png',
        ],
      ],
      '.script' => [
        'javascript-2.script',
        [
          'filename' => 'javascript-2',
          'uri' => 'public://javascript-2.script',
          'mimetype' => 'application/octet-stream',
        ],
      ],
      '.html' => [
        'html-2.html',
        [
          'filename' => 'html-2',
          'uri' => 'public://html-2.html',
          'mimetype' => 'text/html',
        ],
      ],
      '.php' => [
        'php-2.php',
        [
          'filename' => 'php-2',
          'uri' => 'public://php-2.php',
          'mimetype' => 'application/x-httpd-php',
        ],
      ],
      '.sql' => [
        'sql-2.sql',
        [
          'filename' => 'sql-2',
          'uri' => 'public://sql-2.sql',
          'mimetype' => 'application/octet-stream',
        ],
      ],
    ];
  }

}
