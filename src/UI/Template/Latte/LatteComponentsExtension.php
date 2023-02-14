<?php declare(strict_types = 1);

namespace OriCMF\UI\Template\Latte;

use Latte\Extension;
use OriCMF\UI\Template\Latte\Nodes\FormErrorsNode;
use OriCMF\UI\Template\Latte\Nodes\FormFieldNode;

final class LatteComponentsExtension extends Extension
{

	public function getTags(): array
	{
		return [
			'c:formField' => FormFieldNode::create(...),
			'c:formErrors' => FormErrorsNode::create(...),
		];
	}

}
