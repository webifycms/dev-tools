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

?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="robots" content="noindex,nofollow">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= $e($title); ?></title>
	<style><?= $css; ?></style>
</head>
<body>
	<div class="page">
		<header class="topbar">
			<?php if ('' !== $logo) { ?>
				<img class="topbar__logo" src="<?= $e($logo); ?>" alt="<?= $e($logoAlt); ?>" width="160" height="60">
				<span class="topbar__divider" aria-hidden="true"></span>
			<?php } ?>
			<span class="topbar__label"><?= $e($name); ?></span>
		</header>

		<main>
			<section class="hero" aria-labelledby="error-name">
				<p class="hero__eyebrow">Something went wrong</p>
				<h1 class="hero__title" id="error-name"><?= $e($name); ?></h1>
				<p class="hero__message"><?= $e($message); ?></p>
				<div class="hero__meta">
					<span class="pill pill--rose">Exception</span>
					<?php if (0 !== $code) { ?>
						<span class="pill">Code <?= $e($code); ?></span>
					<?php } ?>
					<span class="pill"><?= count($frames); ?> stack frame<?= 1 === count($frames) ? '' : 's'; ?></span>
				</div>
				<div class="hero__actions">
					<button class="button button--primary" type="button" data-copy>Copy error details</button>
					<a class="button button--ghost" href="#stack">View stack trace</a>
				</div>
				<pre id="plain-error" hidden><?= $e($plain); ?></pre>
			</section>

			<?php if ([] !== $previous) { ?>
				<section class="previous" aria-labelledby="previous-title">
					<h2 id="previous-title">Earlier errors</h2>
					<ol>
						<?php foreach ($previous as $error) { ?>
							<li><code><?= $e($error['name']); ?></code>: <?= $e($error['message']); ?></li>
						<?php } ?>
					</ol>
				</section>
			<?php } ?>

			<div class="workspace" id="stack">
				<nav class="panel frames" aria-label="Stack frames">
					<div class="panel__head">
						<h2>Stack trace</h2>
						<span class="count"><?= count($frames); ?></span>
					</div>
					<ol class="frames__list">
						<?php foreach ($frames as $frame) { ?>
							<li>
								<button
									class="frame<?= $frame['application'] ? ' frame--app' : ''; ?><?= 1 === $frame['index'] ? ' is-active' : ''; ?>"
									type="button"
									data-frame="<?= $frame['index']; ?>"
									aria-current="<?= 1 === $frame['index'] ? 'true' : 'false'; ?>"
								>
									<span class="frame__index"><?= $frame['index']; ?></span>
									<span class="frame__call"><?= $e($frame['call']); ?></span>
									<span class="frame__file">
										<?= $e($frame['file']); ?><?php if (null !== $frame['line']) { ?>:<?= $frame['line']; ?><?php } ?>
									</span>
								</button>
							</li>
						<?php } ?>
					</ol>
				</nav>

				<section class="panel details" aria-label="Selected stack frame">
					<?php foreach ($frames as $frame) { ?>
						<article class="detail" data-detail="<?= $frame['index']; ?>"<?php if (1 !== $frame['index']) { ?> hidden<?php } ?>>
							<div class="detail__head">
								<h3><?= $e($frame['call']); ?></h3>
								<p>
									<?= $e($frame['file']); ?><?php if (null !== $frame['line']) { ?>:<?= $frame['line']; ?><?php } ?>
								</p>
							</div>
							<?php if ([] !== $frame['snippet']) { ?>
								<div class="code">
									<?php foreach ($frame['snippet'] as $line) { ?>
										<div class="code__line<?= $line['current'] ? ' code__line--current' : ''; ?>">
											<span class="code__number"><?= $line['number']; ?></span>
											<code><?= $e($line['code']); ?></code>
										</div>
									<?php } ?>
								</div>
							<?php } else { ?>
								<p class="empty">Source code is not available for this frame.</p>
							<?php } ?>
						</article>
					<?php } ?>
				</section>
			</div>

			<section class="context" id="context" aria-labelledby="context-title">
				<h2 id="context-title">Request details</h2>
				<div class="context__grid">
					<?php foreach ($tables as $label => $rows) { ?>
						<details class="table">
							<summary><?= $e($label); ?><span class="count"><?= count($rows); ?></span></summary>
							<?php if ([] === $rows) { ?>
								<p class="empty">Nothing to show.</p>
							<?php } else { ?>
								<div class="table__body">
									<dl>
										<?php foreach ($rows as $key => $value) { ?>
											<dt><?= $e($key); ?></dt>
											<dd><?= $format($value); ?></dd>
										<?php } ?>
									</dl>
								</div>
							<?php } ?>
						</details>
					<?php } ?>
				</div>
			</section>
		</main>

		<footer class="footer">
			<span>
				<a href="https://github.com/webifycms/dev-tools">webifycms/dev-tools</a>
				<code><?= $e($toolsVersion); ?></code>
			</span>
			<span>
				Error page rendered with <a href="https://github.com/filp/whoops">filp/whoops</a>
				<code><?= $e($whoopsVersion); ?></code>
			</span>
		</footer>
	</div>

	<script>
		(function () {
			let buttons = document.querySelectorAll('[data-frame]');
			let details = document.querySelectorAll('[data-detail]');

			for (let i = 0; i < buttons.length; i++) {
				buttons[i].addEventListener('click', function () {
					let selected = this.getAttribute('data-frame');

					for (let j = 0; j < buttons.length; j++) {
						let active = buttons[j].getAttribute('data-frame') === selected;
						buttons[j].classList.toggle('is-active', active);
						buttons[j].setAttribute('aria-current', active ? 'true' : 'false');
					}
					for (let k = 0; k < details.length; k++) {
						details[k].hidden = details[k].getAttribute('data-detail') !== selected;
					}
				});
			}

			let copy = document.querySelector('[data-copy]');
			if (copy) {
				copy.addEventListener('click', function () {
					let text = document.getElementById('plain-error').textContent;

					if (navigator.clipboard) {
						navigator.clipboard.writeText(text);
						return;
					}
					let area = document.createElement('textarea');
					area.value = text;
					document.body.appendChild(area);
					area.select();
					document.execCommand('copy');
					document.body.removeChild(area);
				});
			}
		}());
	</script>
</body>
</html>
