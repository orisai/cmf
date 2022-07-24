<?php declare(strict_types = 1);

namespace Tests\OriCMF\Unit\UI;

use Generator;
use OriCMF\UI\ActionLink;
use OriCMF\UI\Presenter\BasePresenter;
use Orisai\Exceptions\Logic\InvalidArgument;
use PHPUnit\Framework\TestCase;

final class ActionLinkTest extends TestCase
{

	public function testClass(): void
	{
		$class = BasePresenter::class;

		$link = ActionLink::fromClass($class);
		self::assertSame(
			$link->getDestination(),
			":$class:default",
		);
		self::assertSame(
			[],
			$link->getArguments(),
		);

		$link = ActionLink::fromClass($class, ['a' => 'b'], 'foo');
		self::assertSame(
			$link->getDestination(),
			":$class:foo",
		);
		self::assertSame(
			['a' => 'b'],
			$link->getArguments(),
		);
	}

	public function testMapping(): void
	{
		$link = ActionLink::fromMapping(':Foo:Bar:baz');
		self::assertSame(
			$link->getDestination(),
			':Foo:Bar:baz',
		);
		self::assertSame(
			[],
			$link->getArguments(),
		);

		$link = ActionLink::fromMapping('//:Foo:Bar:baz', ['a' => 'b']);
		self::assertSame(
			$link->getDestination(),
			'//:Foo:Bar:baz',
		);
		self::assertSame(
			['a' => 'b'],
			$link->getArguments(),
		);
	}

	public function testAbsolute(): void
	{
		$class = BasePresenter::class;
		$link = ActionLink::fromClass($class);
		self::assertSame(
			$link->getDestination(),
			":$class:default",
		);

		$link->setAbsolute();
		self::assertSame(
			$link->getDestination(),
			"//:$class:default",
		);

		$link = ActionLink::fromMapping(':Foo:Bar:baz');
		$link->setAbsolute();
		self::assertSame(
			$link->getDestination(),
			'//:Foo:Bar:baz',
		);

		$link = ActionLink::fromMapping('//:Foo:Bar:baz');
		$link->setAbsolute(false);
		self::assertSame(
			$link->getDestination(),
			':Foo:Bar:baz',
		);
	}

	public function testAnchor(): void
	{
		$link = ActionLink::fromMapping(':Foo:Bar:baz');
		$link->setAnchor('anchor');
		self::assertSame(
			$link->getDestination(),
			':Foo:Bar:baz#anchor',
		);

		$class = BasePresenter::class;
		$link = ActionLink::fromClass($class);
		$link->setAnchor('anchor');
		self::assertSame(
			$link->getDestination(),
			":$class:default#anchor",
		);
	}

	public function testAddArgument(): void
	{
		$link = ActionLink::fromClass(BasePresenter::class, ['a' => 1]);
		self::assertSame(
			[
				'a' => 1,
			],
			$link->getArguments(),
		);

		$link->addArgument('a', 2);
		$link->addArgument('b', 3);
		self::assertSame(
			[
				'a' => 2,
				'b' => 3,
			],
			$link->getArguments(),
		);
	}

	/**
	 * @dataProvider provideForbidden
	 */
	public function testForbidden(string $destination): void
	{
		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(<<<'MSG'
Destination must be an absolute link. Relative links and "this" are forbidden.
Format: [[[module:]presenter:]action | signal!] [#fragment]
MSG);

		ActionLink::fromMapping($destination);
	}

	/**
	 * @return Generator<array<mixed>>
	 */
	public function provideForbidden(): Generator
	{
		yield ['this'];
		yield ['//this'];
		yield ['Foo:Bar:baz'];
	}

}
