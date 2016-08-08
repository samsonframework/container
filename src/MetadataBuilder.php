<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container;

use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\resolver\ResolverInterface;
use samsonframework\di\Container;
use samsonframework\filemanager\FileManagerInterface;
use samsonphp\generator\Generator;

/**
 * Class Container.
 */
class MetadataBuilder
{
    /** Controller classes scope name */
    const SCOPE_CONTROLLER = 'controllers';

    /** Service classes scope name */
    const SCOPE_SERVICES = 'services';

    /** Generated dependency injecttion resolving function name prefix */
    const DI_FUNCTION_PREFIX = 'container';

    /** @var string[] Collection of available container scopes */
    protected $scopes = [
        self::SCOPE_CONTROLLER => [],
        self::SCOPE_SERVICES => []
    ];

    /** @var ClassMetadata[] Collection of classes metadata */
    protected $classMetadata = [];

    /** @var FileManagerInterface */
    protected $fileManger;

    /** @var ResolverInterface */
    protected $classResolver;

    /** @var Container */
    protected $diContainer;

    /** @var Generator */
    protected $generator;

    /**
     * Container constructor.
     *
     * @param FileManagerInterface $fileManger
     * @param ResolverInterface    $classResolver
     * @param Container            $diContainer
     */
    public function __construct(
        FileManagerInterface $fileManger,
        ResolverInterface $classResolver,
        Generator $generator,
        Container $diContainer
    )
    {
        $this->diContainer = $diContainer;
        $this->fileManger = $fileManger;
        $this->classResolver = $classResolver;
        $this->generator = $generator;
    }

    /**
     * Load classes from paths.
     *
     * @param array $paths Paths for importing
     *
     * @return $this
     */
    public function loadFromPaths(array $paths)
    {
        // Iterate all paths and get files
        foreach ($this->fileManger->scan($paths, ['php']) as $phpFile) {
            // Read all classes in given file
            $this->loadFromClassNames($this->getDefinedClasses(require_once($phpFile)));
        }

        return $this;
    }

    /**
     * Load classes from class names collection.
     *
     * @param string[] $classes Collection of class names for resolving
     *
     * @return $this
     */
    public function loadFromClassNames(array $classes)
    {
        // Read all classes in given file
        foreach ($classes as $className) {
            // Resolve class metadata
            $this->classMetadata[$className] = $this->classResolver->resolve(new \ReflectionClass($className));
            // Store class in defined scopes
            foreach ($this->classMetadata[$className]->scopes as $scope) {
                $this->scopes[$scope][] = $className;
            }
        }

        return $this;
    }

    /**
     * Find class names defined in PHP code.
     *
     * @param string $php PHP code for scanning
     *
     * @return string[] Collection of found class names in php code
     */
    protected function getDefinedClasses($php) : array
    {
        $classes = array();

        // Append php marker for parsing file
        $php = strpos(is_string($php) ? $php : '', '<?php') !== 0 ? '<?php ' . $php : $php;

        $tokens = token_get_all($php);

        for ($i = 2, $count = count($tokens); $i < $count; $i++) {
            if ($tokens[$i - 2][0] === T_CLASS
                && $tokens[$i - 1][0] === T_WHITESPACE
                && $tokens[$i][0] === T_STRING
            ) {
                $classes[] = $tokens[$i][1];
            }
        }

        return $classes;
    }

    /**
     * Load classes from PHP code.
     *
     * @param string $php PHP code
     *
     * @return $this
     */
    public function loadFromCode($php)
    {
        if (count($classes = $this->getDefinedClasses($php))) {
            // TODO: Consider writing cache file and require it
            eval($php);
            $this->loadFromClassNames($classes);
        }

        return $this;
    }

    /**
     * Build container class.
     *
     * @param string|null $containerClass Container class name
     * @param string      $namespace      Name space
     *
     * @return string Generated Container class code
     */
    public function build($containerClass = 'Container', $namespace = '')
    {
        // Build dependency injection container function name
        $diFunctionName = uniqid(self::DI_FUNCTION_PREFIX);

        $this->generator
            ->text('declare(strict_types = 1);')
            ->newLine()
            ->defNamespace($namespace)
            ->multiComment([
                'Application container',
            ])
            ->defClass($containerClass, '\\' . Container::class)
            ->commentVar('callable', 'Logic function')
            ->defClassVar('$logicCallable', 'protected', $diFunctionName);

        foreach ($this->classMetadata as $className => $classMetadata) {
            // Process constructor dependencies
            $constructorDeps = [];
            if (array_key_exists('__construct', $classMetadata->methodsMetadata)) {
                $constructorDeps = $classMetadata->methodsMetadata['__construct']->dependencies;
            }

            $dependencyName = $className;

            // If this class has services scope
            if (in_array($className, $this->scopes[self::SCOPE_SERVICES], true)) {
                $this->diContainer->service($className, $constructorDeps, $dependencyName = $classMetadata->name);
            } else { // Handle not service classes dependencies
                $this->diContainer->set($className, $constructorDeps, $dependencyName);
            }

            $this->generator
                ->defClassFunction('get' . str_replace(' ', '', ucwords(ucfirst(str_replace(['\\', '_'], ' ', $dependencyName)))))
                ->newLine('return ' . $diFunctionName . '(\'' . $dependencyName . '\');')
                ->endClassFunction();

        }

        // Build di container function and add to container class and return class code
        return $this->generator
            ->endClass()
            ->newLine($this->diContainer->build($diFunctionName))
            ->flush();
    }
}
