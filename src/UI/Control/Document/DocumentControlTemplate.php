<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Document;

use Nette\Utils\Html;
use OriCMF\UI\Control\BaseControlTemplate;

final class DocumentControlTemplate extends BaseControlTemplate
{

	public DocumentControl $control;

	public Html $documentStart;

	public Html $documentEnd;

}
