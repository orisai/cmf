<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Document;

use Nette\Utils\Html;
use OriCMF\UI\Control\BaseControl;
use OriCMF\UI\Control\Body\BodyControl;
use OriCMF\UI\Control\Body\BodyControlFactory;
use OriCMF\UI\Control\Head\HeadControl;
use OriCMF\UI\Control\Head\HeadControlFactory;

/**
 * @property-read DocumentControlTemplate $template
 */
final class DocumentControl extends BaseControl
{

	private Html $element;

	public function __construct(
		private readonly HeadControlFactory $headFactory,
		private readonly BodyControlFactory $bodyFactory,
	)
	{
		$this->element = Html::el('html');
	}

	/**
	 * @return $this
	 */
	public function addAttribute(string $name, string $value): self
	{
		$this->element->appendAttribute($name, $value);

		return $this;
	}

	/**
	 * @return $this
	 */
	public function setAttribute(string $name, string $value): self
	{
		$this->element->setAttribute($name, $value);

		return $this;
	}

	/**
	 * @return $this
	 */
	public function removeAttribute(string $name): self
	{
		$this->element->removeAttribute($name);

		return $this;
	}

	public function setCanonicalUrl(string $url): void
	{
		$head = $this['head'];
		$head['links']->addLink($url, 'canonical');
		$head['meta']->addOpenGraph('url', $url);
	}

	public function setSiteName(string $siteName): void
	{
		$head = $this['head'];
		$meta = $head['meta'];

		$head['title']->setSite($siteName);
		$meta->addMeta('application-name', $siteName);
		$meta->addOpenGraph('site_name', $siteName);
	}

	public function setTitle(string $title): void
	{
		$head = $this['head'];
		$head['title']->setMain($title);
		$head['meta']->addOpenGraph('title', $title);
	}

	public function getTitle(): string|null
	{
		return $this['head-title']->getMain();
	}

	public function setAuthor(string $author): void
	{
		$this['head-meta']->setAuthor($author);
	}

	public function setDescription(string $description): void
	{
		$meta = $this['head-meta'];
		$meta->setDescription($description);
		$meta->addOpenGraph('description', $description);
	}

	public function renderStart(): void
	{
		$this->template->documentStart = Html::fromHtml($this->element->startTag());

		$this->template->setView('start');
		$this->template->render();
	}

	public function renderEnd(): void
	{
		$this->template->documentEnd = Html::fromHtml($this->element->endTag());

		$this->template->setView('end');
		$this->template->render();
	}

	protected function createComponentHead(): HeadControl
	{
		return $this->headFactory->create();
	}

	protected function createComponentBody(): BodyControl
	{
		return $this->bodyFactory->create();
	}

}
