<?php
/**
 * Created by Ruslan Molodyko.
 * Date: 10.09.2016
 * Time: 15:33
 */
namespace samsonframework\container\definition\analyzer;

use samsonframework\container\definition\analyzer\exception\ParameterNotFoundException;
use samsonframework\container\definition\builder\DefinitionBuilder;
use samsonframework\container\definition\analyzer\exception\WrongAnalyzerTypeException;
use samsonframework\container\definition\ClassDefinition;
use samsonframework\container\definition\MethodDefinition;
use samsonframework\container\definition\ParameterDefinition;

/**
 * Class DefinitionAnalyzer
 * Fill metadata(definition)
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class DefinitionAnalyzer
{
    /** ClassAnalyzerInterface[] */
    protected $classAnalyzers = [];
    /** MethodAnalyzerInterface[] */
    protected $methodAnalyzers = [];
    /** PropertyAnalyzerInterface[] */
    protected $propertyAnalyzers = [];
    /** ParameterAnalyzerInterface[] */
    protected $parameterAnalyzers = [];

    /**
     * DefinitionAnalyzer constructor.
     *
     * @param ClassAnalyzerInterface[] $classAnalyzers
     * @param MethodAnalyzerInterface[] $methodAnalyzers
     * @param PropertyAnalyzerInterface[] $propertyAnalyzers
     * @param ParameterAnalyzerInterface[] $parameterAnalyzers
     */
    public function __construct($classAnalyzers = [], $methodAnalyzers = [], $propertyAnalyzers = [], $parameterAnalyzers = []) {
        $this->classAnalyzers = $classAnalyzers;
        $this->methodAnalyzers = $methodAnalyzers;
        $this->propertyAnalyzers = $propertyAnalyzers;
        $this->parameterAnalyzers = $parameterAnalyzers;
    }

    /**
     * Analyze definition builder
     *
     * @param DefinitionBuilder $definitionBuilder
     * @throws ParameterNotFoundException
     * @throws WrongAnalyzerTypeException
     * @return bool
     */
    public function analyze(DefinitionBuilder $definitionBuilder): bool
    {
        $isAnalyzed = false;
        // Analyze class definitions
        foreach ($definitionBuilder->getDefinitionCollection() as $classDefinition) {
            // Analyze only not analyzed classes
            if (!$classDefinition->isAnalyzed()) {
                // Analyze class
                $this->analyzeClass($classDefinition);
                // Class was analyzed
                $classDefinition->setIsAnalyzed(true);
                $isAnalyzed = true;
            }
        }

        return $isAnalyzed;
    }

    /**
     * Analyze class
     *
     * @param ClassDefinition $classDefinition
     * @throws WrongAnalyzerTypeException
     * @throws ParameterNotFoundException
     */
    protected function analyzeClass(ClassDefinition $classDefinition)
    {
        // Get reflection
        $reflectionClass = new \ReflectionClass($classDefinition->getClassName());

        // Iterate analyzers
        foreach ($this->classAnalyzers as $classAnalyzer) {
            if ($classAnalyzer instanceof ClassAnalyzerInterface) {
                $classAnalyzer->analyze($this, $reflectionClass, $classDefinition);
            } else {
                throw new WrongAnalyzerTypeException(sprintf(
                    'Analyzer "%s" should implements ClassAnalyzerInterface',
                    get_class($classAnalyzer)
                ));
            }
        }

        // Analyze properties
        $this->analyzeProperty($reflectionClass, $classDefinition);
        // Analyze methods
        $this->analyzeMethod($reflectionClass, $classDefinition);
    }

    /**
     * Analyze method
     *
     * @param \ReflectionClass $reflectionClass
     * @param ClassDefinition $classDefinition
     * @throws ParameterNotFoundException
     * @throws WrongAnalyzerTypeException
     */
    protected function analyzeMethod(\ReflectionClass $reflectionClass, ClassDefinition $classDefinition)
    {
        // Analyze method definitions
        foreach ($reflectionClass->getMethods() as $reflectionMethod) {
            $methodDefinition = $classDefinition->getMethodsCollection()[$reflectionMethod->getName()] ?? null;
            foreach ($this->methodAnalyzers as $methodAnalyzer) {
                if ($methodAnalyzer instanceof MethodAnalyzerInterface) {
                    $methodAnalyzer->analyze($this, $reflectionMethod, $methodDefinition);
                } else {
                    throw new WrongAnalyzerTypeException(sprintf(
                        'Analyzer "%s" should implements MethodAnalyzerInterface',
                        get_class($methodAnalyzer)
                    ));
                }
            }
            $this->analyzeParameter($reflectionMethod, $methodDefinition);
        }
    }

    /**
     * Analyze property
     *
     * @param \ReflectionClass $reflectionClass
     * @param ClassDefinition $classDefinition
     * @throws WrongAnalyzerTypeException
     */
    protected function analyzeProperty(\ReflectionClass $reflectionClass, ClassDefinition $classDefinition)
    {
        // Analyze property definition
        foreach ($classDefinition->getPropertiesCollection() as $propertyDefinition) {
            foreach ($this->propertyAnalyzers as $propertyAnalyzer) {
                if ($propertyAnalyzer instanceof PropertyAnalyzerInterface) {
                    $reflectionProperty = $reflectionClass->getProperty($propertyDefinition->getPropertyName());
                    $propertyAnalyzer->analyze($this, $reflectionProperty, $propertyDefinition);
                } else {
                    throw new WrongAnalyzerTypeException(sprintf(
                        'Analyzer "%s" should implements PropertyAnalyzerInterface',
                        get_class($propertyAnalyzer)
                    ));
                }
            }
        }
    }

    /**
     * Analyze parameter
     *
     * @param \ReflectionMethod $reflectionMethod
     * @param MethodDefinition $methodDefinition
     * @throws ParameterNotFoundException
     * @throws WrongAnalyzerTypeException
     */
    protected function analyzeParameter(\ReflectionMethod $reflectionMethod, MethodDefinition $methodDefinition = null)
    {
        // Get methods parameters
        foreach ($reflectionMethod->getParameters() as $reflectionParameter) {
            $parameterDefinition = null;
            if ($methodDefinition) {
                // Check if parameter exists in method
                if (!array_key_exists($reflectionParameter->getName(), $methodDefinition->getParametersCollection())) {
                    throw new ParameterNotFoundException();
                } else {
                    /** @var ParameterDefinition $parameterDefinition */
                    $parameterDefinition = $methodDefinition->getParametersCollection()[$reflectionParameter->getName()];
                }
            }
            // Analyze parameters
            foreach ($this->parameterAnalyzers as $parameterAnalyzer) {
                if ($parameterAnalyzer instanceof ParameterAnalyzerInterface) {
                    $parameterAnalyzer->analyze($this, $reflectionParameter, $parameterDefinition);
                } else {
                    throw new WrongAnalyzerTypeException(sprintf(
                        'Analyzer "%s" should implements ParameterAnalyzerInterface',
                        get_class($parameterAnalyzer)
                    ));
                }
            }
        }
    }
}
