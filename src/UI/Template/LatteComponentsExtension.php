<?php declare(strict_types = 1);

namespace OriCMF\UI\Template;

use Latte\Extension;
use OriCMF\UI\Template\Nodes\FormErrorsNode;
use OriCMF\UI\Template\Nodes\FormFieldNode;

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
