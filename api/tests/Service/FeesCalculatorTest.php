<?php

use PHPUnit\Framework\TestCase;
use App\Service\FeesCalculator;
use App\Entity\Auction;
use App\Entity\FeesType;
use PHPUnit\Framework\MockObject\MockObject;

class FeesCalculatorTest extends TestCase
{
  private FeesCalculator $feesCalculator;

  protected function setUp(): void
  {
    $this->feesCalculator = new FeesCalculator();
  }

  public function testGetClampedValue(): void
  {
    # without min/max
    $config = ['value' => ['amount' => 10, 'type' => 'percent']];
    $vehicleValue = 100;
    $expected = 10; // 10% of 100

    $result = $this->feesCalculator->getClampedValue($vehicleValue, $config);

    $this->assertEquals($expected, $result);

    # over min
    $config = [
      'value' => ['amount' => 20, 'type' => 'percent'],
      'min' => ['amount' => 10, 'type' => 'int']
    ];

    $vehicleValue = 100;
    $expected = 20;

    $result = $this->feesCalculator->getClampedValue($vehicleValue, $config);

    $this->assertEquals($expected, $result);

    # below min
    $config = [
      'value' => ['amount' => 20, 'type' => 'percent'],
      'min' => ['amount' => 21, 'type' => 'int']
    ];
    $vehicleValue = 100;
    $expected = 21;

    $result = $this->feesCalculator->getClampedValue($vehicleValue, $config);

    $this->assertEquals($expected, $result);

    # below max
    $config = [
      'value' => ['amount' => 20, 'type' => 'percent'],
      'max' => ['amount' => 30, 'type' => 'int']
    ];
    $vehicleValue = 100;
    $expected = 20;

    $result = $this->feesCalculator->getClampedValue($vehicleValue, $config);

    $this->assertEquals($expected, $result);

    # over max
    $config = [
      'value' => ['amount' => 20, 'type' => 'percent'],
      'max' => ['amount' => 15, 'type' => 'int']
    ];
    $vehicleValue = 100;
    $expected = 15;

    $result = $this->feesCalculator->getClampedValue($vehicleValue, $config);

    $this->assertEquals($expected, $result);

    # invalid configuration
    $config = [
      'value' => ['amount' => 33, 'type' => 'percent'],
      'min' => ['amount' => 21, 'type' => 'int'],
      'max' => ['amount' => 15, 'type' => 'int']
    ];
    $vehicleValue = 100;

    $this->expectException(Exception::class);
    $this->feesCalculator->getClampedValue($vehicleValue, $config);
  }

  public function testCalculateFeeBasic(): void
  {
    $auction = $this->createMock(Auction::class);
    $auction->method('getVehicleValue')->willReturn(1000);
    $auction->method('getVehicleType')->willReturn('luxury');
    $result = $this->feesCalculator->calculateFee($auction, FeesType::Basic);
    $expected = 100;

    $this->assertEquals($expected, $result);

    $auction = $this->createMock(Auction::class);
    $auction->method('getVehicleValue')->willReturn(2636);
    $auction->method('getVehicleType')->willReturn('luxury');
    $result = $this->feesCalculator->calculateFee($auction, FeesType::Basic);
    $expected = 200;

    $this->assertEquals($expected, $result);

    $auction = $this->createMock(Auction::class);
    $auction->method('getVehicleValue')->willReturn(0);
    $auction->method('getVehicleType')->willReturn('common');
    $result = $this->feesCalculator->calculateFee($auction, FeesType::Basic);
    $expected = 10;

    $this->assertEquals($expected, $result);
  }

  public function testCalculateFeeSeller(): void
  {
    $auction = $this->createMock(Auction::class);
    $auction->method('getVehicleValue')->willReturn(1500);
    $auction->method('getVehicleType')->willReturn('luxury');
    $result = $this->feesCalculator->calculateFee($auction, FeesType::Seller);
    $expected = 60;

    $this->assertEquals($expected, $result);

    $auction = $this->createMock(Auction::class);
    $auction->method('getVehicleValue')->willReturn(1500);
    $auction->method('getVehicleType')->willReturn('common');
    $result = $this->feesCalculator->calculateFee($auction, FeesType::Seller);
    $expected = 30;

    $this->assertEquals($expected, $result);

    $auction = $this->createMock(Auction::class);
    $auction->method('getVehicleValue')->willReturn(0);
    $auction->method('getVehicleType')->willReturn('common');
    $result = $this->feesCalculator->calculateFee($auction, FeesType::Seller);
    $expected = 0;

    $this->assertEquals($expected, $result);
  }

  public function testCalculateAssociationFees(): void
  {
    $auction = $this->createMock(Auction::class);
    $auction->method('getVehicleValue')->willReturn(1500);
    $auction->method('getVehicleType')->willReturn('common');
    $result = $this->feesCalculator->calculateFee($auction, FeesType::Association);
    $expected = 15;

    $this->assertEquals($expected, $result);

    $auction = $this->createMock(Auction::class);
    $auction->method('getVehicleValue')->willReturn(1000);
    $auction->method('getVehicleType')->willReturn('common');
    $result = $this->feesCalculator->calculateFee($auction, FeesType::Association);
    $expected = 10;

    $this->assertEquals($expected, $result);

    $auction = $this->createMock(Auction::class);
    $auction->method('getVehicleValue')->willReturn(999);
    $auction->method('getVehicleType')->willReturn('common');
    $result = $this->feesCalculator->calculateFee($auction, FeesType::Association);
    $expected = 10;

    $this->assertEquals($expected, $result);

    $auction = $this->createMock(Auction::class);
    $auction->method('getVehicleValue')->willReturn(1001);
    $auction->method('getVehicleType')->willReturn('common');
    $result = $this->feesCalculator->calculateFee($auction, FeesType::Association);
    $expected = 15;

    $this->assertEquals($expected, $result);

    $auction = $this->createMock(Auction::class);
    $auction->method('getVehicleValue')->willReturn(5000);
    $auction->method('getVehicleType')->willReturn('common');
    $result = $this->feesCalculator->calculateFee($auction, FeesType::Association);
    $expected = 20;

    $this->assertEquals($expected, $result);
  }

  public function testCalculateStorageFees(): void
  {
    $expected = 100;

    $auction = $this->createMock(Auction::class);
    $auction->method('getVehicleValue')->willReturn(1500);
    $auction->method('getVehicleType')->willReturn('common');
    $result = $this->feesCalculator->calculateFee($auction, FeesType::Storage);

    $this->assertEquals($expected, $result);

    $auction = $this->createMock(Auction::class);
    $auction->method('getVehicleValue')->willReturn(1000);
    $auction->method('getVehicleType')->willReturn('common');
    $result = $this->feesCalculator->calculateFee($auction, FeesType::Storage);

    $this->assertEquals($expected, $result);

    $auction = $this->createMock(Auction::class);
    $auction->method('getVehicleValue')->willReturn(999);
    $auction->method('getVehicleType')->willReturn('common');
    $result = $this->feesCalculator->calculateFee($auction, FeesType::Storage);

    $this->assertEquals($expected, $result);

    $auction = $this->createMock(Auction::class);
    $auction->method('getVehicleValue')->willReturn(1001);
    $auction->method('getVehicleType')->willReturn('common');
    $result = $this->feesCalculator->calculateFee($auction, FeesType::Storage);

    $this->assertEquals($expected, $result);

    $auction = $this->createMock(Auction::class);
    $auction->method('getVehicleValue')->willReturn(0);
    $auction->method('getVehicleType')->willReturn('common');
    $result = $this->feesCalculator->calculateFee($auction, FeesType::Storage);

    $this->assertEquals($expected, $result);
  }

  public function testTotalFees(): void
  {
    $auction = new Auction();
    $auction->setVehicleType('common');
    $auction->setVehicleValue(398);
    $this->feesCalculator->calculateAllFees($auction);
    $result = $auction->getTotalFees();
    $expected = 152.76;

    $this->assertEqualsWithDelta($expected, $result, 0.000001);

    ###

    $auction = new Auction();
    $auction->setVehicleType('common');
    $auction->setVehicleValue(501);
    $this->feesCalculator->calculateAllFees($auction);
    $result = $auction->getTotalFees();
    $expected = 170.02;

    $this->assertEqualsWithDelta($expected, $result, 0.000001);

    ###

    $auction = new Auction();
    $auction->setVehicleType('common');
    $auction->setVehicleValue(57);
    $this->feesCalculator->calculateAllFees($auction);
    $result = $auction->getTotalFees();
    $expected = 116.14;

    $this->assertEqualsWithDelta($expected, $result, 0.000001);

    ###

    $auction = new Auction();
    $auction->setVehicleType('luxury');
    $auction->setVehicleValue(1800);
    $this->feesCalculator->calculateAllFees($auction);
    $result = $auction->getTotalFees();
    $expected = 367;

    $this->assertEqualsWithDelta($expected, $result, 0.000001);

    ###

    $auction = new Auction();
    $auction->setVehicleType('common');
    $auction->setVehicleValue(1100);
    $this->feesCalculator->calculateAllFees($auction);
    $result = $auction->getTotalFees();
    $expected = 187;

    $this->assertEqualsWithDelta($expected, $result, 0.000001);

    ###

    $auction = new Auction();
    $auction->setVehicleType('luxury');
    $auction->setVehicleValue(1000000);
    $this->feesCalculator->calculateAllFees($auction);
    $result = $auction->getTotalFees();
    $expected = 40320;

    $this->assertEqualsWithDelta($expected, $result, 0.000001);
  }
}
