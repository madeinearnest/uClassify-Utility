<?php
//error_reporting(E_ALL);
//ini_set('display_errors','On');

// which classifier are we using (currently only accepts 'topics')
$classifier = $_GET['clsfr'];

// the url to classify
$url = $_GET['url'];

// initialize json variable to empty for error checking
$json = '';

// instantiate or uClassify wrapper class
$uc = new uclassify();

// set the classifier
$err = $uc->setClassifier($classifier);

// if no errors, classify the url and return json to visualize
if(!$err){
	$json = $uc->classifyUrl($url);
}

function __autoload($class_name) {
	include $class_name . '.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	
	<title><?php echo ucfirst($classifier);?> Classification Results</title>
	
	<!-- meta tags -->
	<meta name="keywords" content="uClassify, classifier, classification, topics, text">
	<meta name="description" content="uCLassify classification results for user supplied uri">
	
	<!-- g.raphael javascript charting library -->
	<script src="./scripts/raphael-min.js"></script>
	<script src="./scripts/g.raphael-min.js"></script>
	<script src="./scripts/g.pie-min.js"></script>
	
	<style>
		body {font-family: Arial, Helvetica, sans-serif;}
	</style>
	
</head>
<body>
	<header id="main">
		<h1><?php echo ucfirst($classifier);?> Classification Results</h1>
	</header>
	
	<section id="content">
	
	<?php if(!empty($json)){?>
	
	<section><p>URL: <a href="<?php echo $url; ?>"><?php echo $url; ?></a></p></section>
	
	<section>
	<script>
		var data = <?php echo $json; ?>;

		// Creates canvas 540 � 480 at 0, 0
		var r = Raphael(0, 75, 640, 480);
		// Creates pie chart at with center at 320, 200,
		// radius 100 and data from the classification
		
		var set = new Array();
		var legend = new Array();
		var i = 0;
		for(var key in data.cls1){
			legend[i] = key+': '+Math.round((data.cls1[key]*100)*100)/100+'%';
			set[i] = data.cls1[key];
			i++;
		}
		
		var pie = r.g.piechart(320, 150, 70, set, {legend: legend, legendpos: "west"});

		pie.hover(function () {
            this.sector.stop();
            this.sector.scale(1.1, 1.1, this.cx, this.cy);
            if (this.label) {
                this.label[0].stop();
                this.label[0].scale(1.5);
                this.label[1].attr({"font-weight": 800});
            }
        }, function () {
            this.sector.animate({scale: [1, 1, this.cx, this.cy]}, 500, "bounce");
            if (this.label) {
                this.label[0].animate({scale: 1}, 500, "bounce");
                this.label[1].attr({"font-weight": 400});
            }
        });
	</script>
	</section>
	
	<?php } ?>
	</section>
</body>
</html>