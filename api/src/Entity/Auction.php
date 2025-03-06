<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

enum FeesType: string
{
  case Basic = 'basic';
  case Seller = 'seller';
  case Storage = 'storage';
  case Association = 'association';
}

class Auction
{
  public const POSSIBLE_VEHICLE_TYPES = ['common', 'luxury'];

  #[Assert\Choice(choices: Auction::POSSIBLE_VEHICLE_TYPES, message: "Must be a valid choice")]
  #[Assert\NotBlank(message: "Cannot be empty")]
  private $vehicleType;

  #[Assert\PositiveOrZero(message: "Cannot be negative")]
  #[Assert\NotBlank(message: "Cannot be empty")]
  private $vehicleValue;

  private $fees = [
    FeesType::Basic->name => 0,
    FeesType::Seller->name => 0,
    FeesType::Association->name => 0,
    FeesType::Storage->name => 0
  ];

  public function getTotalFees()
  {
    $total = 0;
    foreach ($this->fees as $_ => $fee) {
      $total += $fee;
    }
    return $total;
  }

  public function getFeesList()
  {
    return $this->fees;
  }

  public function getTotalVehicleValue()
  {
    return $this->vehicleValue + $this->getTotalFees();
  }

  public function getSpecificFee(FeesType $type)
  {
    if (!array_key_exists($type->name, $this->fees)) {
      return null;
    }
    return $this->fees[$type->name];
  }

  public function setSpecificFee(float $value, FeesType $type)
  {
    $this->fees[$type->name] = round($value, 2);

    return $this;
  }

  public function getVehicleType(): ?string
  {
    return $this->vehicleType;
  }

  public function setVehicleType(string $vehicleType): self
  {
    $this->vehicleType = $vehicleType;

    return $this;
  }

  public function getVehicleValue()
  {
    return $this->vehicleValue;
  }

  public function setVehicleValue(string $value): self
  {
    $this->vehicleValue = $value;

    return $this;
  }
}
