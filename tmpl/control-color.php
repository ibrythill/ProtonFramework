<label>
	<# if ( data.label ) { #>
		<span class="proton-opt-label">{{ data.label }}</span>
	<# } #>

	<# if ( data.description ) { #>
		<span class="proton-opt-description">{{{ data.description }}}</span>
	<# } #>

	<input {{{ data.attr }}} value="<# if ( data.value ) { #>#{{ data.value }}<# } #>" />
</label>
