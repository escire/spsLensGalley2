<?php
	$base_url = $_GET["b"];
	$rev = $_GET["r"];
	$galId = $_GET["galId"];
	$ref = $_GET["ref"];
	$issue = $_GET["issue"];
	$art = $_GET["art"];

	if(!is_numeric($galId)){
		echo "Oops!, ha ocurrido un error";
		return;
	}

	$base_url = filter_var($base_url, FILTER_SANITIZE_STRING);
	$rev = filter_var($rev, FILTER_SANITIZE_STRING);
	$galId = filter_var($galId, FILTER_SANITIZE_STRING);
	$ref = filter_var($ref, FILTER_SANITIZE_STRING);
?>
<!DOCTYPE html>
<html xmlns:mml="http://www.w3.org/1998/Math/MathML">
<head>
	<title>SPS Lens Galley</title>
	<link href='//fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,400italic,600italic' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" media="all" href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" />
	<link href='<?php echo base64_decode($base_url) ?>/plugins/generic/spsLensGalley/lib/lens.css?v=20201217' rel='stylesheet' type='text/css'/>

	<script src="<?php echo base64_decode($base_url) ?>/plugins/generic/spsLensGalley/lib/jquery.min.js"></script>
	<script src='<?php echo base64_decode($base_url) ?>/plugins/generic/spsLensGalley/lib/lens.js?v=20201217'></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.7/latest.js?config=TeX-AMS-MML_HTMLorMML"></script>

	<script type="text/javascript">
		var ojs_gal_url = "<?php echo base64_decode($base_url) . "/index.php/" . $rev . "/gateway/plugin/LensGatewayPluginImage/" . $art . "/"; ?>";
		var ojs_issue = "<?php echo $issue; ?>";
		// MathJax Configuration
		MathJax = {
			svg: {
				  scale: 1,                      // global scaling factor for all expressions
				  minScale: .5,                  // smallest scaling factor to use
				  matchFontHeight: true,         // true to match ex-height of surrounding font
				  mtextInheritFont: false,       // true to make mtext elements use surrounding font
				  merrorInheritFont: true,       // true to make merror text use surrounding font
				  mathmlSpacing: false,          // true for MathML spacing rules, false for TeX rules
				  skipAttributes: {},            // RFDa and other attributes NOT to copy to the output
				  exFactor: .5,                  // default size of ex in em units
				  displayAlign: 'center',        // default for indentalign when set to 'auto'
				  displayIndent: '0',            // default for indentshift when set to 'auto'
				  fontCache: 'local',            // or 'global' or 'none'
				  localID: null,                 // ID to use for local font cache (for single equation processing)
				  internalSpeechTitles: true,    // insert <title> tags with speech content
				  titleID: 0                     // initial id number to use for aria-labeledby titles
			},
			menuSettings:{
				zoom: "Click"
			}
		};

		var qs = function () {
			var query_string = {};
			var query = window.location.search.substring(1);
			var vars = query.split("&");
			for (var i=0;i<vars.length;i++) {
				var pair = vars[i].split("=");
				if (typeof query_string[pair[0]] === "undefined") {
					query_string[pair[0]] = pair[1];
				} else if (typeof query_string[pair[0]] === "string") {
					var arr = [ query_string[pair[0]], pair[1] ];
					query_string[pair[0]] = arr;
				} else {
					query_string[pair[0]].push(pair[1]);
				}
			}
			return query_string;
		} ();

		var documentURL = "<?php echo base64_decode($base_url); ?>/index.php/<?php echo $rev;?>/gateway/plugin/LensGatewayPlugin/<?php echo $galId; ?>";
		$(function() {
			var app = new window.Lens({
				document_url: qs.url ? decodeURIComponent(qs.url) : documentURL
			});

			app.start();
			window.app = app;
		});

		$( document ).ajaxComplete(function() {
			$("body").append('<a href="<?php echo base64_decode($ref); ?>" class="go-back"><i class="fa fa-arrow-left"></i> <span>Regresar</span></a>');
		});
	</script>

</head>
<body class="loading">
	<h1></h1>
</body>
</html>
