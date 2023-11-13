
am4core.useTheme(am4themes_animated);

var chartMin = 0;
var chartMax = 180;

var reverseCheck = false;

var ax = {
    lontano: document.getElementById("Gauge1_l"),
    medio: document.getElementById("Gauge1_m"),
    vicino: document.getElementById("Gauge1_v"),
}

var data = {
    score: getDominantVal(),
    gradingData: [
        {
            title: " ",
            advice: "",
            color: "#000",
            lowScore: -100000,
            highScore: 500000
        },

    ]
};

/**
 Grading Lookup
 */
function lookUpGrade(lookupScore, grades) {
    // Only change code below this line
    for (var i = 0; i < grades.length; i++) {
        if (
            grades[i].lowScore < lookupScore &&
            grades[i].highScore >= lookupScore
        ) {
            return grades[i];
        }
    }
    return null;
}

// create chart
var chart = am4core.create("chartdiv", am4charts.GaugeChart);
chart.hiddenState.properties.opacity = 0;
chart.fontSize = 11;
chart.minHeight = 195
chart.minWidth = 195
chart.innerRadius = am4core.percent(90);
chart.resizable = true;

/**
 Grade and Target above the gauge
 */
var topContainer = chart.chartContainer.createChild(am4core.Container);
topContainer.layout = "absolute";
topContainer.toBack();
topContainer.width = am4core.percent(100);

// GRADE
var leftTopContainer = topContainer.createChild(am4core.Container);
leftTopContainer.layout = "vertical";

var gradeLabel = leftTopContainer.createChild(am4core.Label);
gradeLabel.text = "Grade";
gradeLabel.fill = am4core.color("#757575");
gradeLabel.fontSize = "0";
gradeLabel.fontWeight = 0;
gradeLabel.align = "left";
var gradeValue = leftTopContainer.createChild(am4core.Label);
gradeValue.text = "B";
gradeValue.tooltipText = "Grade is calculated from Metric normalized value.";
gradeValue.tooltip.pointerOrientation = "left";
gradeValue.tooltip.dx = 12;

gradeValue.fontSize = "";
gradeValue.fontWeight = 0;
gradeValue.align = "left";
gradeValue.opacity = 0;
gradeValue.background = new am4core.RoundedRectangle();
gradeValue.background.cornerRadius(4, 4, 4, 4);
gradeValue.padding(8, 12, 8, 12);
//A=4BA45E, B=B0CE55, C=E9FE67, D=FEFF55, E=F5CD45, F=EC6F2F, G=E93224
gradeValue.background.fill = am4core.color("#fff");

// TARGET
var rightTopContainer = topContainer.createChild(am4core.Container);
rightTopContainer.layout = "vertical";
rightTopContainer.align = "right";

var targetLabel = rightTopContainer.createChild(am4core.Label);
targetLabel.text = "";
targetLabel.fill = am4core.color("#757575");
targetLabel.fontSize = "0";
targetLabel.fontWeight = 0;
targetLabel.align = "right";
var targetValue = rightTopContainer.createChild(am4core.Label);
targetValue.text = "";
targetValue.fontSize = "";
targetValue.fontWeight = 0;
targetValue.align = "right";

/**
 * Normal axis
 */
var axis = chart.xAxes.push(new am4charts.ValueAxis());
axis.min = chartMin;
axis.max = chartMax;
axis.strictMinMax = true;
axis.renderer.radius = am4core.percent(70);
axis.renderer.inside = true;
axis.renderer.line.strokeOpacity = 0;
axis.renderer.ticks.template.disabled = false;
axis.renderer.ticks.template.strokeOpacity = 0;
axis.renderer.ticks.template.strokeWidth = 0.5;
axis.renderer.ticks.template.length = 5;
axis.renderer.grid.template.disabled = true;
axis.renderer.labels.template.radius = am4core.percent(15);
axis.renderer.labels.template.fontSize = "0.9em";
axis.renderer.labels.template.fill = am4core.color("#757575");
// axis.renderer.inversed = true;
/**
 * Axis for ranges
 */
var axis2 = chart.xAxes.push(new am4charts.ValueAxis());
axis2.min = chartMin;
axis2.max = chartMax;
axis2.renderer.radius = am4core.percent(110); // figure out how to move labels instead
axis2.strictMinMax = true;
axis2.renderer.labels.template.disabled = true;
axis2.renderer.ticks.template.disabled = true;
axis2.renderer.grid.template.disabled = false;
axis2.renderer.grid.template.opacity = 0;
axis2.renderer.labels.template.bent = true;
axis2.renderer.labels.template.fill = am4core.color("#000");
axis2.renderer.labels.template.fontWeight = "bold";
axis2.renderer.labels.template.fillOpacity = 0; //hide
axis2.numberFormatter.numberFormat = "#a";
// axis2.renderer.inversed = true;

/**
 Ranges
 */

for (let grading of data.gradingData) {
    var range = axis2.axisRanges.create();
    range.axisFill.fill = am4core.color(grading.color);

    range.axisFill.fillOpacity = 1;

    range.axisFill.zIndex = -1;
    range.value = grading.lowScore > chartMin ? grading.lowScore : chartMin;
    range.endValue = grading.highScore < chartMax ? grading.highScore : chartMax;
    range.grid.strokeOpacity = 0;
    range.stroke = am4core.color(grading.color).lighten(-0.1);
    range.label.inside = true;
    range.label.text = grading.title.toUpperCase();
    range.label.inside = true;
    range.label.location = 0.5;
    range.label.inside = true;
    range.label.radius = am4core.percent(10);
    range.label.paddingBottom = -5; // ~half font size
    range.label.fontSize = "0.9em";
}

var matchingGrade = lookUpGrade(data.score, data.gradingData);

/**
 * Metric Value
 */

var labelMetricValue = chart.radarContainer.createChild(am4core.Label);
labelMetricValue.isMeasured = false;
labelMetricValue.fontSize = "1.5em";
labelMetricValue.x = am4core.percent(50);
labelMetricValue.paddingBottom = 15;
labelMetricValue.horizontalCenter = "middle";
labelMetricValue.verticalCenter = "bottom";
//labelMetricValue.dataItem = data;
labelMetricValue.text = data.score.toFixed(1);
//labelMetricValue.text = "{score}";
labelMetricValue.fill = am4core.color(matchingGrade.color);

/**
 * Advice
 */

//var label2 = chart.radarContainer.createChild(am4core.Label);
var labelAdvice = chart.createChild(am4core.Label);
labelAdvice.isMeasured = false;
labelAdvice.fontSize = "5em";
//labelAdvice.paddingTop = 150;
labelAdvice.horizontalCenter = "middle";
labelAdvice.verticalCenter = "bottom";
//labelAdvice.text = matchingGrade.title.toUpperCase();
labelAdvice.text = "";
labelAdvice.fill = am4core.color(matchingGrade.color);
labelAdvice.dx = 280;
labelAdvice.dy = 340;

/**
 * Hand
 */
var hand = chart.hands.push(new am4charts.ClockHand());
hand.axis = axis2;
hand.radius = am4core.percent(85);
hand.innerRadius = am4core.percent(50);
hand.startWidth = 10;
hand.pixelHeight = 10;
hand.pin.disabled = true;
hand.value = data.score;
hand.fill = am4core.color("#444");
hand.stroke = am4core.color("#000");

var handTarget = chart.hands.push(new am4charts.ClockHand());
handTarget.axis = axis2;
handTarget.radius = am4core.percent(100);
handTarget.innerRadius = am4core.percent(105);
handTarget.fill = axis2.renderer.line.stroke;
handTarget.stroke = axis2.renderer.line.stroke;
handTarget.pin.disabled = true;
handTarget.pin.radius = 10;
handTarget.startWidth = 10;
handTarget.fill = am4core.color("#fff");
handTarget.stroke = am4core.color("#fff");

hand.events.on("positionchanged", function(){
    var t = axis2.positionToValue(hand.currentPosition).toFixed(0);
    var formattedValue = chart.numberFormatter.format(t, "#.#a");
    labelMetricValue.text = formattedValue;

    var value2 = axis.positionToValue(hand.currentPosition);
    var matchingGrade = lookUpGrade(axis.positionToValue(hand.currentPosition), data.gradingData);
    labelAdvice.text = matchingGrade.advice.toUpperCase();
    labelAdvice.fill = am4core.color(matchingGrade.color);
    labelAdvice.stroke = am4core.color(matchingGrade.color);
    labelMetricValue.fill = am4core.color(matchingGrade.color);
})

function updateGauge() {
    var value = getDominantVal()

    if (value < chartMin) {
        value = chartMin;
    } else if (value > chartMax) {
        value = chartMax;
    }
    
    hand.showValue(value, 1000, am4core.ease.cubicOut);
}

function getDominantVal(){
    var val = 0;
    if (ax.lontano.value != 0){
        val = Number(ax.lontano.value)
    } else if (ax.medio.value != 0){
        val = Number(ax.medio.value)
    } else if(ax.vicino.value != 0){
        val = Number(ax.vicino.value)
    }
    
    if(reverseCheck){
        return Number(Math.abs(val - 180))
    } else {
        return val
    }

}

function reverse(cb){
    if (cb.id == "reverse1"){
        reverseCheck=cb.checked;
        updateGauge();
        // axis.renderer.inverse = true;
    
        // sleep(500);
        chart.deepInvalidate();
        
        //Set to inverse based on switch
        axis.renderer.inverse = reverseCheck;
        axis2.renderer.inverse = reverseCheck;
        chart.xAxes.getIndex(0).renderer.inversed = reverseCheck;
        chart.xAxes.getIndex(1).renderer.inversed = reverseCheck;
        
        
        //Re-render
        chart.reinit();
    
        // labelMetricValue.text = data.score.toFixed(1);
        // labelMetricValue.invalidate();
        // labelMetricValue.reinit();
        
        if (chart.isReady()){
            setTimeout(updateGauge(), 5000);
        }
    
    }
}