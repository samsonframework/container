<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition;

use samsonframework\container\ContainerInterface;
use samsonframework\container\definition\analyzer\ClassAnalyzerInterface;
use samsonframework\container\definition\exception\ReferenceNotImplementsException;
use samsonframework\generator\ClassGenerator;

/**
 * Class DefinitionCompiler
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class DefinitionCompiler
{
    /**
     * Compile and get container
     *
     * @param DefinitionBuilder $definitionBuilder
     * @param $containerName
     * @param $namespace
     * @param $containerDir
     * @return ContainerInterface
     */
    public function compile(DefinitionBuilder $definitionBuilder, $containerName, $namespace, $containerDir)
    {
        // TODO Move this to construct injection
        $compiler = new DefinitionGenerator(
            (new ClassGenerator($containerName))
                ->defNamespace($namespace)
        );

        // Analyze builder
        $this->analyze($definitionBuilder);
        // Get container code
        $code = $compiler->generateClass($definitionBuilder->getDefinitionCollection());
        // Get file path
        $containerFilePath = rtrim($containerDir, '/') . '/' . $containerName . '.php';
        // Save file
        file_put_contents($containerFilePath, $code);
        // Get container class name
        $className = $namespace . '\\' . $containerName;
        // Require container
        require_once($containerFilePath);
        // Instantiate container
        return new $className();
    }

    /**
     * Analyze definition collection
     *
     * @param DefinitionBuilder $definitionBuilder
     */
    protected function analyze(DefinitionBuilder $definitionBuilder)
    {
        // Analyze class definitions
        foreach ($definitionBuilder->getDefinitionCollection() as $classDefinition) {
            if ($classDefinition instanceof ClassAnalyzerInterface) {
                $reflectionClass = new \ReflectionClass($classDefinition->getClassName());
                $classDefinition->analyze($reflectionClass);
            }
        }
    }
}
