<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Document;

interface DocumentControlFactory
{

	public function create(): DocumentControl;

}
