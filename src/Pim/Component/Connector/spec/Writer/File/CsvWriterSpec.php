<?php

namespace spec\Pim\Component\Connector\Writer\File;

use Akeneo\Component\Batch\Job\JobParameters;
use Akeneo\Component\Batch\Model\StepExecution;
use Akeneo\Component\Buffer\BufferInterface;
use PhpSpec\ObjectBehavior;
use Pim\Component\Connector\Writer\File\ColumnSorterInterface;
use Pim\Component\Connector\Writer\File\FilePathResolverInterface;
use Pim\Component\Connector\Writer\File\FlatItemBuffer;
use Prophecy\Argument;

class CsvWriterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Pim\Component\Connector\Writer\File\CsvWriter');
    }

    function let(FilePathResolverInterface $filePathResolver, FlatItemBuffer $flatRowBuffer, ColumnSorterInterface $columnSorter)
    {
        $this->beConstructedWith($filePathResolver, $flatRowBuffer, $columnSorter);
        $filePathResolver->resolve(Argument::any(), Argument::type('array'))
            ->willReturn('/tmp/export/export.csv');
    }

    function it_is_a_configurable_step()
    {
        $this->shouldHaveType('Akeneo\Component\Batch\Item\AbstractConfigurableStepElement');
    }

    function it_is_a_writer()
    {
        $this->shouldImplement('Akeneo\Component\Batch\Item\ItemWriterInterface');
    }

    function it_prepares_the_export(
        $flatRowBuffer,
        StepExecution $stepExecution,
        JobParameters $jobParameters
    ) {
        $this->setStepExecution($stepExecution);
        $stepExecution->getJobParameters()->willReturn($jobParameters);
        $jobParameters->get('withHeader')->willReturn(true);

        $items = [
            [
                'id' => 123,
                'family' => 12,
            ],
            [
                'id' => 165,
                'family' => 45,
            ],
        ];

        $flatRowBuffer->write($items, true)->shouldBeCalled();

        $this->write($items);
    }

    function it_writes_the_csv_file(
        $flatRowBuffer,
        $columnSorter,
        BufferInterface $buffer,
        StepExecution $stepExecution,
        JobParameters $jobParameters
    ) {
        $this->setStepExecution($stepExecution);
        $stepExecution->getJobParameters()->willReturn($jobParameters);
        $jobParameters->get('delimiter')->willReturn(';');
        $jobParameters->get('enclosure')->willReturn('"');
        $jobParameters->get('withHeader')->willReturn(true);
        $jobParameters->get('filePath')->willReturn('my/file/path');
        $jobParameters->has('mainContext')->willReturn(false);

        $flatRowBuffer->getHeaders()->willReturn(['id', 'family']);
        $flatRowBuffer->getBuffer()->willReturn($buffer);

        $columnSorter->sort(['id', 'family'])->willReturn(['id', 'family']);

        $this->flush();
    }
}
