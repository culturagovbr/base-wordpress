<?php

class EF_Font_Field extends EF_Field_Base {
	
	public static $google_fonts = array(
			'Pathway Gothic One' => array( 'family' => "'Pathway Gothic One', sans-serif", 'url' => 'http://fonts.googleapis.com/css?family=Pathway+Gothic+One' ),
			'Montserrat Alternates' => array( 'family' => "'Montserrat Alternates', sans-serif", 'url' => 'http://fonts.googleapis.com/css?family=Montserrat+Alternates' ),
			'Playfair Display SC' => array( 'family' => "'Playfair Display SC', serif", 'url' => 'http://fonts.googleapis.com/css?family=Playfair+Display+SC' ),
			'Oregano' => array( 'family' => "'Oregano', cursive", 'url' => 'http://fonts.googleapis.com/css?family=Oregano' ),
			'Gilda Display' => array( 'family' => "'Gilda Display', serif", 'url' => 'http://fonts.googleapis.com/css?family=Gilda+Display' ),
			'Hammersmith One' => array( 'family' => "'Hammersmith One', sans-serif", 'url' => 'http://fonts.googleapis.com/css?family=Hammersmith+One' ),
			'Alegreya Sans SC' => array( 'family' => "'Alegreya Sans SC', sans-serif", 'url' => 'http://fonts.googleapis.com/css?family=Alegreya+Sans+SC' ),
			'Varela Round' => array( 'family' => "'Varela Round', sans-serif", 'url' => 'http://fonts.googleapis.com/css?family=Varela+Round' ),
			'Averia Sans Libre' => array( 'family' => "'Averia Sans Libre', cursive", 'url' => 'http://fonts.googleapis.com/css?family=Averia+Sans+Libre' ),
			'Esteban' => array( 'family' => "'Esteban', serif", 'url' => 'http://fonts.googleapis.com/css?family=Esteban' ),
			'Gafata' => array( 'family' => "'Gafata', sans-serif", 'url' => 'http://fonts.googleapis.com/css?family=Gafata' ),
	);

	public $type = 'font';

	public function display() {
		$defaults = array(
				'section_prefix' => 'section_prefix_',
				'class' => 'ef-section',
				'style' => '',
				'id_prefix' => 'id_prefix',
				'selector' => ''
		);

		$args = wp_parse_args( $this->args, $defaults );
		extract( $args );

		$font_types = $this->get_available_font_types(); 
		$font_measures = $this->get_available_font_measures();
		
		?>
		<section id="<?php echo $section_prefix . $this->id ?>" class="<?php echo $class ?>" <?php echo $style; ?>>
			<label for="<?php echo $this->id_prefix . $this->id ?>"><?php echo $this->name ?></label>	
			
			<select name="eventframework[<?php echo $this->id; ?>][font-name]" id="<?php echo $this->id_prefix . $this->id ?>-name" class="ef-select">
				<?php foreach ( $font_types as $key => $text ): ?>
					<option id="<?php echo $key ?>" value="<?php echo $key ?>" <?php echo ( isset( $this->value['font-name'] ) && $key == $this->value['font-name'] ? 'selected' : '' ) ?>><?php echo $text ?></option>
				<?php endforeach ?>
			</select>
			
			<br />
			<label for="<?php echo $this->id_prefix . $this->id ?>-size"><?php _e( 'Font Size', 'dxef' ); ?></label>
			<input id="<?php echo $this->id_prefix . $this->id ?>-size" type="text" name="eventframework[<?php echo $this->id ?>][font-size]" value="<?php if ( ! empty( $this->value['font-size'] ) ) echo $this->value['font-size'] ?>" placeholder="<?php echo $this->name; ?>" class="ef-text" />
			
			<select name="eventframework[<?php echo $this->id; ?>][font-measure]" id="<?php echo $this->id_prefix . $this->id ?>-measure" class="ef-select">
				<?php foreach ( $font_measures as $key => $text ): ?>
					<option id="<?php echo $key ?>" value="<?php echo $key ?>" <?php echo ( isset( $this->value['font-name'] ) && $key == $this->value['font-name'] ? 'selected' : '' ) ?>><?php echo $text ?></option>
				<?php endforeach ?>
			</select>
		</section>
		<?php
	}	
	
	private function get_available_font_types() {
		$font_types = array(
			'Times New Roman' => 'Times New Roman',
			'Arial' => 'Arial',
			'Helvetica' => 'Helvetica',
			'Comic Sans MS' => 'Comic Sans MS',
		);

		$google_fonts = self::get_google_fonts();
		$font_types = array_merge( $font_types, $google_fonts );
		
		return apply_filters( 'ef_font_types_filter', $font_types );
	}
	
	private function get_available_font_measures() {
		$font_measures = array(
			'px' => 'px',
			'em' => 'em',
			'pt' => 'pt',
			'in' => 'in',
		);
	
		return apply_filters( 'ef_font_measures_filter', $font_measures );
	}
	
	public function get_css_rules() {
		$css = array();

		if ( ! empty( $this->value ) ) {
			$font_name = $this->value['font-name'];
			if ( self::is_google_font( $font_name ) ) {
				$font_properties = self::get_google_font_properties( $font_name );
				$font_name = $font_properties['family'];
				echo "<link href='" . $font_properties['url'] . "' rel='stylesheet' type='text/css'>";
			}
			
			$css[ 'font-family' ] = $font_name;
			$css[ 'font-size' ] = $this->value['font-size'] . $this->value['font-measure'];
		}
	
		return $css;
	}
	
	public static function get_google_fonts() {
		$google_fonts = array(
			'Pathway Gothic One' => 'Pathway Gothic One',
			'Montserrat Alternates' => 'Montserrat Alternates',
			'Playfair Display SC' => 'Playfair Display SC',
			'Oregano' => 'Oregano',
			'Gilda Display' => 'Gilda Display',
			'Hammersmith One' => 'Hammersmith One',
			'Alegreya Sans SC' => 'Alegreya Sans SC',
			'Varela Round' => 'Varela Round',
			'Averia Sans Libre' => 'Averia Sans Libre',
			'Esteban' => 'Esteban',
			'Gafata' => 'Gafata'
		);

		return $google_fonts;
	}
	
	public static function get_google_font_properties( $font_name ) {
		if ( ! isset( self::$google_fonts[ $font_name ] ) ) {
			return array( 'family' => '', 'url' => '' );
		}
		
		return self::$google_fonts[ $font_name ];
	}
	
	public static function is_google_font( $font_name ) {
		return isset( self::$google_fonts[ $font_name ] ) ? true : false;
	}
}