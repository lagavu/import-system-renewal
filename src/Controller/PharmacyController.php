<?php

namespace App\Controller;

use App\Domain\Pharmacy\Command\Handler\PharmacyStatisticHandler;
use App\Domain\Pharmacy\Command\PharmacyStatisticCommand;
use App\Domain\Pharmacy\Form\PharmacyStatisticType;
use App\Domain\Pharmacy\Repository\PharmacyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PharmacyController extends AbstractController
{
    /**
     * @Route("/pharmacy", name="pharmacy")
     */
    public function index(Request $request, PharmacyRepository $pharmacyRepository)
    {
        $pharmacyAddress = $request->get('pharmacy_statistic')['pharmacyAddress'] ?? null;

        $pharmacyStatisticTypeForm = $this->createForm(PharmacyStatisticType::class);

        if ($pharmacyAddress) {
            $pharmacy = $pharmacyRepository->get($pharmacyAddress);
            $preparations = $pharmacy->getPreparations()->getValues();

            return $this->render('pharmacy/index.html.twig', [
                'form' => $pharmacyStatisticTypeForm->createView(),
                'selectedPharmacy' => $pharmacy->getAddress(),
                'commonQuantity' => $pharmacy->getCommonQuantity($preparations),
                'preparations' => $preparations,
            ]);
        }

        return $this->render('pharmacy/index.html.twig', ['form' => $pharmacyStatisticTypeForm->createView()]);
    }
}
