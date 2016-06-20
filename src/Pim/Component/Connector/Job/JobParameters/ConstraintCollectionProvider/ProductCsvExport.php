<?php

namespace Pim\Component\Connector\Job\JobParameters\ConstraintCollectionProvider;

use Akeneo\Component\Batch\Job\JobInterface;
use Akeneo\Component\Batch\Job\JobParameters\ConstraintCollectionProviderInterface;
use Pim\Bundle\BaseConnectorBundle\Validator\Constraints\Channel;
use Pim\Component\Catalog\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Constraints for product CSV export
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2016 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ProductCsvExport implements ConstraintCollectionProviderInterface
{
    /** @var ConstraintCollectionProviderInterface */
    protected $simpleProvider;

    /** @var array */
    protected $supportedJobNames;

    /**
     * @param ConstraintCollectionProviderInterface $simpleCsv
     * @param array                                 $supportedJobNames
     */
    public function __construct(ConstraintCollectionProviderInterface $simpleCsv, array $supportedJobNames)
    {
        $this->simpleProvider = $simpleCsv;
        $this->supportedJobNames = $supportedJobNames;
    }

    /**
     * {@inheritdoc}
     */
    public function getConstraintCollection()
    {
        $baseConstraint = $this->simpleProvider->getConstraintCollection();
        $constraintFields = $baseConstraint->fields;
        $constraintFields['decimalSeparator'] = new NotBlank();
        $constraintFields['dateFormat'] = new NotBlank();
//        $constraintFields['channel'] = [
//            new NotBlank(['groups' => 'Execution']),
//            new Channel()
//        ];
//        $constraintFields['enabled'] = new NotBlank(['groups' => 'Execution']);
//        $constraintFields['updated_since_strategy'] = [
//            new NotBlank(['groups' => 'Execution']),
//            new Choice(['choices' => [
//                    'all',
//                    'last_export',
//                    'since_date',
//                    'since_n_days',
//                ]
//            ])
//        ];
//        $constraintFields['updated_since_date'] = new DateTime(['groups' => 'Execution']);
//        $constraintFields['updated_since_n_days'] = new Range(['min' => 0, 'groups' => 'Execution']);
//        $constraintFields['locales'] = new NotBlank([
//            'groups'  => 'Execution',
//            'message' => 'pim_connector.export.locales.validation.not_blank'
//        ]);
//        $constraintFields['families'] = [];
//        $constraintFields['completeness'] = [
//            new NotBlank(['groups' => 'Execution']),
//            new Choice(['choices' => [
//                'at_least_one_complete',
//                'all_complete',
//                'all_incomplete',
//                'all',
//            ], 'groups' => 'Execution'])
//        ];

        return new Collection(['fields' => $constraintFields]);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(JobInterface $job)
    {
        return in_array($job->getName(), $this->supportedJobNames);
    }
}
