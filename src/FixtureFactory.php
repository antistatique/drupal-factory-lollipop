<?php

namespace Drupal\factory_lollipop;

use Drupal\factory_lollipop\Resolver\ChainFactoryResolver;
use Drupal\factory_lollipop\Resolver\ChainFactoryTypeResolver;

/**
 * Define and create Factories based on resolved Factory Type for use in tests.
 */
class FixtureFactory {

  /**
   * The factory definitions.
   *
   * @var array
   */
  protected $definitions = [];

  /**
   * The factory type resolver.
   *
   * @var \Drupal\factory_lollipop\Resolver\ChainFactoryTypeResolver
   */
  protected $chainFactoryTypeResolver;

  /**
   * The factories resolver.
   *
   * @var \Drupal\factory_lollipop\Resolver\ChainFactoryResolver
   */
  protected $chainFactoryResolver;

  /**
   * Construct a new FixtureFactory object.
   *
   * @param \Drupal\factory_lollipop\Resolver\ChainFactoryTypeResolver $chain_factory_type_resolver
   *   The factory type resolver.
   * @param \Drupal\factory_lollipop\Resolver\ChainFactoryResolver $chain_factory_resolver
   *   The factories resolver.
   */
  public function __construct(ChainFactoryTypeResolver $chain_factory_type_resolver, ChainFactoryResolver $chain_factory_resolver) {
    $this->chainFactoryTypeResolver = $chain_factory_type_resolver;
    $this->chainFactoryResolver = $chain_factory_resolver;
  }

  /**
   * A method of registering factory definitions.
   *
   * A factory of type with name will be defined, allowing for calls to
   * $this->>create to simply look up and produce standard objects.
   *
   * @param string $type
   *   The type of the object to create, accepted values are:
   *   - taxonomy
   *   - term.
   * @param string $name
   *   The name to register for the factory (must be unique)
   * @param array $opts
   *   An array containing the default options for the factory.
   */
  public function define(string $type, string $name, array $opts): void {
    $this->definitions[$name] = ['type' => $type, 'opts' => $opts];
  }

  /**
   * A method for creating objects from factory definitions.
   *
   * The object is created with the supplied options, persisted,
   * and returned to the caller.
   *
   * @param string $name
   *   The name of the factory to generate.
   * @param array $opts
   *   An associative array with options for the factory.
   *
   * @return mixed
   *   The newly created or already existing Drupal Entity|Object.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function create(string $name, array $opts = []) {
    $factory_array = array_merge($this->getDefaultOptions($name), $opts);

    $factory_object = (object) $factory_array;
    $factory_type = $this->getType($name);

    foreach ($this->chainFactoryTypeResolver->getResolvers() as $resolver) {
      if ($resolver->shouldApply($factory_type)) {
        return $resolver->create($factory_object);
      }
    }

    throw new \RuntimeException("Factories of type '{$factory_type}' are not supported.");
  }

  /**
   * Returns the factory type for a given definition.
   *
   * @param string $name
   *   The name of the definition.
   *
   * @return string|null
   *   The type of the factory. Null when the name has not been defined.
   */
  public function getType($name): ?string {
    return $this->definitions[$name]['type'] ?? NULL;
  }

  /**
   * Creates associated objects for a field.
   *
   * The association is a Callable function because it may need to run on
   * cascade for Factory creation (Eg. Node -> Node-Type).
   *
   * @param string $name
   *   The name of the factory to generate.
   * @param array $opts
   *   An associative array with options for the factory.
   *
   * @return Callable
   *   A callable function that return the unique ID of associated Factory.
   *
   * @throws \InvalidArgumentException
   *   May throw an error when given $name has never been defined before.
   */
  public function association(string $name, array $opts = []): callable {
    return function () use ($name, $opts) {
      $factory_type = $this->getType($name);
      $factory_object = $this->create($name, $opts);

      foreach ($this->chainFactoryTypeResolver->getResolvers() as $resolver) {
        if ($resolver->shouldApply($factory_type)) {
          return $resolver->getIdentifier($factory_object);
        }
      }

      throw new \InvalidArgumentException("Factories of type '{$factory_type}' are not supported.");
    };
  }

  /**
   * Returns the default options generated for a given factory.
   *
   * Each callable option will be processed in this method.
   *
   * @param string $factory_name
   *   The name of the factory options being fetched.
   *
   * @return array
   *   The generated options with all processed callable.
   *
   * @throws \InvalidArgumentException
   *   May throw an exception when attempting fetching unavailable factory.
   */
  public function getDefaultOptions(string $factory_name): array {
    if (!isset($this->definitions[$factory_name])) {
      throw new \InvalidArgumentException("There is no factory definition called {$factory_name}.");
    }

    $default_options = $this->definitions[$factory_name]['opts'];

    foreach ($default_options as $opt_name => $opt) {
      if ($opt instanceof \Closure) {
        $default_options[$opt_name] = $opt();
      }
    }

    return $default_options;
  }

  /**
   * This method loads and stores all factories definitions.
   *
   * @param array $excluded
   *   Factories name to avoid loading.
   */
  public function loadAllDefinitions(array $excluded = []): void {
    foreach ($this->chainFactoryResolver->getResolvers() as $resolver) {
      // Skip excluded factories.
      if (in_array($resolver->getName(), $excluded, TRUE)) {
        continue;
      }

      $resolver->resolve($this);
    }
  }

  /**
   * This method loads and stores specific factory definitions.
   *
   * @param string[] $factories
   *   Factories name to load.
   */
  public function loadDefinitions(array $factories): void {
    foreach ($this->chainFactoryResolver->getResolvers() as $resolver) {
      // Resolve only given factories.
      if (!in_array($resolver->getName(), $factories, TRUE)) {
        continue;
      }
      $resolver->resolve($this);
    }
  }

  /**
   * A method for inspecting currently defined factories.
   *
   * @return array
   *   Array of factory definitions.
   */
  public function getDefinitions(): array {
    return $this->definitions;
  }

  /**
   * Fetch one defined factories.
   *
   * @param string $name
   *   The Factory definition to inspect.
   *
   * @return array|null
   *   The defined factory as array or NULL when not found.
   */
  public function getDefinition(string $name): ?array {
    return $this->definitions[$name] ?? NULL;
  }

  /**
   * Creates a sequence string based on an incrementing integer.
   *
   * This is typically used to generate unique names such as usernames.
   *
   * The parameter may be a function that receives a counter value
   * each time the entity is created or it may be a string.
   *
   * If the parameter is a string string containing "%d" then it will be
   * replaced by the counter value. If the string does not contain "%d"
   * then the number is simply appended to the parameter.
   *
   * @param callable|string $generator
   *   The function or pattern to generate a value from.
   * @param int $start
   *   The first number to use.
   *
   * @return callable
   *   A callable function that to assist with uniqueness constraints.
   */
  public static function sequence($generator, $start = 1): callable {
    $n = $start - 1;
    if (is_callable($generator)) {
      return static function () use (&$n, $generator) {
        $n++;
        return $generator($n);
      };
    }

    if (strpos($generator, '%d') !== FALSE) {
      return static function () use (&$n, $generator) {
        $n++;
        return str_replace('%d', $n, $generator);
      };
    }

    return static function () use (&$n, $generator) {
      $n++;
      return $generator . $n;
    };
  }

}
