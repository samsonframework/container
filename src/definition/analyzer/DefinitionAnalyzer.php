<?php declare(strict_types=1);
/**
 * Created by Ruslan Molodyko.
 * Date: 10.09.2016
 * Time: 15:33
 */
namespace samsonframework\container\definition\analyzer;

use samsonframework\container\definition\analyzer\exception\ParameterNotFoundException;
use samsonframework\container\definition\builder\DefinitionBuilder;
use samsonframework\container\definition\ClassDefinition;

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
     * Add class analyzer
     *
     * @param ClassAnalyzerInterface $classAnalyzer
     * @return DefinitionAnalyzer
     */
    public function addClassAnalyzer(ClassAnalyzerInterface $classAnalyzer): DefinitionAnalyzer
    {
        $this->classAnalyzers[] = $classAnalyzer;

        return $this;
    }

    /**
     * Add method analyzer
     *
     * @param MethodAnalyzerInterface $methodAnalyzer
     * @return DefinitionAnalyzer
     */
    public function addMethodAnalyzer(MethodAnalyzerInterface $methodAnalyzer): DefinitionAnalyzer
    {
        $this->methodAnalyzers[] = $methodAnalyzer;

        return $this;
    }

    /**
     * Add property analyzer
     *
     * @param PropertyAnalyzerInterface $propertyAnalyzer
     * @return DefinitionAnalyzer
     */
    public function addPropertyAnalyzer(PropertyAnalyzerInterface $propertyAnalyzer): DefinitionAnalyzer
    {
        $this->propertyAnalyzers[] = $propertyAnalyzer;

        return $this;
    }

    /**
     * Add parameter analyzer
     *
     * @param ParameterAnalyzerInterface $parameterAnalyzer
     * @return DefinitionAnalyzer
     */
    public function addParameterAnalyzer(ParameterAnalyzerInterface $parameterAnalyzer): DefinitionAnalyzer
    {
        $this->parameterAnalyzers[] = $parameterAnalyzer;

        return $this;
    }

    /**
     * Analyze definition builder
     *
     * @param DefinitionBuilder $definitionBuilder
     * @throws ParameterNotFoundException
     * @return bool
     */
    public function analyze(DefinitionBuilder $definitionBuilder): bool
    {
        $isAnalyzed = false;
        // Analyze class definitions
        foreach ($definitionBuilder->getDefinitionCollection() as $classDefinition) {
            // Analyze only not analyzed classes
            if (!$classDefinition->isAnalyzed()) {
                // Get reflection
                $reflectionClass = new \ReflectionClass($classDefinition->getClassName());

                // Analyze class
                $this->analyzeClass($classDefinition, $reflectionClass);
                // Analyze properties
                $this->analyzeProperty($reflectionClass, $classDefinition);
                // Analyze methods
                $this->analyzeMethod($reflectionClass, $classDefinition);

                // Class was analyzed
                $classDefinition->setIsAnalyzed(true);

                // Do not analyze this definition
                $isAnalyzed = true;
            }
        }

        return $isAnalyzed;
    }

    /**
     * Analyze class
     *
     * @param ClassDefinition $classDefinition
     * @param \ReflectionClass $reflectionClass
     */
    protected function analyzeClass(ClassDefinition $classDefinition, \ReflectionClass $reflectionClass)
    {
        // Iterate analyzers
        foreach ($this->classAnalyzers as $classAnalyzer) {
            $classAnalyzer->analyze($this, $classDefinition, $reflectionClass);
        }
    }

    /**
     * Analyze method
     *
     * @param \ReflectionClass $reflectionClass
     * @param ClassDefinition $classDefinition
     * @throws ParameterNotFoundException
     */
    protected function analyzeMethod(\ReflectionClass $reflectionClass, ClassDefinition $classDefinition)
    {
        // Analyze method definitions
        foreach ($reflectionClass->getMethods() as $reflectionMethod) {
            foreach ($this->methodAnalyzers as $methodAnalyzer) {
                $methodAnalyzer->analyze($this, $classDefinition, $reflectionMethod);
            }
            $this->analyzeParameter($reflectionMethod, $classDefinition);
        }
    }

    /**
     * Analyze property
     *
     * @param \ReflectionClass $reflectionClass
     * @param ClassDefinition $classDefinition
     */
    protected function analyzeProperty(\ReflectionClass $reflectionClass, ClassDefinition $classDefinition)
    {
        // Iterate class properties
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            // Analyze property definition
            foreach ($this->propertyAnalyzers as $propertyAnalyzer) {
                $propertyAnalyzer->analyze($this, $classDefinition, $reflectionProperty);
            }
        }
    }

    /**
     * Analyze parameter
     *
     * @param \ReflectionMethod $reflectionMethod
     * @param ClassDefinition $classDefinition
     * @throws ParameterNotFoundException
     */
    protected function analyzeParameter(\ReflectionMethod $reflectionMethod, ClassDefinition $classDefinition) {
        // Get methods parameters
        foreach ($reflectionMethod->getParameters() as $reflectionParameter) {
            // Analyze parameters
            foreach ($this->parameterAnalyzers as $parameterAnalyzer) {
                $parameterAnalyzer->analyze($this, $classDefinition, $reflectionParameter);
            }
        }
    }
}
