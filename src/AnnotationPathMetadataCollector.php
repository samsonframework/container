<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 20.08.16 at 12:39
 */
namespace samsonframework\container;

use samsonframework\container\resolver\ResolverInterface;
use samsonframework\filemanager\FileManagerInterface;

/**
 * Annotation path class metadata collector.
 * Class read files in specified paths, resolves and collects class metadata from annotations.
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class AnnotationPathMetadataCollector extends AbstractMetadataCollector
{
    /** @var FileManagerInterface */
    protected $fileManager;

    /**
     * AnnotationPathMetadataCollector constructor.
     *
     * @param ResolverInterface    $resolver
     * @param FileManagerInterface $fileManager
     */
    public function __construct(ResolverInterface $resolver, FileManagerInterface $fileManager)
    {
        $this->fileManager = $fileManager;

        parent::__construct($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function collect($paths) : array
    {
        /** @var array $paths */
        $classesMetadata = [];

        // Iterate all paths and get files
        foreach ($this->fileManager->scan($paths, ['php']) as $phpFile) {
            require_once($phpFile);
            // Read all classes in given file
            foreach ($this->getDefinedClasses(file_get_contents($phpFile)) as $className) {
                $classesMetadata[$className] = $this->resolver->resolve(new \ReflectionClass($className));
            }
        }

        return $classesMetadata;
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
}
