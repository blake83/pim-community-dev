<?php

namespace Context;

use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

/**
 * Context to be able to test the standard format
 *
 * @author    Julien Janvier <jjanvier@akeneo.com>
 * @copyright 2016 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class StandardFormatContext extends BehatContext
{
    /** @var array */
    private $results;

    /**
     * @Given /^I normalize the product "([^"]*)" with the "([^"]*)" format$/
     */
    public function iNormalizeTheProductWithTheFormat($identifier, $format)
    {
        $repository = $this->getContainer()->get('pim_catalog.repository.product');
        $product = $repository->findOneByIdentifier($identifier);

        $serializer = $this->getContainer()->get('pim_serializer');
        $this->results[$identifier] = $serializer->normalize($product, $format);
    }

    /**
     * TODO: we should not take into account the fields "created" and "updated" as the expected result can not be valid
     *
     * TODO: we should not take into account the media attributes as the expected result can not be valid
     * TODO: ("data" => "1/5/7/5/15757827125efa686c1c0f1e7930ca0c528f1c2c_imageA.jpg")
     *
     * @Then /^the standard format result of the product "([^"]*)" should be:$/
     */
    public function iShouldGetTheFollowingResult($identifier, PyStringNode $expected)
    {
        $this->assertDumpEquals(
            $expected->getRaw(),
            $this->results[$identifier],
            sprintf('The standard format results of the product "%s" is not valid.', $identifier)
        );
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        return $this->getMainContext()->getContainer();
    }

    /**
     * Copy/paste from Symfony
     * See {@link Symfony\Component\VarDumper\Test\VarDumperTestTrait}
     *
     * @param string $dump
     * @param string $data
     * @param string $message
     */
    private function assertDumpEquals($dump, $data, $message)
    {
        \PHPUnit_Framework_TestCase::assertSame($dump, $this->getVarDumperDump($data), $message);
    }

    /**
     * Copy/paste from Symfony
     * See {@link Symfony\Component\VarDumper\Test\VarDumperTestTrait}
     *
     * @param string $data
     *
     * @return string
     */
    private function getVarDumperDump($data)
    {
        $h = fopen('php://memory', 'r+b');
        $cloner = new VarCloner();
        $dumper = new CliDumper($h);
        $dumper->setColors(false);
        $dumper->dump($cloner->cloneVar($data)->withRefHandles(false));
        $data = stream_get_contents($h, -1, 0);
        fclose($h);

        return rtrim($data);
    }
}
