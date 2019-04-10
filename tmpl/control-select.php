<label>

	<# if ( data.label ) { #>
		<span class="proton-opt-label">{{ data.label }}</span>
	<# } #>

	<# if ( data.description ) { #>
		<span class="proton-opt-description">{{{ data.description }}}</span>
	<# } #>

	<select {{{ data.attr }}}>

		<# _.each( data.choices, function( label, choice ) { #>

			<option value="{{ choice }}" <# if ( data.value === choice ) { #> selected="selected" <# } #>>{{ label }}</option>

		<# } ) #>

	</select>
</label>
