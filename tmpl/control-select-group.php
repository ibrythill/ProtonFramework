<label>

	<# if ( data.label ) { #>
		<span class="proton-opt-label">{{ data.label }}</span>
	<# } #>

	<# if ( data.description ) { #>
		<span class="proton-opt-description">{{{ data.description }}}</span>
	<# } #>

	<select {{{ data.attr }}}>

		<# _.each( data.choices, function( label, choice ) { #>

			<option value="{{ choice }}" <# if ( choice === data.value ) { #> selected="selected" <# } #>>{{ label }}</option>

		<# } ) #>

		<# _.each( data.group, function( group ) { #>

			<optgroup label="{{ group.label }}">

				<# _.each( group.choices, function( label, choice ) { #>

					<option value="{{ choice }}" <# if ( choice === data.value ) { #> selected="selected" <# } #>>{{ label }}</option>

				<# } ) #>

			</optgroup>
		<# } ) #>

	</select>
</label>
