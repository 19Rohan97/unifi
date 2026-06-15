<?php
/**
 * WLV Process Timeline shortcode — paste-ready snippet for functions.php
 *
 * Usage in any page/post (or Elementor "Shortcode" widget):
 *   [wlv_process_timeline]
 *
 * Optional color overrides:
 *   [wlv_process_timeline bg="#f7f7f5" ink="#0d0d0d" gold="#fecc07"]
 *
 * Edit the $steps array below to change step copy / icons.
 */

add_shortcode( 'wlv_process_timeline', 'wlv_process_timeline_render' );

function wlv_process_timeline_render( $atts ) {

	$atts = shortcode_atts(
		array(
			'bg'   => '#f7f7f5',
			'ink'  => '#0d0d0d',
			'gold' => '#fecc07',
		),
		$atts,
		'wlv_process_timeline'
	);

	$steps = array(
		array(
			'num'   => '01',
			'label' => 'Step 01 · ~1 week',
			'title' => 'Site Survey & Design',
			'desc'  => 'We walk your space, run an RF survey, plan camera sightlines, and audit your existing wiring and door hardware. Predictive heat-maps in UniFi Design Center show exactly where every AP and camera will go.',
			'icon'  => '<svg viewBox="0 0 32 32" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 28V8a2 2 0 0 1 2-2h14l8 8v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z"/><path d="M20 6v8h8M9 18h14M9 22h14M9 26h8"/></svg>',
		),
		array(
			'num'   => '02',
			'label' => 'Step 02 · 2–3 days',
			'title' => 'Detailed Proposal',
			'desc'  => 'You get a line-item proposal with the exact hardware, cabling, labor, and timeline. No surprises — every part number priced out, every labor hour accounted for.',
			'icon'  => '<svg viewBox="0 0 32 32" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="4" width="20" height="24" rx="2"/><path d="M11 11h10M11 16h10M11 21h6"/></svg>',
		),
		array(
			'num'   => '03',
			'label' => 'Step 03 · on-site build',
			'title' => 'Cabling & Install',
			'desc'  => 'Our crew runs every Cat6/Cat6A drop, builds your network rack, mounts every AP and camera, and pulls every door wire — all in-house by a licensed Michigan low-voltage contractor.',
			'icon'  => '<svg viewBox="0 0 32 32" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 18l8-8 4 4 12-12"/><path d="M4 26h24M4 22h24"/><circle cx="20" cy="10" r="1.5" fill="currentColor"/></svg>',
		),
		array(
			'num'   => '04',
			'label' => 'Step 04 · scheduled cutover',
			'title' => 'Configuration & Cutover',
			'desc'  => 'VLANs, SSIDs, RADIUS / 802.1X, camera AI tuning, access credentialing — configured before we leave. Cutovers are scheduled when it\'s safest for your business.',
			'icon'  => '<svg viewBox="0 0 32 32" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><circle cx="16" cy="16" r="3"/><path d="M16 4v4M16 24v4M4 16h4M24 16h4M7 7l3 3M22 22l3 3M7 25l3-3M22 10l3-3"/></svg>',
		),
		array(
			'num'   => '05',
			'label' => 'Step 05 · 24/7 ongoing',
			'title' => 'Ongoing Support',
			'desc'  => 'Pair the install with a Dark Blue Technologies managed agreement and we monitor the whole stack 24/7, push firmware updates, and respond to issues before you notice them.',
			'icon'  => '<svg viewBox="0 0 32 32" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 3l11 4v9c0 7-5 11-11 13-6-2-11-6-11-13V7z"/><path d="M11 16l4 4 7-7"/></svg>',
		),
	);

	// Emit shared <style> + <script> exactly once per page.
	static $assets_done = false;
	$assets_block       = '';
	if ( ! $assets_done ) {
		$assets_block = wlv_process_timeline_assets( $atts );
		$assets_done  = true;
	}

	ob_start();
	?>
	<?php echo $assets_block; ?>
	<div class="wlv-process">
		<div class="wlv-process__container">
			<ol class="wlv-process__timeline">
				<?php foreach ( $steps as $step ) : ?>
					<li class="wlv-process__step">
						<div class="wlv-process__node" aria-hidden="true">
							<span class="wlv-process__num"><?php echo esc_html( $step['num'] ); ?></span>
						</div>
						<article class="wlv-process__card">
							<div class="wlv-process__card-head">
								<span class="wlv-process__icon" aria-hidden="true"><?php echo $step['icon']; ?></span>
								<span class="wlv-process__label"><?php echo esc_html( $step['label'] ); ?></span>
							</div>
							<h3 class="wlv-process__card-title"><?php echo esc_html( $step['title'] ); ?></h3>
							<p class="wlv-process__card-desc"><?php echo esc_html( $step['desc'] ); ?></p>
						</article>
					</li>
				<?php endforeach; ?>
			</ol>

			<div class="wlv-process__dots" role="tablist" aria-label="Process step navigation">
				<?php foreach ( $steps as $i => $step ) : ?>
					<button type="button"
						class="wlv-process__dot<?php echo 0 === $i ? ' is-active' : ''; ?>"
						data-step="<?php echo (int) $i; ?>"
						aria-label="Step <?php echo (int) ( $i + 1 ); ?>"></button>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

function wlv_process_timeline_assets( $atts ) {

	$bg   = sanitize_hex_color( $atts['bg'] )   ? $atts['bg']   : '#f7f7f5';
	$ink  = sanitize_hex_color( $atts['ink'] )  ? $atts['ink']  : '#0d0d0d';
	$gold = sanitize_hex_color( $atts['gold'] ) ? $atts['gold'] : '#fecc07';

	ob_start();
	?>
<style id="wlv-process-styles">
.wlv-process {
	--wlv-bg: <?php echo esc_attr( $bg ); ?>;
	--wlv-ink: <?php echo esc_attr( $ink ); ?>;
	--wlv-gold: <?php echo esc_attr( $gold ); ?>;
	--wlv-gold-deep: #f5b800;
	--wlv-ink-soft: #4a4a4a;
	--wlv-muted: #6b6b6b;
	--wlv-line: #e6e6e6;
	--wlv-line-2: #d8d8d8;
	--wlv-card-bg: #ffffff;
	--wlv-warm: #fbf8ef;
	--wlv-radius-lg: 18px;

	position: relative;
	background: var(--wlv-bg);
	color: var(--wlv-ink);
	padding: clamp(40px, 6vw, 72px) 0;
	overflow: hidden;
	font-family: "Lato", "Inter", system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
}
.wlv-process *, .wlv-process *::before, .wlv-process *::after { box-sizing: border-box; }
.wlv-process::before, .wlv-process::after {
	content: "";
	position: absolute;
	width: 360px; height: 360px;
	border-radius: 50%;
	background: radial-gradient(circle, rgba(254,204,7,.18), transparent 70%);
	pointer-events: none;
}
.wlv-process::before { top: 12%; left: -120px; }
.wlv-process::after  { bottom: 6%; right: -120px; }

.wlv-process__container {
	position: relative;
	z-index: 1;
	width: 100%;
	max-width: 1200px;
	margin: 0 auto;
	padding: 0 clamp(16px, 3vw, 28px);
}

/* ============ Desktop: alternating gold-rail timeline ============ */
.wlv-process__timeline {
	list-style: none;
	padding: 0;
	margin: 0 auto;
	max-width: 980px;
	position: relative;
	display: flex;
	flex-direction: column;
	gap: 28px;
}
.wlv-process__timeline::before {
	content: "";
	position: absolute;
	top: 32px; bottom: 32px;
	left: 50%;
	width: 3px;
	background: repeating-linear-gradient(180deg, var(--wlv-gold) 0 14px, transparent 14px 22px);
	transform: translateX(-50%);
	border-radius: 3px;
}

.wlv-process__step {
	display: grid;
	grid-template-columns: 1fr 80px 1fr;
	align-items: start;
	margin: 0;
}

.wlv-process__node {
	grid-column: 2;
	grid-row: 1;
	justify-self: center;
	position: relative;
	z-index: 2;
	width: 68px; height: 68px;
	display: grid; place-items: center;
	background: var(--wlv-ink);
	color: var(--wlv-gold);
	border-radius: 50%;
	border: 4px solid var(--wlv-bg);
	box-shadow: 0 10px 24px rgba(0,0,0,.16), 0 0 0 1px rgba(0,0,0,.04);
	transition: transform .25s ease, background .2s ease, color .2s ease;
}
.wlv-process__node::after {
	content: "";
	position: absolute;
	inset: -10px;
	border-radius: 50%;
	border: 1px dashed rgba(254,204,7,.45);
	opacity: 0;
	transition: opacity .25s ease;
}
.wlv-process__step:hover .wlv-process__node {
	transform: scale(1.06);
	background: var(--wlv-gold);
	color: var(--wlv-ink);
}
.wlv-process__step:hover .wlv-process__node::after { opacity: 1; }
.wlv-process__num {
	font-weight: 900;
	font-size: 1.25rem;
	letter-spacing: -0.02em;
	line-height: 1;
}

.wlv-process__card {
	background: var(--wlv-card-bg);
	border: 1px solid var(--wlv-line);
	border-radius: var(--wlv-radius-lg);
	padding: 24px 26px;
	box-shadow: 0 1px 2px rgba(0,0,0,.06), 0 2px 4px rgba(0,0,0,.04);
	position: relative;
	transition: transform .2s ease, box-shadow .25s ease, border-color .2s ease;
}
.wlv-process__card:hover {
	transform: translateY(-2px);
	border-color: var(--wlv-gold);
	box-shadow: 0 4px 16px rgba(0,0,0,.08), 0 1px 3px rgba(0,0,0,.06);
}
.wlv-process__card-title {
	font-size: 1.2rem;
	font-weight: 900;
	letter-spacing: -0.01em;
	line-height: 1.2;
	margin: 0 0 8px;
	color: var(--wlv-ink);
}
.wlv-process__card-desc {
	color: var(--wlv-ink-soft);
	font-size: .95rem;
	margin: 0;
	line-height: 1.6;
}
.wlv-process__card-head { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
.wlv-process__icon {
	width: 38px; height: 38px;
	display: grid; place-items: center;
	background: var(--wlv-warm);
	color: var(--wlv-ink);
	border-radius: 10px;
	flex-shrink: 0;
	transition: background .2s ease, color .2s ease;
}
.wlv-process__card:hover .wlv-process__icon {
	background: var(--wlv-ink);
	color: var(--wlv-gold);
}
.wlv-process__label {
	font-weight: 800;
	font-size: .72rem;
	letter-spacing: .12em;
	text-transform: uppercase;
	color: var(--wlv-muted);
}

.wlv-process__step:nth-child(odd) .wlv-process__card  { grid-column: 1; }
.wlv-process__step:nth-child(even) .wlv-process__card { grid-column: 3; }

/* Elbow + dashed connector from card to rail */
.wlv-process__card::before {
	content: "";
	position: absolute;
	top: 28px;
	width: 12px; height: 12px;
	background: var(--wlv-card-bg);
	border-top: 1px solid var(--wlv-line);
	border-right: 1px solid var(--wlv-line);
	transition: border-color .2s ease;
}
.wlv-process__step:nth-child(odd)  .wlv-process__card::before { right: -7px; transform: rotate(45deg); }
.wlv-process__step:nth-child(even) .wlv-process__card::before { left: -7px;  transform: rotate(-135deg); }
.wlv-process__card:hover::before { border-color: var(--wlv-gold); }

.wlv-process__card::after {
	content: "";
	position: absolute;
	top: 34px;
	height: 1px;
	width: 28px;
	border-top: 1.5px dashed rgba(0,0,0,.18);
}
.wlv-process__step:nth-child(odd)  .wlv-process__card::after { right: -28px; }
.wlv-process__step:nth-child(even) .wlv-process__card::after { left: -28px; }

/* Position dots hidden on desktop */
.wlv-process__dots { display: none; }


/* ============ Mobile: horizontal scroll-snap slider ============ */
@media (max-width: 720px) {
	.wlv-process__timeline {
		display: flex;
		flex-direction: row;
		gap: 14px;
		overflow-x: auto;
		overflow-y: hidden;
		scroll-snap-type: x mandatory;
		scroll-padding-left: 20px;
		scroll-behavior: smooth;
		padding: 8px 20px 24px;
		margin: 0 calc(-1 * clamp(16px, 3vw, 28px));
		-webkit-overflow-scrolling: touch;
		scrollbar-width: none;
	}
	.wlv-process__timeline::-webkit-scrollbar { display: none; }
	.wlv-process__timeline::before { display: none; }

	.wlv-process__step {
		display: flex;
		flex-direction: column;
		align-items: stretch;
		flex: 0 0 86%;
		max-width: 360px;
		min-height: 280px;
		background: var(--wlv-card-bg);
		border: 1px solid var(--wlv-line);
		border-radius: var(--wlv-radius-lg);
		padding: 22px 22px 24px;
		box-shadow: 0 4px 16px rgba(0,0,0,.08), 0 1px 3px rgba(0,0,0,.06);
		scroll-snap-align: start;
		scroll-snap-stop: always;
		position: relative;
		overflow: hidden;
	}
	.wlv-process__step::before {
		content: "";
		position: absolute; top: 0; left: 0; right: 0;
		height: 4px;
		background: var(--wlv-gold);
	}
	.wlv-process__node {
		grid-column: auto;
		grid-row: auto;
		justify-self: start;
		width: 56px; height: 56px;
		border-width: 3px;
		margin-bottom: 18px;
		box-shadow: 0 6px 16px rgba(0,0,0,.12);
	}
	.wlv-process__num { font-size: 1.05rem; }

	.wlv-process__card,
	.wlv-process__step:nth-child(odd) .wlv-process__card,
	.wlv-process__step:nth-child(even) .wlv-process__card {
		grid-column: auto;
		background: transparent;
		border: 0;
		box-shadow: none;
		padding: 0;
	}
	.wlv-process__card::before,
	.wlv-process__card::after,
	.wlv-process__step:nth-child(odd)  .wlv-process__card::before,
	.wlv-process__step:nth-child(even) .wlv-process__card::before,
	.wlv-process__step:nth-child(odd)  .wlv-process__card::after,
	.wlv-process__step:nth-child(even) .wlv-process__card::after { display: none; }
	.wlv-process__card-title { font-size: 1.1rem; }
	.wlv-process__card-desc  { font-size: .92rem; }
	.wlv-process__card:hover { transform: none; }
	.wlv-process__card-head  { margin-bottom: 10px; }

	.wlv-process__dots {
		display: flex;
		justify-content: center;
		align-items: center;
		gap: 8px;
		margin-top: 8px;
		padding: 4px 0;
	}
	.wlv-process__dot {
		width: 8px; height: 8px;
		border-radius: 50%;
		border: 0;
		padding: 0;
		background: var(--wlv-line-2);
		cursor: pointer;
		transition: background .2s ease, transform .2s ease, width .2s ease;
	}
	.wlv-process__dot.is-active {
		background: var(--wlv-gold-deep);
		width: 24px;
		border-radius: 999px;
	}
	.wlv-process__dot:focus-visible {
		outline: 2px solid var(--wlv-ink);
		outline-offset: 3px;
	}
}

@media (prefers-reduced-motion: reduce) {
	.wlv-process *, .wlv-process *::before, .wlv-process *::after {
		animation-duration: .01ms !important;
		transition-duration: .01ms !important;
	}
}
</style>
<script id="wlv-process-script">
(function () {
	"use strict";
	function init(root) {
		var slider = root.querySelector(".wlv-process__timeline");
		var dots   = root.querySelectorAll(".wlv-process__dot");
		var steps  = root.querySelectorAll(".wlv-process__step");
		if (!slider || !dots.length || !steps.length) return;

		function setActive(idx) {
			dots.forEach(function (d, i) { d.classList.toggle("is-active", i === idx); });
		}

		dots.forEach(function (dot, i) {
			dot.addEventListener("click", function () {
				var target = steps[i];
				if (!target) return;
				slider.scrollTo({
					left: target.offsetLeft - slider.offsetLeft,
					behavior: "smooth"
				});
			});
		});

		function updateActiveFromScroll() {
			var sliderRect = slider.getBoundingClientRect();
			var probe = sliderRect.left + 40;
			var bestIdx = 0;
			var bestDist = Infinity;
			for (var i = 0; i < steps.length; i++) {
				var r = steps[i].getBoundingClientRect();
				var dist = Math.abs(r.left - probe);
				if (dist < bestDist) { bestDist = dist; bestIdx = i; }
			}
			setActive(bestIdx);
		}

		var raf = 0;
		slider.addEventListener("scroll", function () {
			if (raf) return;
			raf = requestAnimationFrame(function () {
				updateActiveFromScroll();
				raf = 0;
			});
		}, { passive: true });
		updateActiveFromScroll();
	}

	function boot() {
		document.querySelectorAll(".wlv-process").forEach(init);
	}
	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", boot);
	} else {
		boot();
	}
})();
</script>
	<?php
	return ob_get_clean();
}
