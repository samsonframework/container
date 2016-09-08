<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition;

/**
 * Class DefinitionAnalyzer
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class DefinitionAnalyzer
{
    /** @var  DefinitionBuilder */
    protected $builder;

    public function __construct(DefinitionBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Analyze builder
     */
    public function analyze()
    {
        $definitionCollection = $this->getBuilder()->getDefinitionCollection();

        // Analyze class definitions
        foreach ($definitionCollection as $classDefinition) {
            if ($classDefinition instanceof ClassAnalyzerInterface) {
                $reflectionClass = new \ReflectionClass($classDefinition->getClassName());
                $classDefinition->analyze($this, $reflectionClass);
            }
        }
    }

    /**
     * @return DefinitionBuilder
     */
    public function getBuilder(): DefinitionBuilder
    {
        return $this->builder;
    }
}
