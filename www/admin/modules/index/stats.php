<h4>Trafic web</h4>

<?php
$start = date('Y-m-01');
$end = date('Y-m-' . date('t', strtotime($start)));
if(isset($_GET['start']))
{
	$start = $_GET['start'];
}
if(isset($_GET['end']))
{
	$start = $_GET['end'];
}

require ROOT_PATH . 'core/classes/class.analytics.php';
$analytics = new analytics('contact@adveris.fr', 'hoche2002');

$analytics->setProfileById('ga:13676027');
$analytics->setDateRange($start, $end);

$visits = $analytics->getVisitors();

// build tables
if(count($visits))
{
	foreach($visits as $day => $visit)
	{ 
		// petite correction pour éviter une erreur javascript sur les nombres précédés d'un 0
		$day_label = $day;
		if($day[0] == '0') { $day_label = $day[1]; }

		$flot_datas_visits[] = '['.$day_label.','.$visit.']';
	}
	$flot_data_visits = '['.implode(',',$flot_datas_visits).']';
}
?>

<script type="text/javascript">
function showTooltip(x, y, contents) {
	$('<div id="tooltip">' + contents + '</div>').css( {
		position: 'absolute',
		display: 'none',
		top: y - 15,
		left: x + 10,
		border: '1px solid #fdd',
		padding: '2px',
		'background-color': '#fee',
		opacity: 0.80
	}).appendTo("body").fadeIn(200);
}

$(document).ready(function() {
	var visits = <?php echo $flot_data_visits; ?>;

	$.plot($('#placeholder-1'),[
	{ label: 'Visites', data: visits }
	],{
		lines: { show: true },
		points: { show: true },
		grid: { backgroundColor: '#fffaff', borderWidth: 1, hoverable: true },
		colors: ['#EDC240']
	});
	
	$("#placeholder-1").bind("plothover", function (event, pos, item) {
	
		
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));

        
            if (item) {
                if (previousPoint != item.dataIndex) {
                    previousPoint = item.dataIndex;
                    
                    $("#tooltip").remove();
                    var x = item.datapoint[0].toFixed(2),
                        y = item.datapoint[1].toFixed(2);
                    
                    showTooltip(item.pageX, item.pageY,
                                item.series.label + " du " + x.substr(0, x.length - 3) + " : " + y.substr(0, y.length - 3));
                }
            }
            else {
                $("#tooltip").remove();
                previousPoint = null;            
            }
        
    });
});
</script>

<div id="placeholder-1" style="height:200px;"></div>

<br class="clear-both" />