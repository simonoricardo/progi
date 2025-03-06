<?php

namespace App\Service;

use App\Entity\Auction;
use App\Entity\FeesType;
use Exception;

class FeesCalculator
{
  private const BASIC_FEE = ['amount' => 10, 'type' => 'percent'];
  private const FIXED_STORAGE_FEE = ['amount' => 100, 'type' => 'int'];

  private const BASIC_BUYER_FEE_CONFIG = [
    'common' =>
    [
      'value' => FeesCalculator::BASIC_FEE,
      'min' => ['amount' => 10, 'type' => 'int'],
      'max' => ['amount' => 50, 'type' => 'int']
    ],
    'luxury' =>
    [
      'value' => FeesCalculator::BASIC_FEE,
      'min' => ['amount' => 25, 'type' => 'int'],
      'max' => ['amount' => 200, 'type' => 'int']
    ],
  ];

  private const SELLER_SPECIAL_FEE_CONFIG =
  [
    'common' => ['value' => ['amount' => 2, 'type' => 'percent']],
    'luxury' => ['value' => ['amount' => 4, 'type' => 'percent']],
  ];

  private const FIXED_STORAGE_FEE_CONFIG =
  [
    'all' => ['value' => FeesCalculator::FIXED_STORAGE_FEE],
  ];

  public function getClampedValue($vehicleValue, $config)
  {
    ['amount' => $feeAmount, 'type' => $type] = $config['value'];

    $min = isset($config['min']) ? $config['min'] : null;
    $max = isset($config['max']) ? $config['max'] : null;

    $calculatedFee = $type === 'percent' ? $feeAmount * $vehicleValue / 100 : $feeAmount;

    if (is_null($max) && is_null($min)) {
      return $calculatedFee;
    }

    if (isset($min['amount']) && isset($max['amount']) && $min['amount'] > $max['amount']) {
      throw new Exception('Error in configuration, min fee is greater than max fee');
    }

    $minFee = null;
    $maxFee = null;

    if (!is_null($min)) {
      $minFee = $min['type'] === 'percent' ? $vehicleValue  * $min['amount'] / 100 : $min['amount'];
    }

    if (!is_null($max)) {
      $maxFee = $max['type'] === 'percent' ? $vehicleValue  * $max['amount'] / 100 : $max['amount'];
    }

    if (!is_null($minFee) && $calculatedFee <= $minFee) {
      return $minFee;
    }

    if (!is_null($maxFee) && $calculatedFee >= $maxFee) {
      return $maxFee;
    }

    return $calculatedFee;
  }

  private function getAssociationFees(Auction $auction)
  {
    $price = $auction->getVehicleValue();

    return match (true) {
      ($price > 1) && ($price <= 500) => 5,
      ($price > 500) && ($price <= 1000) => 10,
      ($price > 1000) && ($price <= 3000) => 15,
      default => 20
    };
  }

  public function calculateFee(Auction $auction, FeesType $feeType): float
  {
    if ($feeType === FeesType::Association) {
      return $this->getAssociationFees($auction);
    }

    $matchingConfiguration = match ($feeType) {
      FeesType::Basic => FeesCalculator::BASIC_BUYER_FEE_CONFIG,
      FeesType::Seller => FeesCalculator::SELLER_SPECIAL_FEE_CONFIG,
      FeesType::Storage => FeesCalculator::FIXED_STORAGE_FEE_CONFIG,
    };

    foreach ($matchingConfiguration as $configVehicleType => $config) {
      if ($configVehicleType === 'all') {
        return $this->getClampedValue($auction->getVehicleValue(), $config);
      }
      if ($configVehicleType === $auction->getVehicleType()) {
        return $this->getClampedValue($auction->getVehicleValue(), $config);
      }
    }
  }

  public function getFees(Auction $auction)
  {
    return [
      FeesType::Basic->value => $this->calculateFee($auction, Feestype::Basic),
      FeesType::Seller->value => $this->calculateFee($auction, Feestype::Seller),
      FeesType::Storage->value => $this->calculateFee($auction, Feestype::Storage),
      FeesType::Association->value => $this->getAssociationFees($auction)
    ];
  }


  public function calculateAllFees(Auction &$auction)
  {
    $fees = $this->getFees($auction);
    foreach ($fees as $feeType => $value) {
      $auction->setSpecificFee($value, FeesType::from($feeType));
    }
    return $auction;
  }
}
