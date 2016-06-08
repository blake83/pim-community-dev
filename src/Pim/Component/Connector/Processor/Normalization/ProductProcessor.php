<?php


namespace Pim\Component\Connector\Processor\Normalization;


use Akeneo\Component\Batch\Item\AbstractConfigurableStepElement;
use Akeneo\Component\Batch\Item\InvalidItemException;
use Akeneo\Component\Batch\Item\ItemProcessorInterface;
use Akeneo\Component\Batch\Model\StepExecution;
use Akeneo\Component\Batch\Step\StepExecutionAwareInterface;
use Akeneo\Component\FileStorage\Exception\FileTransferException;
use Akeneo\Component\FileStorage\Model\FileInfoInterface;
use Akeneo\Component\FileStorage\Repository\FileInfoRepositoryInterface;
use Akeneo\Component\StorageUtils\Repository\CachedObjectRepositoryInterface;
use Pim\Bundle\CatalogBundle\Doctrine\MongoDBODM\ProductRepositoryInterface;
use Pim\Component\Catalog\Repository\AttributeRepositoryInterface;
use Pim\Component\Connector\Writer\File\FileExporter;

class ProductProcessor extends AbstractConfigurableStepElement implements ItemProcessorInterface, StepExecutionAwareInterface
{
    /** @var StepExecution */
    protected $stepExecution;

    /** @var CachedObjectRepositoryInterface */
    protected $attributeRepository;

    /** @var FileInfoRepositoryInterface */
    protected $fileInfoRepository;

    /** @var FileExporter */
    protected $fileExporter;

    /** @var array */
    protected $mediaAttributeTypes;

    public function __construct(CachedObjectRepositoryInterface $attributeRepository, FileInfoRepositoryInterface $fileInfoRepository, FileExporter $fileExporter, array $mediaAttributeTypes)
    {
        $this->attributeRepository = $attributeRepository;
        $this->fileInfoRepository = $fileInfoRepository;
        $this->fileExporter = $fileExporter;
        $this->mediaAttributeTypes = $mediaAttributeTypes;
    }

    public function process($item)
    {
        //TODO: the "key" of the media should be present in the pivot format of the fileinfo

        if (!$this->stepExecution->getJobParameters()->get('export_medias')) {
            return $item;
        }

        //TODO: we have to get the identifier of the item first
        $identifier = 'foooooo';

        foreach ($item['value'] as $attributeCode => $data) {
            $attribute = $this->attributeRepository->findOneByIdentifier($attributeCode);
            if (in_array($attribute->getAttributeType(), $this->mediaAttributeTypes)) {
                /** @var FileInfoInterface $fileInfo */
                $fileInfo = $this->fileInfoRepository->findOneByIdentifier($data['key']);
                if (null !== $fileInfo) {
                    $exportPath = $this->buildExportPath(
                        $fileInfo, $item['locale'], $item['scope'], $attributeCode, $identifier
                    );

                    try {
                        $this->fileExporter->export($fileInfo->getKey(), $exportPath, $fileInfo->getStorage());
                    } catch (FileTransferException $e) {
                        $exportPath = null;
                        $this->stepExecution->incrementSummaryInfo('media_skipped');
                    }

                    // important to have the "exportPath" in the writer
                    $item['value']['exportPath'] = null;
                }

                //TODO: detach $fileInfo
            }
        }

        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function setStepExecution(StepExecution $stepExecution)
    {
        $this->stepExecution = $stepExecution;
    }

    /**
     * @param FileInfoInterface $fileInfo
     * @param                   $localeCode
     * @param                   $scopeCode
     * @param                   $attributeCode
     * @param                   $identifier
     *
     * @return string
     */
    protected function buildExportPath(FileInfoInterface $fileInfo, $localeCode, $scopeCode, $attributeCode, $identifier)
    {
        $exportPath = sprintf('files/%s/%s', $identifier, $attributeCode);

        if (isset($localeCode)) {
            $exportPath .= DIRECTORY_SEPARATOR . $localeCode;
        }

        if (isset($scopeCode)) {
            $exportPath .= DIRECTORY_SEPARATOR . $scopeCode;
        }

        return $exportPath . DIRECTORY_SEPARATOR . $fileInfo->getOriginalFilename();
    }
}
