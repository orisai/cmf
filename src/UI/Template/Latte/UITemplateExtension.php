<?php declare(strict_types = 1);

namespace OriCMF\UI\Template\Latte;

use Latte\Extension;
use OriCMF\UI\Template\Latte\Filters\UIFilters;
use OriCMF\UI\Template\Latte\Nodes\AlinkNode;

final class UITemplateExtension extends Extension
{

	public function getTags(): array
	{
		return [
			'alink' => AlinkNode::create(...),
		];
	}

	public function getFilters(): array
	{
		return [
			'urlUid' => UIFilters::urlUid(...),
		];
	}

}
