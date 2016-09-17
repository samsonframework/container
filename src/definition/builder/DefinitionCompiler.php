<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition\builder;

use samsonframework\container\ContainerInterface;
use samsonframework\container\definition\AbstractPropertyDefinition;
use samsonframework\container\definition\analyzer\DefinitionAnalyzer;
use samsonframework\container\definition\analyzer\exception\ParameterNotFoundException;
use samsonframework\container\definition\builder\exception\ImplementerForTypeNotFoundException;
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
     * @throws ImplementerForTypeNotFoundException
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
                    $this->addDependency($dependencyList, $propertyDefinition->getDependency(), $propertyDefinition);
                }
                foreach ($classDefinition->getMethodsCollection() as $methodDefinition) {
                    foreach ($methodDefinition->getParametersCollection() as $parameterDefinition) {
                        $this->addDependency($dependencyList, $parameterDefinition->getDependency(), $parameterDefinition);
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
     * @throws ImplementerForTypeNotFoundException
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
     * Check if class name is interface or abstract class
     *
     * @param string $className
     * @return bool
     */
    protected function checkIfType(string $className): bool
    {
        $reflectionClass = new \ReflectionClass($className);
        return $reflectionClass->isInterface() || $reflectionClass->isAbstract();
    }

    /**
     * Get class which implements the interface
     *
     * @param string $interfaceName
     * @return string
     * @throws ImplementerForTypeNotFoundException
     * TODO Add interface resolvers functionality
     */
    protected function resolveTypeImplementer(string $interfaceName): string
    {
        // Gather all interface implementations
        foreach (get_declared_classes() as $class) {
            $classImplements = class_implements($class);
            // Remove slash for start of interface
            if (in_array(ltrim($interfaceName, '\\'), $classImplements, true)) {
                return $class;
            }
        }
        throw new ImplementerForTypeNotFoundException(
            sprintf('Type "%s" does not have some implementers', $interfaceName)
        );
    }

    /**
     * Add dependencies which then will be use for automatic creation the definitions
     *
     * @param array $dependencyList
     * @param ReferenceInterface $reference
     * @param AbstractPropertyDefinition $definition
     * @return array
     * @throws ImplementerForTypeNotFoundException
     */
    protected function addDependency(
        array &$dependencyList,
        ReferenceInterface $reference,
        AbstractPropertyDefinition $definition
    ) {
        // Add class dependency to list
        if ($reference instanceof ClassReference) {
            $className = $reference->getClassName();
            // When there is not simple class then resolve it by type
            if ($this->checkIfType($className)) {
                $className = $this->resolveTypeImplementer($className);
                $reference = new ClassReference($className);
                // Set new implementer dependency instead of type one
                $definition->setDependency($reference);
            }
            $dependencyList[$className] = $reference;
        }
    }
}
