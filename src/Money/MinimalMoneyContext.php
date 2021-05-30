<?php declare(strict_types = 1);

namespace OriCMF\Core\Money;

use Brick\Math\BigDecimal;
use Brick\Math\BigNumber;
use Brick\Money\Context;
use Brick\Money\Currency;

final class MinimalMoneyContext implements Context
{

	public function applyTo(BigNumber $amount, Currency $currency, int $roundingMode): BigDecimal
	{
		return $amount->toBigDecimal()->stripTrailingZeros();
	}

	public function getStep(): int
	{
		return 0;
	}

	public function isFixedScale(): bool
	{
		return false;
	}

}
