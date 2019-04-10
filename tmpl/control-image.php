<# if ( data.label ) { #>
	<span class="proton-opt-label">{{ data.label }}</span>
<# } #>

<# if ( data.description ) { #>
	<span class="proton-opt-description">{{{ data.description }}}</span>
<# } #>

<input type="hidden" class="proton-opt-attachment-id" name="{{ data.field_name }}" value="{{ data.value }}" />
<div class="proton-opt-upload">
<# if ( data.src ) { #>
	<img class="proton-opt-img" src="{{ data.src }}" alt="{{ data.alt }}" />
<# } else { #>
	<div class="proton-opt-placeholder">{{ data.l10n.placeholder }}</div>
<# } #>
</div>
<p>
	<# if ( data.src ) { #>
		<button type="button" class="button button-secondary proton-opt-change-media pobtn"><i class="livicon shadowed" data-s="16" data-n="image" data-c="#fff" data-hc="#fff" style="width: 16px; height: 16px;"></i>{{ data.l10n.change }}</button>
		<button type="button" class="button button-secondary proton-opt-remove-media pobtn"><i class="livicon shadowed" data-s="16" data-n="remove-alt" data-c="#fff" data-hc="#fff" style="width: 16px; height: 16px;"></i>{{ data.l10n.remove }}</button>
	<# } else { #>
		<button type="button" class="button button-secondary proton-opt-add-media pobtn"><i class="livicon shadowed" data-s="16" data-n="image" data-c="#fff" data-hc="#fff" style="width: 16px; height: 16px;"></i>{{ data.l10n.upload }}</button>
	<# } #>
</p>
