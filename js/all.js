
function createGraph(input) {
    var keys = Object.keys(input);

    for(var k = 0; k < keys.length; k++) {
	dat = input[keys[k]];
	// find max value
	var max = 0;
	var datKeys = Object.keys(dat);
	for (var i = 0; i < datKeys.length; i++) {
	    if (max < dat[datKeys[i]])
		max = dat[datKeys[i]];
	}
	
	jQuery('#graph').append("<center><h4>" + keys[k] + "</h4><div class=\"timeline\" id=\"key-" + keys[k] + "\"></div></center>");

	// create function with its own scope to keep data current
	(function(dat) {
	
	var width = 960,
	    height = 136,
	    cellSize = 17;
	
	var formatPercent = d3.format(",");

	var cm = colorbrewer.Spectral[11].slice();
	var color = d3.scaleQuantize()
            .domain([0, max])
	    .range(cm.reverse()) // colorbrewer.Spectral[11])
            //.range(["#a50026", "#d73027", "#f46d43", "#fdae61", "#fee08b", "#ffffbf", "#d9ef8b", "#a6d96a", "#66bd63", "#1a9850", "#006837"]);

	var svg = d3.select("#key-"+keys[k])
	    .selectAll("svg")
	    .data(d3.range(2017, 2018))
	    .enter().append("svg")
            .attr("width", width)
            .attr("height", height)
	    .append("g")
            .attr("transform", "translate(" + ((width - cellSize * 53) / 2) + "," + (height - cellSize * 7 - 1) + ")");
	
	svg.append("text")
            .attr("transform", "translate(-6," + cellSize * 3.5 + ")rotate(-90)")
            .attr("font-family", "sans-serif")
            .attr("font-size", 10)
            .attr("text-anchor", "middle")
            .text(function(d) { return d; });
	
	var rect = svg.append("g")
            .attr("fill", "none")
            .attr("stroke", "#ccc")
	    .selectAll("rect")
	    .data(function(d) { return d3.timeDays(new Date(d, 0, 1), new Date(d + 1, 0, 1)); })
	    .enter().append("rect")
            .attr("width", cellSize)
            .attr("height", cellSize)
            .attr("x", function(d) { return d3.timeWeek.count(d3.timeYear(d), d) * cellSize; })
            .attr("y", function(d) { return d.getDay() * cellSize; })
            .datum(d3.timeFormat("%Y-%m-%d"));
	
	svg.append("g")
            .attr("fill", "none")
            .attr("stroke", "#000")
	    .selectAll("path")
	    .data(function(d) { return d3.timeMonths(new Date(d, 0, 1), new Date(d + 1, 0, 1)); })
	    .enter().append("path")
            .attr("d", pathMonth);

	month = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']
	var legend = svg.selectAll(".legend")
	    .data(month)
	    .enter().append("g")
	    .attr("class", "legend")
	    .attr("transform", function(d, i) { return "translate(" + (((i+1) * 73)-20) + ",0)"; });
	
	legend.append("text")
	    .attr("class", function(d,i){ return month[i] })
	    .style("text-anchor", "end")
	    .attr("dy", "-.25em")
	    .text(function(d,i){ return month[i] });

	//  Tooltip Object
	var tooltip = d3.select("body")
	    .append("div").attr("id", "tooltip")
	    .style("position", "absolute")
	    .style("z-index", "10")
	    .style("visibility", "hidden")
	    .text("a simple tooltip");
		
	//d3.csv("dji.csv", function(error, csv) {
	// data is in dat, convert to array
	var datesAr = Object.keys(dat);
	dayData = [];
	for ( i = 0; i < datesAr.length; i++) {
	    dayData.push( { "Date": datesAr[i], "value": dat[datesAr[i]] } );
	}
	
	//if (error) throw error;
	
	var data = d3.nest()
	    .key(function(d) { return d.Date; })
	    .rollup(function(d) { return (d[0].value ); })
	    .object(dayData);
	
	rect.filter(function(d) { return d in data; })
	    .attr("fill", function(d) { return color(data[d]); });
	    //.append("title")
	    //.text(function(d) { return d + ": " + formatPercent(data[d]); });
	
	
	rect.on("mouseover", mouseover);
	rect.on("mouseout", mouseout);
	function mouseover(d) {
	    tooltip.style("visibility", "visible");
	    var percent_data = (data[d] !== undefined) ? formatPercent(data[d]) : formatPercent(0);
	    var purchase_text = d + ": " + percent_data;

	    if (typeof data[d] !== 'undefined') {
		console.log('stop here');
	    }
	    
	    tooltip.transition()
	        .duration(200)
	        .style("opacity", .9);
	    tooltip.html(purchase_text)
	        .style("left", (d3.event.pageX)+30 + "px")
	        .style("top", (d3.event.pageY) + "px");
	}
	function mouseout (d) {
	    tooltip.transition()
	        .duration(500)
	        .style("opacity", 0);
	    var $tooltip = $("#tooltip");
	    $tooltip.empty();
	}
	
	
	// });
	
	function pathMonth(t0) {
	    var t1 = new Date(t0.getFullYear(), t0.getMonth() + 1, 0),
		d0 = t0.getDay(), w0 = d3.timeWeek.count(d3.timeYear(t0), t0),
		d1 = t1.getDay(), w1 = d3.timeWeek.count(d3.timeYear(t1), t1);
	    return "M" + (w0 + 1) * cellSize + "," + d0 * cellSize
		+ "H" + w0 * cellSize + "V" + 7 * cellSize
		+ "H" + w1 * cellSize + "V" + (d1 + 1) * cellSize
		+ "H" + (w1 + 1) * cellSize + "V" + 0
		+ "H" + (w0 + 1) * cellSize + "Z";
	}
	})(dat);
    }
    
}

jQuery(document).ready(function() {
    jQuery.getJSON('getData.php', { 'action': 'get' }, function(data) {
	createGraph(data);
    });
});
