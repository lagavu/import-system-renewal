<?php

namespace App\Domain\Distributor\Form;

use App\Domain\Distributor\Command\ImportProductCommand;
use App\Domain\Distributor\Repository\DistributorRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImportProductType extends AbstractType
{
    private $distributorRepository;

    public function __construct(DistributorRepository $distributorRepository)
    {
        $this->distributorRepository = $distributorRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('distributorName', ChoiceType::class, [
                'label' => 'Выберите дистрибьютора',
                'choices' => $this->getDistributorsNameList(),
                'attr' => [
                    'class' => 'form-control-file',
                ],
            ])
            ->add('file', FileType::class, [
                'label' => 'Добавьте файл для загрузки',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control-file',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => ImportProductCommand::class,
        ));
    }

    /**
     * @return string[]
     */
    private function getDistributorsNameList(): array
    {
        $distributors = $this->distributorRepository->findAll();

        $distributorNameList = [];

        foreach ($distributors as $distributor) {
            $distributorNameList[$distributor->getName()] = $distributor->getName();
        }

        return $distributorNameList;
    }
}
