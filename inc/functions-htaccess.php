<?php


/**
	 * Inserts an array of strings into a file (.htaccess ), placing it between
	 * BEGIN and END markers. Replaces existing marked info. Retains surrounding
	 * data. Creates file if none exists.
	 *
	 * @param array|string $insertion
	 * @return bool True on write success, false on failure.
	 */
	function pro_addHtaccess($insertion, $marker)
	{
	    $htaccess_file = ABSPATH.'.htaccess';
	    return insert_with_markers($htaccess_file, $marker, (array) $insertion);
	}

	function _custom_htaccess($rules) {
	$hurl = parse_url(home_url());
	$hurl = $hurl['host'];

	$insertion = array(

	'# ----------------------------------------------------------------------',
	'# | Cross-origin images                                                |',
	'# ----------------------------------------------------------------------',

	'# Send the CORS header for images when browsers request it.',
	'#',
	'# https://developer.mozilla.org/en-US/docs/Web/HTML/CORS_enabled_image',
	'# https://blog.chromium.org/2011/07/using-cross-domain-images-in-webgl-and.html',

	'<IfModule mod_setenvif.c>',
	'    <IfModule mod_headers.c>',
	'        <FilesMatch "\.(bmp|cur|gif|ico|jpe?g|png|svgz?|webp)$">',
	'            SetEnvIf Origin ":" IS_CORS',
	'            Header set Access-Control-Allow-Origin "*" env=IS_CORS',
	'        </FilesMatch>',
	'    </IfModule>',
	'</IfModule>',

	'# ----------------------------------------------------------------------',
	'# | Cross-origin web fonts                                             |',
	'# ----------------------------------------------------------------------',

	'# Allow cross-origin access to web fonts.',

	'<IfModule mod_headers.c>',
	'    <FilesMatch "\.(eot|otf|tt[cf]|woff2?)$">',
	'        Header set Access-Control-Allow-Origin "*"',
	'    </FilesMatch>',
	'</IfModule>',

	'# ######################################################################',
	'# # INTERNET EXPLORER                                                  #',
	'# ######################################################################',
	'																		 ',
	'# ----------------------------------------------------------------------',
	'# | Document modes                                                     |',
	'# ----------------------------------------------------------------------',
	'																		 ',
	'# Force Internet Explorer 8/9/10 to render pages in the highest mode	 ',
	'# available in the various cases when it may not.						 ',
	'#																		 ',
	'# https://hsivonen.fi/doctype/#ie8										 ',
	'#																		 ',
	'# (!) Starting with Internet Explorer 11, document modes are deprecated.',
	'# If your business still relies on older web apps and services that were',
	'# designed for older versions of Internet Explorer, you might want to	 ',
	'# consider enabling `Enterprise Mode` throughout your company.			 ',
	'#																		 ',
	'# https://msdn.microsoft.com/en-us/library/ie/bg182625.aspx#docmode	 ',
	'# http://blogs.msdn.com/b/ie/archive/2014/04/02/stay-up-to-date-with-enterprise-mode-for-internet-explorer-11.aspx',
	'																		 ',
	'<IfModule mod_headers.c>',
	'																		 ',
	'    Header set X-UA-Compatible "IE=edge"',
	'																		 ',
	'    # mod_headers cannot match based on the content-type, however,	 ',
	'    # the `X-UA-Compatible` response header should be send only for	 ',
	'    # HTML documents and not for the other resources.					 ',
	'																		 ',
	'    <FilesMatch "\.(appcache|atom|bbaw|bmp|crx|css|cur|eot|f4[abpv]|flv|geojson|gif|htc|ico|jpe?g|js|json(ld)?|m4[av]|manifest|map|mp4|oex|og[agv]|opus|otf|pdf|png|rdf|rss|safariextz|svgz?|swf|topojson|tt[cf]|txt|vcard|vcf|vtt|webapp|web[mp]|webmanifest|woff2?|xloc|xml|xpi)$">',
	'        Header unset X-UA-Compatible',
	'    </FilesMatch>',
	'																		 ',
	'</IfModule>',

	'# ----------------------------------------------------------------------',
	'# | Media types                                                        |',
	'# ----------------------------------------------------------------------',
	'	',
	'# Serve resources with the proper media types (f.k.a. MIME types).',
	'#',
	'# https://www.iana.org/assignments/media-types/media-types.xhtml',
	'# https://httpd.apache.org/docs/current/mod/mod_mime.html#addtype',
	' ',
	'<IfModule mod_mime.c>',

	'  # Data interchange',

	'    AddType application/atom+xml                        atom',
	'    AddType application/json                            json map topojson',
	'    AddType application/ld+json                         jsonld',
	'    AddType application/rss+xml                         rss',
	'    AddType application/vnd.geo+json                    geojson',
	'    AddType application/xml                             rdf xml',


	'  # JavaScript',

	'    # Normalize to standard type.',
	'    # https://tools.ietf.org/html/rfc4329#section-7.2',

	'    AddType application/javascript                      js',


	'  # Manifest files',

	'    AddType application/manifest+json                   webmanifest',
	'    AddType application/x-web-app-manifest+json         webapp',
	'    AddType text/cache-manifest                         appcache',


	'  # Media files',

	'    AddType audio/mp4                                   f4a f4b m4a',
	'    AddType audio/ogg                                   oga ogg opus',
	'    AddType image/bmp                                   bmp',
	'    AddType image/svg+xml                               svg svgz',
	'    AddType image/webp                                  webp',
	'    AddType video/mp4                                   f4v f4p m4v mp4',
	'    AddType video/ogg                                   ogv',
	'    AddType video/webm                                  webm',
	'    AddType video/x-flv                                 flv',

	'    # Serving `.ico` image files with a different media type',
	'    # prevents Internet Explorer from displaying then as images:',
	'    # https://github.com/h5bp/html5-boilerplate/commit/37b5fec090d00f38de64b591bcddcb205aadf8ee',

	'    AddType image/x-icon                                cur ico',


	'  # Web fonts',

	'    AddType application/font-woff                       woff',
	'    AddType application/font-woff2                      woff2',
	'    AddType application/vnd.ms-fontobject               eot',

	'    # Browsers usually ignore the font media types and simply sniff',
	'    # the bytes to figure out the font type.',
	'    # https://mimesniff.spec.whatwg.org/#matching-a-font-type-pattern',
	'    #',
	'    # However, Blink and WebKit based browsers will show a warning',
	'    # in the console if the following font types are served with any',
	'    # other media types.',

	'    AddType application/x-font-ttf                      ttc ttf',
	'    AddType font/opentype                               otf',


	'  # Other',

	'    AddType application/octet-stream                    safariextz',
	'    AddType application/x-bb-appworld                   bbaw',
	'    AddType application/x-chrome-extension              crx',
	'    AddType application/x-opera-extension               oex',
	'    AddType application/x-xpinstall                     xpi',
	'    AddType text/vcard                                  vcard vcf',
	'    AddType text/vnd.rim.location.xloc                  xloc',
	'    AddType text/vtt                                    vtt',
	'    AddType text/x-component                            htc',

	'</IfModule>',

	'# ######################################################################',
	'# # SECURITY                                                           #',
	'# ######################################################################',

	'# ----------------------------------------------------------------------',
	'# | File access                                                        |',
	'# ----------------------------------------------------------------------',
	'																		 ',
	'# Block access to directories without a default document.				 ',
	'#																		 ',
	'# You should leave the following uncommented, as you shouldnt allow	 ',
	'# anyone to surf through every directory on your server (which may		 ',
	'# includes rather private places such as the CMSs directories).		 ',
	'																		 ',
	'<IfModule mod_autoindex.c>',
	'    Options -Indexes',
	'</IfModule>',
	'																		 ',
	'# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ',
	'																		 ',
	'# Block access to all hidden files and directories with the exception of',
	'# the visible content from within the `/.well-known/` hidden directory. ',
	'#																		 ',
	'# These types of files usually contain user preferences or the preserved',
	'# state of an utility, and can include rather private places like, for	 ',
	'# example, the `.git` or `.svn` directories.							 ',
	'#																		 ',
	'# The `/.well-known/` directory represents the standard (RFC 5785) path ',
	'# prefix for "well-known locations" (e.g.: `/.well-known/manifest.json`,',
	'# `/.well-known/keybase.txt`), and therefore, access to its visible	 ',
	'# content should not be blocked.										 ',
	'#																		 ',
	'# https://www.mnot.net/blog/2010/04/07/well-known						 ',
	'# https://tools.ietf.org/html/rfc5785									 ',
	'																		 ',
	'<IfModule mod_rewrite.c>',
	'    RewriteEngine On',
	'    RewriteCond %{REQUEST_URI} "!(^|/)\.well-known/([^./]+./?)+$" [NC]',
	'    RewriteCond %{SCRIPT_FILENAME} -d [OR]',
	'    RewriteCond %{SCRIPT_FILENAME} -f',
	'    RewriteRule "(^|/)\." - [F]',
	'</IfModule>',
	'																		 ',
	'# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ',
	'																		 ',
	'# Block access to files that can expose sensitive information.			 ',
	'#																		 ',
	'# By default, block access to backup and source files that may be		 ',
	'# left by some text editors and can pose a security risk when anyone	 ',
	'# has access to them.													 ',
	'#																		 ',
	'# http://feross.org/cmsploit/											 ',
	'#																		 ',
	'# (!) Update the `<FilesMatch>` regular expression from below to		 ',
	'# include any files that might end up on your production server and	 ',
	'# can expose sensitive information about your website. These files may	 ',
	'# include: configuration files, files that contain metadata about the	 ',
	'# project (e.g.: project dependencies), build scripts, etc..			 ',
	'																		 ',
	'<FilesMatch "(^#.*#|\.(bak|conf|dist|fla|in[ci]|log|psd|sh|sql|sw[op])|~)$">',
	'																		 ',
	'    # Apache < 2.3',
	'    <IfModule !mod_authz_core.c>',
	'        Order allow,deny',
	'        Deny from all',
	'        Satisfy All',
	'    </IfModule>',
	'																		 ',
	'    # Apache � 2.3',
	'    <IfModule mod_authz_core.c>',
	'        Require all denied',
	'    </IfModule>',
	'																		 ',
	'</FilesMatch>',


	'# disable directory browsing',
	'Options All -Indexes',

	'<files wp-config.php>',
	'	order allow,deny',
	'	deny from all',
	'</files>',

	'<files xmlrpc.php>',
	'	order deny,allow',
	'	deny from all',
	'</files>',


	'<files ~ "^.*\.([Hh][Tt][Aa])">',
	'	order allow,deny',
	'	deny from all',
	'	satisfy all',
	'</files>',


	'# ----------------------------------------------------------------------',
	'# | Compression                                                        |',
	'# ----------------------------------------------------------------------',

	'<ifModule mod_gzip.c>',
	'mod_gzip_on Yes',
	'mod_gzip_dechunk Yes',
	'mod_gzip_item_include file .(html?|txt|css|js|php|pl)$',
	'mod_gzip_item_include handler ^cgi-script$',
	'mod_gzip_item_include mime ^text/.*',
	'mod_gzip_item_include mime ^application/x-javascript.*',
	'mod_gzip_item_exclude mime ^image/.*',
	'mod_gzip_item_exclude rspheader ^Content-Encoding:.*gzip.*',
	'</ifModule>',

	'<IfModule mod_deflate.c>',

	'    # Force compression for mangled `Accept-Encoding` request headers',
	'    # https://developer.yahoo.com/blogs/ydn/pushing-beyond-gzipping-25601.html',

	'    <IfModule mod_setenvif.c>',
	'        <IfModule mod_headers.c>',
	'            SetEnvIfNoCase ^(Accept-EncodXng|X-cept-Encoding|X{15}|~{15}|-{15})$ ^((gzip|deflate)\s*,?\s*)+|[X~-]{4,13}$ HAVE_Accept-Encoding',
	'            RequestHeader append Accept-Encoding "gzip,deflate" env=HAVE_Accept-Encoding',
	'        </IfModule>',
	'    </IfModule>',

	'    # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -',

	'    # Compress all output labeled with one of the following media types.',
	'    #',
	'    # (!) For Apache versions below version 2.3.7 you dont need to',
	'    # enable `mod_filter` and can remove the `<IfModule mod_filter.c>`',
	'    # and `</IfModule>` lines as `AddOutputFilterByType` is still in',
	'    # the core directives.',
	'    #',
	'    # https://httpd.apache.org/docs/current/mod/mod_filter.html#addoutputfilterbytype',

	'    <IfModule mod_filter.c>',
	'        AddOutputFilterByType DEFLATE application/atom+xml \
	                                      application/javascript \
	                                      application/json \
	                                      application/ld+json \
	                                      application/manifest+json \
	                                      application/rdf+xml \
	                                      application/rss+xml \
	                                      application/schema+json \
	                                      application/vnd.geo+json \
	                                      application/vnd.ms-fontobject \
	                                      application/x-font-ttf \
	                                      application/x-javascript \
	                                      application/x-web-app-manifest+json \
	                                      application/xhtml+xml \
	                                      application/xml \
	                                      font/eot \
	                                      font/opentype \
	                                      image/bmp \
	                                      image/svg+xml \
	                                      image/vnd.microsoft.icon \
	                                      image/x-icon \
	                                      text/cache-manifest \
	                                      text/css \
	                                      text/html \
	                                      text/javascript \
	                                      text/plain \
	                                      text/vcard \
	                                      text/vnd.rim.location.xloc \
	                                      text/vtt \
	                                      text/x-component \
	                                      text/x-cross-domain-policy \
	                                      text/xml',

	'    </IfModule>',

	'    # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -',

	'    # Map the following filename extensions to the specified',
	'    # encoding type in order to make Apache serve the file types',
	'    # with the appropriate `Content-Encoding` response header',
	'    # (do note that this will NOT make Apache compress them!).',
	'    #',
	'    # If these files types would be served without an appropriate',
	'    # `Content-Enable` response header, client applications (e.g.:',
	'    # browsers) wouldnt know that they first need to uncompress',
	'    # the response, and thus, wouldnt be able to understand the',
	'    # content.',
	'    #',
	'    # https://httpd.apache.org/docs/current/mod/mod_mime.html#addencoding',

	'    <IfModule mod_mime.c>',
	'        AddEncoding gzip              svgz',
	'    </IfModule>',

	'</IfModule>',


    '#The following lines are to avoid bugs with some browsers',
    'BrowserMatch ^Mozilla/4 gzip-only-text/html',
    'BrowserMatch ^Mozilla/4\.0[678] no-gzip',
    'BrowserMatch \bMSIE !no-gzip !gzip-only-text/html',

	'# ----------------------------------------------------------------------',
	'# | Expires headers                                                    |',
	'# ----------------------------------------------------------------------',

	'# Serve resources with far-future expires headers.',
	'#',
	'# (!) If you dont control versioning with filename-based',
	'# cache busting, you should consider lowering the cache times',
	'# to something like one week.',
	'#',
	'# https://httpd.apache.org/docs/current/mod/mod_expires.html',

	'<IfModule mod_expires.c>',

	'    ExpiresActive on',
	'    ExpiresDefault                                      "access plus 1 month"',

	'  # CSS',

	'    ExpiresByType text/css                              "access plus 1 year"',


	'  # Data interchange',

	'    ExpiresByType application/atom+xml                  "access plus 1 hour"',
	'    ExpiresByType application/rdf+xml                   "access plus 1 hour"',
	'    ExpiresByType application/rss+xml                   "access plus 1 hour"',

	'    ExpiresByType application/json                      "access plus 0 seconds"',
	'    ExpiresByType application/ld+json                   "access plus 0 seconds"',
	'    ExpiresByType application/schema+json               "access plus 0 seconds"',
	'    ExpiresByType application/vnd.geo+json              "access plus 0 seconds"',
	'    ExpiresByType application/xml                       "access plus 0 seconds"',
	'    ExpiresByType text/xml                              "access plus 0 seconds"',


	'  # Favicon (cannot be renamed!) and cursor images',

	'    ExpiresByType image/vnd.microsoft.icon              "access plus 1 week"',
	'    ExpiresByType image/x-icon                          "access plus 1 week"',

	'  # HTML',

	'    ExpiresByType text/html                             "access plus 0 seconds"',


	'  # JavaScript',

	'    ExpiresByType application/javascript                "access plus 1 year"',
	'    ExpiresByType application/x-javascript              "access plus 1 year"',
	'    ExpiresByType text/javascript                       "access plus 1 year"',


	'  # Manifest files',

	'    ExpiresByType application/manifest+json             "access plus 1 week"',
	'    ExpiresByType application/x-web-app-manifest+json   "access plus 0 seconds"',
	'    ExpiresByType text/cache-manifest                   "access plus 0 seconds"',


	'  # Media files',

	'    ExpiresByType audio/ogg                             "access plus 1 month"',
	'    ExpiresByType image/bmp                             "access plus 1 month"',
	'    ExpiresByType image/gif                             "access plus 1 month"',
	'    ExpiresByType image/jpeg                            "access plus 1 month"',
	'    ExpiresByType image/png                             "access plus 1 month"',
	'    ExpiresByType image/svg+xml                         "access plus 1 month"',
	'    ExpiresByType image/webp                            "access plus 1 month"',
	'    ExpiresByType video/mp4                             "access plus 1 month"',
	'    ExpiresByType video/ogg                             "access plus 1 month"',
	'    ExpiresByType video/webm                            "access plus 1 month"',


	'  # Web fonts',

	'    # Embedded OpenType (EOT)',
	'    ExpiresByType application/vnd.ms-fontobject         "access plus 1 month"',
	'    ExpiresByType font/eot                              "access plus 1 month"',

	'    # OpenType',
	'    ExpiresByType font/opentype                         "access plus 1 month"',

	'    # TrueType',
	'    ExpiresByType application/x-font-ttf                "access plus 1 month"',

	'    # Web Open Font Format (WOFF) 1.0',
	'    ExpiresByType application/font-woff                 "access plus 1 month"',
	'    ExpiresByType application/x-font-woff               "access plus 1 month"',
	'    ExpiresByType font/woff                             "access plus 1 month"',

	'    # Web Open Font Format (WOFF) 2.0',
	'    ExpiresByType application/font-woff2                "access plus 1 month"',


	'  # Other',

	'    ExpiresByType text/x-cross-domain-policy            "access plus 1 week"',

	'</IfModule>',

	'<IfModule mod_headers.c>',
	    '<FilesMatch "\.(js|css|xml|gz)$">',
	    '    Header append Vary Accept-Encoding',
	    '</FilesMatch>',
	    '<FilesMatch "\.(ico|jpe?g|png|gif|swf|woff)$">',
	    '    Header set Cache-Control "public"',
	    '</FilesMatch>',
	    '<FilesMatch "\.(css)$">',
	    '    Header set Cache-Control "public"',
	    '</FilesMatch>',
	    '<FilesMatch "\.(js)$">',
	    '    Header set Cache-Control "private"',
	    '</FilesMatch>',
	    '<FilesMatch "\.(x?html?|php)$">',
	    '    Header set Cache-Control "private, must-revalidate"',
	    '</FilesMatch>',
	    '# BEGIN Turn ETags Off',
		'# Inherently misconfigured, especially for server clusters',
		'Header unset ETag',
	'</IfModule>',

	'<IfModule mod_rewrite.c>',
		'# Block the include-only files.',
		'RewriteEngine On',
		'RewriteBase /',
		'RewriteRule ^wp-admin/includes/ - [F,L]',
		'RewriteRule !^wp-includes/ - [S=3]',
		'RewriteRule ^wp-includes/[^/]+\.php$ - [F,L]',
		'RewriteRule ^wp-includes/js/tinymce/langs/.+\.php - [F,L]',
		'RewriteRule ^wp-includes/theme-compat/ - [F,L]',

		'# Protect Your WordPress Blog From Script Injections',
		'Options +FollowSymLinks',
		'RewriteCond %{QUERY_STRING} (<|%3C).*script.*(>|%3E) [NC,OR]',
		'RewriteCond %{QUERY_STRING} GLOBALS(=|[|%[0-9A-Z]{0,2}) [OR]',
		'RewriteCond %{QUERY_STRING} _REQUEST(=|[|%[0-9A-Z]{0,2})',
		'RewriteRule ^(.*)$ index.php [F,L]',

		'#Replace ?mysite.com/ with your blog url',
		'RewriteCond %{HTTP_REFERER} !^http://(.+.)?'.$hurl.'/ [NC]',
		'RewriteCond %{HTTP_REFERER} !^$',
		'#Replace /images/nohotlink.jpg with your "dont hotlink" image url',
		'RewriteRule .*.(jpe?g|gif|bmp|png)$ /images/nohotlink.jpg [L]',


	'</IfModule>',);



	pro_addHtaccess($insertion, 'ProtonFramework');
	return $rules;
}
//add_action("mod_rewrite_rules", "_custom_htaccess", 10, 2);


?>