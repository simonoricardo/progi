<?php

namespace App\Controller;

use App\Entity\Auction;
use App\Form\Type\AuctionType;
use App\Service\FeesCalculator;
use App\Utils\FormUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AuctionFeesController extends AbstractController
{
  public function calculate(Request $request, FeesCalculator $feesCalculator): JsonResponse
  {
    $data = json_decode($request->getContent(), true);

    $auction = new Auction();

    $form = $this->createForm(AuctionType::class, $auction);
    $form->submit($data);

    if (!$form->isValid()) {
      $errors = FormUtils::generateErrorsArrayFromForm($form);
      return $this->json(['errors' => $errors], status: 422);
    }

    if ($form->isSubmitted() && $form->isValid()) {
      $auction = $form->getData();
      $auction = $feesCalculator->calculateAllFees($auction);
      return $this->json(['auction' => $auction], status: 200);
    }

    return $this->json("An error has occured in the request", status: 400);
  }
}
