<?php

namespace App\Controller;

use App\Domain\Distributor\Command\Handler\ImportProductHandler;
use App\Domain\Distributor\Command\ImportProductCommand;
use App\Domain\Distributor\Form\ImportProductType;
use App\Domain\Preparation\Repository\PreparationUndefinedRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class DistributorController extends AbstractController
{
    /**
     * @Route("/distributor/import/", name="distributor_import")
     */
    public function import(
        Request $request,
        ImportProductHandler $importProductHandler,
        PreparationUndefinedRepository $preparationUndefinedRepository
    ): Response {
        $distributorName = $request->get('import_product')['distributorName'] ?? null;
        $file = $request->files->get('import_product')['file'] ?? null;

        if ($distributorName && $file) {
            $importProductCommand = new ImportProductCommand($distributorName, $file);

            $importProductTypeForm = $this->createForm(ImportProductType::class, $importProductCommand);
            $importProductTypeForm->handleRequest($request);

            if ($importProductTypeForm->isSubmitted() && $importProductTypeForm->isValid()) {
                $importProductHandler->handle($importProductCommand);
            }
        }

        $importProductTypeForm = $this->createForm(ImportProductType::class);

        return $this->render('distributor/index.html.twig', [
            'form' => $importProductTypeForm->createView(),
            'unknownBindings' => $preparationUndefinedRepository->findAll(),
        ]);
    }
}
