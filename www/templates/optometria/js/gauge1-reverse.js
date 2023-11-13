
am4core.useTheme(am4themes_animated);

var chartMin = 0;
var chartMax = 180;

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
var chart = am4core.create("chartdiv-reverse", am4charts.GaugeChart);
chart.hiddenState.properties.opacity = 0;
chart.fontSize = 11;
chart.align = "left"
chart.minHeight = 195
chart.minWidth = 195
chart.innerRadius = am4core.percent(90);
// chart.resizable = false;
// chart.autoMargins = true;

/**
 Grade and Target above the gauge
 */
var topContainer = chart.chartContainer.createChild(am4core.Container);
topContainer.layout = "relative";
topContainer.toBack();
// topContainer.height = am4core.percent(100);
// topContainer.width = am4core.percent(100);

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
//I Numeri
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
axis.renderer.inversed = true;
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
axis2.renderer.inversed = true;

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

var labelMetricValueReverse = chart.radarContainer.createChild(am4core.Label);
labelMetricValueReverse.isMeasured = false;
labelMetricValueReverse.fontSize = "1.5em";
labelMetricValueReverse.x = am4core.percent(50);
labelMetricValueReverse.paddingBottom = 15;
labelMetricValueReverse.horizontalCenter = "middle";
labelMetricValueReverse.verticalCenter = "bottom";
//labelMetricValueReverse.dataItem = data;
labelMetricValueReverse.text = data.score.toFixed(1);
//labelMetricValueReverse.text = "{score}";
labelMetricValueReverse.fill = am4core.color(matchingGrade.color);

/**
 * Advice
 */

//var label2 = chart.radarContainer.createChild(am4core.Label);
var labelAdvice = chart.createChild(am4core.Label);
labelAdvice.isMeasured = false;
labelAdvice.fontSize = "5em";
//labelAdvice.paddingTop = 150;
labelAdvice.horizontalCenter = "left";
labelAdvice.verticalCenter = "bottom";
//labelAdvice.text = matchingGrade.title.toUpperCase();
labelAdvice.text = "";
labelAdvice.fill = am4core.color(matchingGrade.color);
labelAdvice.dx = 280;
labelAdvice.dy = 340;

/**
 * Hand
 */
var handReverse = chart.hands.push(new am4charts.ClockHand());
handReverse.axis = axis2;
handReverse.radius = am4core.percent(85);
handReverse.innerRadius = am4core.percent(50);
handReverse.startWidth = 10;
handReverse.pixelHeight = 10;
handReverse.pin.disabled = true;
handReverse.value = data.score;
handReverse.fill = am4core.color("#444");
handReverse.stroke = am4core.color("#000");

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

handReverse.events.on("positionchanged", function(){
    var t = axis2.positionToValue(handReverse.currentPosition).toFixed(0);
    var formattedValue = chart.numberFormatter.format(t, "#.#a");
    labelMetricValueReverse.text = formattedValue;

    var value2 = axis.positionToValue(handReverse.currentPosition);
    var matchingGrade = lookUpGrade(axis.positionToValue(handReverse.currentPosition), data.gradingData);
    labelAdvice.text = matchingGrade.advice.toUpperCase();
    labelAdvice.fill = am4core.color(matchingGrade.color);
    labelAdvice.stroke = am4core.color(matchingGrade.color);
    labelMetricValueReverse.fill = am4core.color(matchingGrade.color);
})

function updateGaugeReverse() {
    var value = getDominantValReverse()

    if (value < chartMin) {
        value = chartMin;
    } else if (value > chartMax) {
        value = chartMax;
    }
    handReverse.showValue(value, 1000, am4core.ease.cubicOut);
}

function getDominantValReverse(){
    if (ax.lontano.value != 0){
        return Number(Math.abs(ax.lontano.value - 180))
    }

    if (ax.medio.value != 0){
        return Number(Math.abs(ax.medio.value - 180))
    }

    if(ax.vicino.value){
        return Number(Math.abs(ax.vicino.value - 180))
    }

    return 0;
}