
am4core.useTheme(am4themes_animated);

var chartMin2 = 0;
var chartMax2 = 180;

var ax2 = {
    lontano: document.getElementById("Gauge2_l"),
    medio: document.getElementById("Gauge2_m"),
    vicino: document.getElementById("Gauge2_v"),
}

var data2 = {
    score: getDominantVal2(),
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
var chart2 = am4core.create("chartdiv2-reverse", am4charts.GaugeChart);
chart2.hiddenState.properties.opacity = 0;
chart2.fontSize = 11;
chart2.innerRadius = am4core.percent(90);
chart2.resizable = true;

/**
 Grade and Target above the gauge
 */

var topContainer2 = chart2.chartContainer.createChild(am4core.Container);
topContainer2.layout = "absolute";
topContainer2.toBack();
topContainer2.width = am4core.percent(100);

// GRADE
var leftTopContainer2= topContainer2.createChild(am4core.Container);
leftTopContainer2.layout = "vertical";

var gradeLabel2 = leftTopContainer2.createChild(am4core.Label);
gradeLabel2.text = "Grade";
gradeLabel2.fill = am4core.color("#757575");
gradeLabel2.fontSize = "0";
gradeLabel2.fontWeight = 0;
gradeLabel2.align = "left";
var gradeValue2 = leftTopContainer2.createChild(am4core.Label);
gradeValue2.text = "B";
gradeValue2.tooltipText = "Grade is calculated from Metric normalized value.";
gradeValue2.tooltip.pointerOrientation = "left";
gradeValue2.tooltip.dx = 12;

gradeValue2.fontSize = "";
gradeValue2.fontWeight = 0;
gradeValue2.align = "left";
gradeValue2.opacity = 0;
gradeValue2.background = new am4core.RoundedRectangle();
gradeValue2.background.cornerRadius(4, 4, 4, 4);
gradeValue2.padding(8, 12, 8, 12);
//A=4BA45E, B=B0CE55, C=E9FE67, D=FEFF55, E=F5CD45, F=EC6F2F, G=E93224
gradeValue2.background.fill = am4core.color("#fff");

// TARGET
var rightTopContainer2 = topContainer2.createChild(am4core.Container);
rightTopContainer2.layout = "vertical";
rightTopContainer2.align = "right";

var targetLabel2 = rightTopContainer2.createChild(am4core.Label);
targetLabel2.text = "";
targetLabel2.fill = am4core.color("#757575");
targetLabel2.fontSize = "0";
targetLabel2.fontWeight = 0;
targetLabel2.align = "right";
var targetValue2 = rightTopContainer2.createChild(am4core.Label);
targetValue2.text = "";
targetValue2.fontSize = "";
targetValue2.fontWeight = 0;
targetValue2.align = "right";

/**
 * Normal axis
 */
var axis2 = chart2.xAxes.push(new am4charts.ValueAxis());
axis2.min = chartMin2;
axis2.max = chartMax2;
axis2.strictMinMax = true;
axis2.renderer.radius = am4core.percent(70);
axis2.renderer.inside = true;
axis2.renderer.line.strokeOpacity = 0;
axis2.renderer.ticks.template.disabled = false;
axis2.renderer.ticks.template.strokeOpacity = 0;
axis2.renderer.ticks.template.strokeWidth = 0.5;
axis2.renderer.ticks.template.length = 5;
axis2.renderer.grid.template.disabled = true;
axis2.renderer.labels.template.radius = am4core.percent(15);
axis2.renderer.labels.template.fontSize = "0.9em";
axis2.renderer.labels.template.fill = am4core.color("#757575");
axis2.renderer.inversed = true;

/**
 * Axis for ranges
 */
var axis2 = chart2.xAxes.push(new am4charts.ValueAxis());
axis2.min = chartMin2;
axis2.max = chartMax2;
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

for (let grading of data2.gradingData) {
    var range2 = axis2.axisRanges.create();
    range2.axisFill.fill = am4core.color(grading.color);

    range2.axisFill.fillOpacity = 1;

    range2.axisFill.zIndex = -1;
    range2.value = grading.lowScore > chartMin2 ? grading.lowScore : chartMin2;
    range2.endValue = grading.highScore < chartMax2 ? grading.highScore : chartMax2;
    range2.grid.strokeOpacity = 0;
    range2.stroke = am4core.color(grading.color).lighten(-0.1);
    range2.label.inside = true;
    range2.label.text = grading.title.toUpperCase();
    range2.label.inside = true;
    range2.label.location = 0.5;
    range2.label.inside = true;
    range2.label.radius = am4core.percent(10);
    range2.label.paddingBottom = -5; // ~half font size
    range2.label.fontSize = "0.9em";
}

var matchingGrade2 = lookUpGrade(data2.score, data2.gradingData);

/**
 * Metric Value
 */

var labelMetricValue2Reverse = chart2.radarContainer.createChild(am4core.Label);
labelMetricValue2Reverse.isMeasured = false;
labelMetricValue2Reverse.fontSize = "1.5em";
labelMetricValue2Reverse.x = am4core.percent(50);
labelMetricValue2Reverse.paddingBottom = 15;
labelMetricValue2Reverse.horizontalCenter = "middle";
labelMetricValue2Reverse.verticalCenter = "bottom";
//labelMetricValue.dataItem = data;
labelMetricValue2Reverse.text = data2.score.toFixed(1);
//labelMetricValue.text = "{score}";
labelMetricValue2Reverse.fill = am4core.color(matchingGrade2.color);

/**
 * Advice
 */

//var label2 = chart.radarContainer.createChild(am4core.Label);
var labelAdvice2 = chart2.createChild(am4core.Label);
labelAdvice2.isMeasured = false;
labelAdvice2.fontSize = "5em";
//labelAdvice.paddingTop = 150;
labelAdvice2.horizontalCenter = "middle";
labelAdvice2.verticalCenter = "bottom";
//labelAdvice.text = matchingGrade.title.toUpperCase();
labelAdvice2.text = "";
labelAdvice2.fill = am4core.color(matchingGrade2.color);
labelAdvice2.dx = 280;
labelAdvice2.dy = 340;

/**
 * Hand
 */
var hand2Reverse = chart2.hands.push(new am4charts.ClockHand());
hand2Reverse.axis = axis2;
hand2Reverse.radius = am4core.percent(85);
hand2Reverse.innerRadius = am4core.percent(50);
hand2Reverse.startWidth = 10;
hand2Reverse.pixelHeight = 10;
hand2Reverse.pin.disabled = true;
hand2Reverse.value = data2.score;
hand2Reverse.fill = am4core.color("#444");
hand2Reverse.stroke = am4core.color("#000");

var handTarget2 = chart2.hands.push(new am4charts.ClockHand());
handTarget2.axis = axis2;
handTarget2.radius = am4core.percent(100);
handTarget2.innerRadius = am4core.percent(105);
handTarget2.fill = axis2.renderer.line.stroke;
handTarget2.stroke = axis2.renderer.line.stroke;
handTarget2.pin.disabled = true;
handTarget2.pin.radius = 10;
handTarget2.startWidth = 10;
handTarget2.fill = am4core.color("#fff");
handTarget2.stroke = am4core.color("#fff");

hand2Reverse.events.on("positionchanged", function(){
    var t2 = axis2.positionToValue(hand2Reverse.currentPosition).toFixed(0);
    var formattedValue2 = chart2.numberFormatter.format(t2, "#.#a");
    labelMetricValue2Reverse.text = formattedValue2;

    var value2 = axis2.positionToValue(hand2Reverse.currentPosition);
    var matchingGrade = lookUpGrade(axis2.positionToValue(hand2Reverse.currentPosition), data2.gradingData);
    labelAdvice2.text = matchingGrade.advice.toUpperCase();
    labelAdvice2.fill = am4core.color(matchingGrade.color);
    labelAdvice2.stroke = am4core.color(matchingGrade.color);
    labelMetricValue2Reverse.fill = am4core.color(matchingGrade.color);
})


function updateGauge2Reverse() {
    var value2 = getDominantVal2();

    if (value2 < chartMin2) {
        value2 = chartMin2;
    } else if (value2 > chartMax2) {
        value2 = chartMax2;
    }

    hand2Reverse.showValue(value2, 1000, am4core.ease.cubicOut);
}

function getDominantVal2(){
    if (ax2.lontano.value != 0){
        return Number(ax2.lontano.value)
    }

    if (ax2.medio.value != 0){
        return Number(ax2.medio.value)
    }

    if(ax2.vicino.value){
        return Number(ax2.vicino.value)
    }

    return 0;
}