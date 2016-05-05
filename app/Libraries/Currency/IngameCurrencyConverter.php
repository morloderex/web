<?php

namespace App\Libraries\Currency;

/**
* Handles Copper to silver & gold ratio
*/
class IngameCurrencyConverter
{
	protected $currencyRates = [
		'1s'	=>	100,
		'10s'	=>	1000,
		'1g'	=>	10000,
		'10g'	=>	100000,
		'100g'	=>	1000000
	];

	protected $copper;

	public function __construct(int $copper)
	{
		$this->copper = $copper;	
	}

	public function format(int $money) {
		return money_format('%.2n', $money);
	}

	public function getMoney()
	{
		$copper = $this->getCopper();
		$silver = $this->getSilver();
		$gold 	= $this->getGold();

		return "$gold Gold, $silver Silver, $copper Copper.";
	}

	public function getCopper() : int
	{
		$copper = $this->copper;
		$currencyRates = $this->currencyRates;
		
		return $copper / $currencyRates['1s'];
	}

	public function getSilver() : int
	{
		$copper = $this->copper;
		$currencyRates = $this->currencyRates;

		return $copper / $currencyRates['1s'] % $currencyRates['1s'];
	}

	public function getGold() : int
	{
		$copper = $this->copper;
		$currencyRates = $this->currencyRates;

		return $copper / $currencyRates['10g'] % $currencyRates['1s'];
	}
}