<?php

/**
 * Class Gov_Schedules_Shortcodes
 *
 */
class Gov_Schedules_Shortcodes
{

	public function __construct()
	{
		add_shortcode('gs-agenda', array($this, 'gs_agenda_shortcode'));
	}

	public function gs_agenda_shortcode($atts)
	{
		$atts = shortcode_atts(array(
			'form-group-id' => '',
			'return' => home_url('/?sent=true#message')
		), $atts);

		ob_start(); ?>

		<div id="agenda" class="gs-agenda-container">
			<div class="agenda-cats row">
				<div class="col-md-6">
					<h2 class="section-title mb-5 text-right"><a href="#" class="active" data-event-cat="agenda-cultural">Agenda Cultural</a></h2>
				</div>
				<div class="col-md-6">
					<h2 class="section-title mb-5 text-left"><a href="#" data-event-cat="agendo-do-ministro">Agenda do Ministro</a></h2>
				</div>
			</div>
			<div class="daypicker-wrapper">
				<ul class="daypicker">
					<?php
					setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
					date_default_timezone_set('America/Sao_Paulo');

					for($i = 3; $i >= 1; $i--) {
						$date = date_create();
						date_sub($date, date_interval_create_from_date_string( $i . ' days')); ?>

						<li>
							<a href="#" data-day="<?php echo date_format($date, 'Y-m-d'); ?>">
								<span><?php echo date_format($date, 'd'); ?></span>
								<small><?php echo strftime('%a', strtotime( date_format($date, 'Y-m-d') ) ); ?></small>
							</a>
						</li>

						<?php
					}

					for($i = 0; $i <= 3; $i++) {
						$date = date_create();
						date_add($date, date_interval_create_from_date_string($i . ' days')); ?>

						<li <?php echo $i === 0 ? 'class="selected"' : ''; ?>>
							<a href="#" data-day="<?php echo date_format($date, 'Y-m-d'); ?>">
								<span><?php echo date_format($date, 'd'); ?></span>
								<small><?php echo strftime('%a', strtotime( date_format($date, 'Y-m-d') ) ); ?></small>
							</a>
						</li>

						<?php
					} ?>
				</ul>
			</div>
			<div class="monthpicker-wrapper text-center">
				<a href="#" class="monthpicker">NOV 2018</a>
				<input type="text" id="datepicker" class="sr-only">
			</div>
			<div class="events">

				<?php
				$args = array(
					'post_type' => 'event',
					'tax_query' => array(
						array (
							'taxonomy' => 'event-category',
							'field' => 'slug',
							'terms' => 'agenda-cultural',
						)
					),
					'meta_query'     => array(
						array(
							'key'     => 'dados_do_evento_data-de-incio',
							'value'   => date( 'Y-m-d' ),
							'compare' => 'LIKE',
							//'type'    => 'DATE'
						)
					)
				);

				$query = new WP_Query($args);

				if( $query->have_posts() ):
					while ($query->have_posts()) : $query->the_post();

						$locaction = get_post_meta( get_the_ID(), 'dados_do_evento_location', true );
						$date = get_post_meta( get_the_ID(), 'dados_do_evento_data-de-incio', true );
						$raw_date = explode(' ', $date ); ?>

						<div class="event-item">
							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<span class="location"><?php echo $locaction; ?></span>
							<span class="time"><?php echo $raw_date[1]; ?></span>
						</div>

					<?php

					endwhile; wp_reset_query();
				else: ?>
					<div class="event-item">
						<span class="location">Sem compromissos oficiais.</span>
					</div>
				<?php endif;
				?>
			</div>
			<div class="agenda-footer text-center">
				<a href="<?php echo home_url('/agenda'); ?>" class="btn text-uppercase mt-5">Agenda completa</a>
			</div>
		</div>

		<?php return ob_get_clean();
	}


}