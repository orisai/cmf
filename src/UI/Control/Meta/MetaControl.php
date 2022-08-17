<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Meta;

use OriCMF\UI\Control\BaseControl;
use function implode;
use function ksort;

/**
 * @property-read MetaTemplate $template
 */
final class MetaControl extends BaseControl
{

	/** @var array<string> */
	private array $httpEquivs = [];

	/** @var array<string> */
	private array $metasWithName = [];

	/** @var array<string> */
	private array $metasWithProperty = [];

	/**
	 * Sets robots
	 * <meta name="robots" content="$value1,$value2,$value3...">
	 *
	 * @param array<string> $values
	 * @return $this
	 */
	public function setRobots(array $values): self
	{
		$this->metasWithName['robots'] = implode(', ', $values);

		return $this;
	}

	/**
	 * Adds standard meta <meta name="$name" content="$content">
	 *
	 * @return $this
	 */
	public function addMeta(string $name, string $content): self
	{
		$this->metasWithName[$name] = $content;

		return $this;
	}

	/**
	 * Adds application link meta
	 * <meta property="al:$property" content="$content">
	 *
	 * @return $this
	 */
	public function addApplicationLink(string $property, string $content): self
	{
		$this->metasWithProperty['al:' . $property] = $content;

		return $this;
	}

	/**
	 * Adds open graph meta
	 * <meta property="og:$property" content="$content">
	 *
	 * @return $this
	 */
	public function addOpenGraph(string $property, string $content): self
	{
		$this->metasWithProperty['og:' . $property] = $content;

		return $this;
	}

	/**
	 * Adds facebook meta
	 * <meta property="fb:$property" content="$content" />
	 *
	 * @return $this
	 */
	public function addFacebook(string $property, string $content): self
	{
		$this->metasWithProperty['fb:' . $property] = $content;

		return $this;
	}

	/**
	 * Adds twitter meta
	 * <meta name="twitter:$name" content="$content">
	 *
	 * @return $this
	 */
	public function addTwitter(string $name, string $content): self
	{
		$this->metasWithName['twitter:' . $name] = $content;

		return $this;
	}

	/**
	 * Adds httpEquiv meta
	 * <meta http-equiv="$httpEquiv" content="$content">
	 *
	 * @return $this
	 */
	public function addHttpEquiv(string $httpEquiv, string $content): self
	{
		$this->httpEquivs[$httpEquiv] = $content;

		return $this;
	}

	/**
	 * Sets author meta
	 * <meta name="author" content="$author">
	 *
	 * @return $this
	 */
	public function setAuthor(string $author): self
	{
		$this->metasWithName['author'] = $author;

		return $this;
	}

	/**
	 * Sets description meta
	 * <meta name="description" content="$description">
	 *
	 * @return $this
	 */
	public function setDescription(string $description): self
	{
		$this->metasWithName['description'] = $description;

		return $this;
	}

	public function render(): void
	{
		$this->template->httpEquivs = $this->httpEquivs;
		ksort($this->metasWithName);
		$this->template->metasWithName = $this->metasWithName;
		ksort($this->metasWithProperty);
		$this->template->metasWithProperty = $this->metasWithProperty;

		$this->template->render();
	}

}
