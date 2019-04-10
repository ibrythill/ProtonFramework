<# if ( data.label ) { #>
	<span class="proton-opt-label">{{ data.label }}</span>
<# } #>

<# if ( data.description ) { #>
	<span class="proton-opt-description">{{{ data.description }}}</span>
<# } #>

<ul class="proton-opt-radio-list">

	<# _.each( data.choices, function( label, choice ) { #>

		<li>
			<label>
				<input type="radio" value="{{ choice }}" name="{{ data.field_name }}" <# if ( data.value === choice ) { #> checked="checked" <# } #> />
				{{ label }}
			</label>
		</li>

	<# } ) #>

</ul>
