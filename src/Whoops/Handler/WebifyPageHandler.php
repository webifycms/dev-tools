<?php

/**
 * 	The file is part of the "webifycms/dev-tools", WebifyCMS development tools.
 *
 * 	@see https://webifycms.com/tools/development-tools
 *
 * 	@license Copyright (c) 2022 WebifyCMS
 * 	@license https://webifycms.com/extension/tools/license
 * 	@author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Tools\Whoops\Handler;

use Composer\InstalledVersions;
use Stringable;
use Throwable;
use Whoops\Exception\{Formatter, Frame};
use Whoops\Handler\Handler;
use Whoops\Inspector\InspectorInterface;
use Whoops\RunInterface;

/**
 * Renders the WebifyCMS development error page.
 *
 * The page is built from the error Whoops inspected: it shows the message, each
 * stack frame with its source line, and the request values with secrets hidden.
 * The stylesheet is read from this package and placed into the page. The logo
 * defaults to WebifyCMS but can be replaced or hidden by the application.
 */
final class WebifyPageHandler extends Handler
{
	public const string PAGE_TITLE = 'WebifyCMS — Development Error';

	private const string TOOLS_PACKAGE  = 'webifycms/dev-tools';

	private const string WHOOPS_PACKAGE = 'filp/whoops';

	/**
	 * The logo is an image source, not a file path, so an application can supply
	 * its own inline image. An empty value removes the brand from the bar.
	 */
	public function __construct(
		private readonly ?string $logo = null,
		private readonly ?string $logoAlt = null,
	) {}

	public function handle(): int
	{
		$inspector = $this->getInspector();
		$run       = $this->getRun();

		if (!$inspector instanceof InspectorInterface || !$run instanceof RunInterface) {
			return Handler::DONE;
		}

		$exception = $inspector->getException();

		if (!$exception instanceof Throwable) {
			return Handler::DONE;
		}

		$escape = static function (mixed $value): string {
			if (is_string($value)) {
				return $value;
			}

			if (is_int($value) || is_float($value)) {
				return (string) $value;
			}

			if (is_bool($value)) {
				return $value ? 'true' : 'false';
			}

			if (null === $value) {
				return '';
			}

			if ($value instanceof Stringable) {
				return $value->__toString();
			}

			return '[unprintable]';
		};
		$format = static function (mixed $value) use ($escape): string {
			if (is_bool($value)) {
				return $value ? 'true' : 'false';
			}

			if (null === $value) {
				return 'null';
			}

			if (is_scalar($value)) {
				return $escape($value);
			}

			return $escape(print_r($value, true));
		};

		$plain = Formatter::formatExceptionPlain($inspector);

		$this->render(
			[
				'title'         => self::PAGE_TITLE,
				'name'          => $inspector->getExceptionName(),
				'message'       => $inspector->getExceptionMessage(),
				'code'          => $exception->getCode(),
				'previous'      => $this->previousErrors($inspector),
				'frames'        => $this->frames($inspector, $run),
				'tables'        => $this->tables(),
				'plain'         => is_string($plain) ? $plain : '',
				'logo'          => $this->logoSrc(),
				'logoAlt'       => $this->logoAlt(),
				'css'           => $this->stylesheet(),
				'toolsVersion'  => $this->packageVersion(self::TOOLS_PACKAGE),
				'whoopsVersion' => $this->packageVersion(self::WHOOPS_PACKAGE),
				'e'             => $escape,
				'format'        => $format,
			]
		);

		return Handler::QUIT;
	}

	/**
	 * @return array<int, array{name: string, message: string}>
	 */
	private function previousErrors(InspectorInterface $inspector): array
	{
		$previous = [];

		foreach ($inspector->getPreviousExceptions() as $error) {
			if ($error instanceof Throwable) {
				$previous[] = [
					'name'    => $error::class,
					'message' => $error->getMessage(),
				];
			}
		}

		return $previous;
	}

	/**
	 * @return array<int, array{
	 *     index: int,
	 *     call: string,
	 *     file: string,
	 *     line: null|int,
	 *     application: bool,
	 *     snippet: array<int, array{number: int, code: string, current: bool}>
	 * }>
	 */
	private function frames(InspectorInterface $inspector, RunInterface $run): array
	{
		$frames = [];

		foreach ($inspector->getFrames($run->getFrameFilters()) as $index => $frame) {
			if (!$frame instanceof Frame) {
				continue;
			}

			$line    = $frame->getLine();
			$start   = is_int($line) ? max(0, $line - 6) : 0;
			$lines   = $frame->getFileLines($start, 13);
			$snippet = [];

			if (is_array($lines)) {
				foreach ($lines as $number => $text) {
					$number    = (int) $number + 1;
					$snippet[] = [
						'number'  => $number,
						'code'    => (string) $text,
						'current' => $number === $line,
					];
				}
			}

			$class    = $frame->getClass();
			$function = $frame->getFunction();
			$call     = null !== $class && null !== $function ? $class . '::' . $function : $class . $function;

			$frames[] = [
				'index'       => (int) $index + 1,
				'call'        => $call,
				'file'        => (string) ($frame->getFile() ?? ''),
				'line'        => $line,
				'application' => $frame->isApplication(),
				'snippet'     => $snippet,
			];
		}

		return $frames;
	}

	/**
	 * @return array<string, array<array-key, mixed>>
	 */
	private function tables(): array
	{
		$tables = [
			'GET Data'              => $_GET,
			'POST Data'             => $_POST,
			'Files'                 => $_FILES,
			'Cookies'               => $_COOKIE,
			'Session'               => $_SESSION ?? [],
			'Server / Request Data' => $_SERVER,
			'Environment Variables' => $_ENV,
		];

		foreach ($tables as $name => $values) {
			$tables[$name] = $this->mask($values);
		}

		return $tables;
	}

	/**
	 * Hides values whose names suggest passwords, tokens or keys, so the page can
	 * be shared without giving away a way to sign in somewhere.
	 *
	 * @param array<array-key, mixed> $values
	 *
	 * @return array<array-key, mixed>
	 */
	private function mask(array $values): array
	{
		$masked = [];

		foreach ($values as $key => $item) {
			if (
				is_string($key)
				&& 1 === preg_match(
					'/(password|passwd|secret|token|api[_-]?key|private[_-]?key|credential|auth)/i',
					$key
				)
			) {
				$masked[$key] = '[redacted]';

				continue;
			}

			$masked[$key] = is_array($item) ? $this->mask($item) : $item;
		}

		return $masked;
	}

	private function logoSrc(): string
	{
		if (null !== $this->logo) {
			return $this->logo;
		}

		return $this->bundledLogo();
	}

	private function logoAlt(): string
	{
		if (null !== $this->logoAlt) {
			return $this->logoAlt;
		}

		return null === $this->logo ? 'WebifyCMS' : 'Application logo';
	}

	/**
	 * The bundled logo is embedded as a data address so no file path is exposed.
	 */
	private function bundledLogo(): string
	{
		$path = dirname(__DIR__, 3) . '/assets/logo.png';

		if (!is_file($path)) {
			return '';
		}

		$contents = file_get_contents($path);

		return is_string($contents) ? 'data:image/png;base64,' . base64_encode($contents) : '';
	}

	/**
	 * Composer does not always record a version for the package rendering this
	 * page, so the footer falls back to a plain label when none is available.
	 */
	private function packageVersion(string $package): string
	{
		if (!InstalledVersions::isInstalled($package)) {
			return 'version unavailable';
		}

		$version   = InstalledVersions::getPrettyVersion($package);
		$reference = InstalledVersions::getReference($package);

		if (!is_string($version) || '' === $version) {
			return 'version unavailable';
		}

		return is_string($reference) && '' !== $reference
			? $version . ' (' . substr($reference, 0, 7) . ')'
			: $version;
	}

	private function stylesheet(): string
	{
		$path = dirname(__DIR__, 3) . '/assets/whoops.css';

		if (!is_file($path)) {
			return '';
		}

		$css = file_get_contents($path);

		return is_string($css) ? $css : '';
	}

	/**
	 * @param array<string, mixed> $values
	 */
	private function render(array $values): void
	{
		extract($values, EXTR_SKIP);

		include dirname(__DIR__, 3) . '/templates/whoops/error.html.php';
	}
}
