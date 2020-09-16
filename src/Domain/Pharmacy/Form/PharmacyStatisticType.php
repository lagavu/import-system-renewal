<?php

namespace App\Domain\Pharmacy\Form;

use App\Domain\Pharmacy\Repository\PharmacyRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class PharmacyStatisticType extends AbstractType
{
    private $pharmacyRepository;

    public function __construct(PharmacyRepository $pharmacyRepository)
    {
        $this->pharmacyRepository = $pharmacyRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pharmacyAddress', ChoiceType::class, [
                'label' => 'Выберите аптеку',
                'choices' => $this->getPharmaciesAddressesList(),
                'attr' => [
                    'class' => 'form-control-file',
                ],
            ]);
    }

    /**
     * @return string[]
     */
    private function getPharmaciesAddressesList(): array
    {
        $pharmacies = $this->pharmacyRepository->findAll();

        $pharmacyAddressesList = [];

        foreach ($pharmacies as $pharmacy) {
            $pharmacyAddressesList[$pharmacy->getAddress()] = $pharmacy->getAddress();
        }

        return $pharmacyAddressesList;
    }
}
