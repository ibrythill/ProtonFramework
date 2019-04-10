<# if ( data.label ) { #>
	<span class="proton-opt-label">{{ data.label }}</span>
<# } #>

<# if ( data.description ) { #>
	<span class="proton-opt-description">{{{ data.description }}}</span>
<# } #>

<# _.each( data.choices, function( palette, choice ) { #>
	<label aria-selected="{{ palette.selected }}">
		<input type="radio" value="{{ choice }}" name="{{ data.field_name }}" <# if ( palette.selected ) { #> checked="checked" <# } #> />

		<span class="proton-opt-palette-label">{{ palette.label }}</span>

		<div class="proton-opt-palette-block">

			<# _.each( palette.colors, function( color ) { #>
				<span class="proton-opt-palette-color" style="background-color: {{ color }}">&nbsp;</span>
			<# } ) #>

		</div>
	</label>
<# } ) #>
