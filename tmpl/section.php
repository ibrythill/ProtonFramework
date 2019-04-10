
	<div class="proton-opt-meta">
		<# if ( data.label ) { #>
			<h2><i id="{{ data.icon }}-{{ data.name }}-meta" class="livicon shadowed" data-s="18" data-n="{{ data.icon }}" data-c="#fff" data-hc="#fff" style="width: 18px; height: 18px;"></i>{{ data.label }}</h2>
		<# } #>
		<# if ( data.description ) { #>
			<span class="proton-opt-description description">{{{ data.description }}}</span>
		<# } #>
	</div>


