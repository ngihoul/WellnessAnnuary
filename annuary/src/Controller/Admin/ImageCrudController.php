<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageCrudController extends AbstractCrudController
{

    protected $container;
    protected $imageRepository;
    protected $entityManager;

    public function __construct(ParameterBagInterface $container, ImageRepository $imageRepository, EntityManagerInterface $entityManager) {
        $this->container = $container;
        $this->imageRepository = $imageRepository;
        $this->entityManager = $entityManager;
    }


    public static function getEntityFqcn(): string
    {
        return Image::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('Image du Slider')
            ->setEntityLabelInPlural('Images du Slider')
            ->setDefaultSort(['ordering' => 'ASC']);
    }

    public function createEntity(string $entityFqcn) {
        $newImage = new Image();
        $newImage->setType('slider');

        return $newImage;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters, ): QueryBuilder
    {
        parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        $response = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $response->andWhere('entity.type = :type')->setParameter('type', 'slider');

        return $response;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('fileName', 'Nom du fichier')
                ->setBasePath($this->getParameter('images_directory'))
                ->setUploadDir('/public/uploads/')
                ->setUploadedFileNamePattern(
                    fn (UploadedFile $file): string => sprintf('%s_%s.%s', $file->getFilename(), uniqid(), $file->guessExtension())
                ),
            IntegerField::new('ordering', 'Ordre'),
        ];
    }
}
