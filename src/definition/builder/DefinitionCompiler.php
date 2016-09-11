<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition\builder;

use samsonframework\container\ContainerInterface;
use samsonframework\container\definition\analyzer\DefinitionAnalyzer;
use samsonframework\container\definition\analyzer\exception\ParameterNotFoundException;
use samsonframework\container\definition\analyzer\exception\WrongAnalyzerTypeException;
use samsonframework\container\definition\exception\ClassDefinitionAlreadyExistsException;
use samsonframework\container\definition\builder\exception\ReferenceNotImplementsException;
use samsonframework\container\definition\reference\ClassReference;
use samsonframework\container\definition\reference\ReferenceInterface;
use samsonframework\di\Container;

/**
 * Class DefinitionCompiler
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class DefinitionCompiler
{
    /** @var DefinitionGenerator */
    protected $generator;

    /** @var DefinitionAnalyzer */
    protected $analyzer;

    /** @var array All registered dependencies*/
    protected $dependencies = [];

    /**
     * DefinitionCompiler constructor.
     *
     * @param DefinitionGenerator $generator
     * @param DefinitionAnalyzer $analyzer
     */
    public function __construct(DefinitionGenerator $generator, DefinitionAnalyzer $analyzer)
    {
        $this->generator = $generator;
        $this->analyzer = $analyzer;
    }

    /**
     * Compile and get container
     *
     * @param DefinitionBuilder $definitionBuilder
     * @param $containerName
     * @param $namespace
     * @param $containerDir
     * @return ContainerInterface
     * @throws WrongAnalyzerTypeException
     * @throws ParameterNotFoundException
     * @throws ClassDefinitionAlreadyExistsException
     * @throws ReferenceNotImplementsException
     * @throws \InvalidArgumentException
     */
    public function compile(DefinitionBuilder $definitionBuilder, $containerName, $namespace, $containerDir)
    {
        $this->generator->getClassGenerator()
            ->defName($containerName)
            ->defExtends('BaseContainer')
            ->defNamespace($namespace)
            ->defUse(Container::class, 'BaseContainer');

        // Max count analyzer iterations
        $count = 10;

        /**
         * Analyze builder metadata
         *
         * 1. Analyze definitions
         * 2. Add dependencies by analyzers
         * 3. Append missing definitions for dependencies
         * ... Analyze again
         */
        while ($this->analyzer->analyze($definitionBuilder)) {

            // Get dependencies
            $dependencies = $this->getClassDependencies($definitionBuilder);
            // Generate metadata
            $this->generateDefinitions($definitionBuilder, $dependencies);

            // Wrong behavior
            if ($count === 0) {
                throw new \InvalidArgumentException('Wrong analyze');
                break;
            }
            $count--;
        }

        // Get container code
        $code = $this->generator->generateClass($definitionBuilder);
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
     * Get class dependencies form definition builder
     *
     * @param DefinitionBuilder $definitionBuilder
     * @return array
     */
    protected function getClassDependencies(DefinitionBuilder $definitionBuilder): array
    {
        $dependencyList = [];
        // Get dependencies which will be used for generation definitions
        foreach ($definitionBuilder->getDefinitionCollection() as $classDefinition) {
            // When this class definition did not analyzed
            if (true || !$classDefinition->isAnalyzed()) {
                // Iterate properties and get their dependencies
                foreach ($classDefinition->getPropertiesCollection() as $propertyDefinition) {
                    // Add dependency to list if valid
                    $this->addDependency($dependencyList, $propertyDefinition->getDependency());
                }
                foreach ($classDefinition->getMethodsCollection() as $methodDefinition) {
                    foreach ($methodDefinition->getParametersCollection() as $parameterDefinition) {
                        $this->addDependency($dependencyList, $parameterDefinition->getDependency());
                    }
                }
            }
        }
        return $dependencyList;
    }

    /**
     * Generate definitions from dependencies for builder
     *
     * @param DefinitionBuilder $definitionBuilder
     * @param array $dependencyList
     * @throws ClassDefinitionAlreadyExistsException
     */
    protected function generateDefinitions(
        DefinitionBuilder $definitionBuilder,
        array $dependencyList
    ) {
        // Iterate all classes and auto generate definition for missing
        foreach ($dependencyList as $className => $classReference) {
            if (!$definitionBuilder->hasDefinition($className)) {
                $definitionBuilder->addDefinition($className);
            }
        }
    }

    /**
     * Add dependencies which then will be use for automatic creation the definitions
     *
     * @param array $dependencyList
     * @param ReferenceInterface $reference
     * @return array
     */
    protected function addDependency(array &$dependencyList, ReferenceInterface $reference)
    {
        // Add class dependency to list
        if ($reference instanceof ClassReference) {
            $dependencyList[$reference->getClassName()] = $reference;
        }
    }
}
