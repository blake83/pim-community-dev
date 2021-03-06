<?php

namespace spec\Pim\Bundle\BaseConnectorBundle\Reader\Doctrine;

use Akeneo\Component\Batch\Job\JobParameters;
use Akeneo\Component\Batch\Model\StepExecution;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\From;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Pim\Bundle\CatalogBundle\Doctrine\ORM\Repository\ProductRepository;
use Pim\Component\Catalog\Manager\CompletenessManager;
use Pim\Component\Catalog\Model\ChannelInterface;
use Pim\Component\Catalog\Model\ProductInterface;
use Pim\Component\Catalog\Converter\MetricConverter;
use Pim\Component\Catalog\Repository\ChannelRepositoryInterface;

class ORMProductReaderSpec extends ObjectBehavior
{
    function let(
        ProductRepository $repository,
        ChannelRepositoryInterface $channelRepository,
        CompletenessManager $completenessManager,
        MetricConverter $metricConverter,
        StepExecution $stepExecution,
        EntityManager $entityManager
    ) {
        $this->beConstructedWith(
            $repository,
            $channelRepository,
            $completenessManager,
            $metricConverter,
            $entityManager
        );

        $this->setStepExecution($stepExecution);
    }

    function it_reads_products_one_by_one(
        $channelRepository,
        $repository,
        $stepExecution,
        ChannelInterface $channel,
        From $from,
        QueryBuilder $queryBuilder,
        AbstractQuery $query,
        ProductInterface $sku1,
        ProductInterface $sku2,
        ProductInterface $sku3,
        JobParameters $jobParameters
    ) {
        $stepExecution->getJobParameters()->willReturn($jobParameters);
        $jobParameters->get('channel')->willReturn('foobar');

        $channelRepository->findOneByIdentifier('foobar')->willReturn($channel);
        $repository->buildByChannelAndCompleteness($channel)->willReturn($queryBuilder);
        $queryBuilder->getRootAliases()->willReturn(['root']);
        $queryBuilder->getDQLPart('from')->willReturn([$from]);
        $queryBuilder->select('root.id')->willReturn($queryBuilder);
        $queryBuilder->resetDQLPart('from')->willReturn($queryBuilder);
        $from->getFrom()->willReturn('from_table');
        $from->getAlias()->willReturn('alias_table');
        $queryBuilder->from('from_table', 'alias_table', 'root.id')->willReturn($queryBuilder);
        $queryBuilder->groupBy('root.id')->willReturn($queryBuilder);

        $queryBuilder->getQuery()->willReturn($query);

        $query->getArrayResult()->willReturn(array_flip([1, 33, 789]));

        $repository->findByIds([1, 33, 789])->willReturn([$sku1, $sku2, $sku3]);

        $stepExecution->incrementSummaryInfo('read')->shouldBeCalled();

        $this->read()->shouldReturn($sku1);
    }

    function it_generates_channel_completenesses_first_time_it_reads(
        $channelRepository,
        $completenessManager,
        $repository,
        $stepExecution,
        From $from,
        ChannelInterface $channel,
        QueryBuilder $queryBuilder,
        AbstractQuery $query,
        ProductInterface $sku1,
        ProductInterface $sku2,
        ProductInterface $sku3,
        JobParameters $jobParameters
    ) {
        $stepExecution->getJobParameters()->willReturn($jobParameters);
        $jobParameters->get('channel')->willReturn('foobar');

        $channelRepository->findOneByIdentifier('foobar')->willReturn($channel);
        $repository->buildByChannelAndCompleteness($channel)->willReturn($queryBuilder);
        $queryBuilder->getRootAliases()->willReturn(['root']);
        $queryBuilder->getDQLPart('from')->willReturn([$from]);
        $queryBuilder->select('root.id')->willReturn($queryBuilder);
        $queryBuilder->resetDQLPart('from')->willReturn($queryBuilder);
        $from->getFrom()->willReturn('from_table');
        $from->getAlias()->willReturn('alias_table');
        $queryBuilder->from('from_table', 'alias_table', 'root.id')->willReturn($queryBuilder);
        $queryBuilder->groupBy('root.id')->willReturn($queryBuilder);

        $queryBuilder->getQuery()->willReturn($query);

        $query->getArrayResult()->willReturn(array_flip([1, 33, 789]));

        $repository->findByIds([1, 33, 789])->willReturn([$sku1, $sku2, $sku3]);

        $completenessManager->generateMissingForChannel($channel)->shouldBeCalledTimes(1);

        $stepExecution->incrementSummaryInfo('read')->shouldBeCalled();

        $this->read()->shouldReturn($sku1);
    }

    function it_converts_metric_values(
        $channelRepository,
        $repository,
        $metricConverter,
        $stepExecution,
        ChannelInterface $channel,
        From $from,
        QueryBuilder $queryBuilder,
        AbstractQuery $query,
        ProductInterface $sku1,
        ProductInterface $sku2,
        ProductInterface $sku3,
        JobParameters $jobParameters
    ) {
        $stepExecution->getJobParameters()->willReturn($jobParameters);
        $jobParameters->get('channel')->willReturn('foobar');

        $channelRepository->findOneByIdentifier('foobar')->willReturn($channel);
        $repository->buildByChannelAndCompleteness($channel)->willReturn($queryBuilder);
        $queryBuilder->getRootAliases()->willReturn(['root']);
        $queryBuilder->getDQLPart('from')->willReturn([$from]);
        $queryBuilder->select('root.id')->willReturn($queryBuilder);
        $queryBuilder->resetDQLPart('from')->willReturn($queryBuilder);
        $from->getFrom()->willReturn('from_table');
        $from->getAlias()->willReturn('alias_table');
        $queryBuilder->from('from_table', 'alias_table', 'root.id')->willReturn($queryBuilder);
        $queryBuilder->groupBy('root.id')->willReturn($queryBuilder);

        $queryBuilder->getQuery()->willReturn($query);

        $query->getArrayResult()->willReturn(array_flip([1, 33, 789]));

        $repository->findByIds([1, 33, 789])->willReturn([$sku1, $sku2, $sku3]);

        $metricConverter->convert($sku1, $channel)->shouldBeCalled();

        $stepExecution->incrementSummaryInfo('read')->shouldBeCalled();

        $this->read()->shouldReturn($sku1);
    }

    function it_increments_read_count_each_time_it_reads(
        $channelRepository,
        $repository,
        $stepExecution,
        ChannelInterface $channel,
        From $from,
        QueryBuilder $queryBuilder,
        AbstractQuery $query,
        ProductInterface $sku1,
        ProductInterface $sku2,
        ProductInterface $sku3,
        JobParameters $jobParameters
    ) {
        $stepExecution->getJobParameters()->willReturn($jobParameters);
        $jobParameters->get('channel')->willReturn('foobar');

        $channelRepository->findOneByIdentifier('foobar')->willReturn($channel);
        $repository->buildByChannelAndCompleteness($channel)->willReturn($queryBuilder);
        $queryBuilder->getRootAliases()->willReturn(['root']);
        $queryBuilder->getDQLPart('from')->willReturn([$from]);
        $queryBuilder->select('root.id')->willReturn($queryBuilder);
        $queryBuilder->resetDQLPart('from')->willReturn($queryBuilder);
        $from->getFrom()->willReturn('from_table');
        $from->getAlias()->willReturn('alias_table');
        $queryBuilder->from('from_table', 'alias_table', 'root.id')->willReturn($queryBuilder);
        $queryBuilder->groupBy('root.id')->willReturn($queryBuilder);

        $queryBuilder->getQuery()->willReturn($query);

        $query->getArrayResult()->willReturn(array_flip([1, 33, 789]));

        $repository->findByIds([1, 33, 789])->willReturn([$sku1, $sku2, $sku3]);

        $stepExecution->incrementSummaryInfo('read')->shouldBeCalledTimes(3);

        $this->read();
        $this->read();
        $this->read();
        $this->read();
    }
}
