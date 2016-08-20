<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container;

use samsonframework\container\resolver\ResolverInterface;
use samsonframework\filemanager\FileManagerInterface;
use samsonphp\generator\Generator;

/**
 * Class Container.
 */
class ContainerBuilder extends Builder
{
    /**
     * @var FileManagerInterface
     * @Injectable
     */
    protected $fileManger;

    /**
     * @var ResolverInterface
     * @Injectable
     */
    protected $classResolver;

    /**
     * Container constructor.
     *
     * @param FileManagerInterface $fileManager
     * @param ResolverInterface    $classResolver
     * @param Generator            $generator
     */
    public function __construct(FileManagerInterface $fileManager, ResolverInterface $classResolver, Generator $generator)
    {
        $this->fileManger = $fileManager;
        $this->classResolver = $classResolver;

        parent::__construct($generator, $this->classesMetadata);
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
            require_once($phpFile);
            // Read all classes in given file
            $this->loadFromClassNames($this->getDefinedClasses(file_get_contents($phpFile)));
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
            $this->classesMetadata[$className] = $this->classResolver->resolve(new \ReflectionClass($className));
            // Store by metadata name as alias
            $this->classAliases[$this->classesMetadata[$className]->name] = $className;
            // Store class in defined scopes
            foreach ($this->classesMetadata[$className]->scopes as $scope) {
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
    protected function getDefinedClasses(string $php) : array
    {
        $classes = [];
        $namespace = null;

        // Append php marker for parsing file
        $php = strpos(is_string($php) ? $php : '', '<?php') !== 0 ? '<?php ' . $php : $php;

        $tokens = token_get_all($php);

        for ($i = 2, $count = count($tokens); $i < $count; $i++) {
            if ($tokens[$i - 2][0] === T_CLASS
                && $tokens[$i - 1][0] === T_WHITESPACE
                && $tokens[$i][0] === T_STRING
            ) {
                $classes[] = $namespace ? $namespace . '\\' . $tokens[$i][1] : $tokens[$i][1];
            } elseif ($tokens[$i - 2][0] === T_NAMESPACE
                && $tokens[$i - 1][0] === T_WHITESPACE
                && $tokens[$i][0] === T_STRING
            ) {
                while (isset($tokens[$i]) && is_array($tokens[$i])) {
                    $namespace .= $tokens[$i++][1];
                }
            }
        }

        return $classes;
    }

    /**
     * Load classes from PHP c ode.
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
}
