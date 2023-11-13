const charts = [
    document.getElementById("chartdiv"),
    document.getElementById("chartdiv2"),
]

const reverseCharts = [
    document.getElementById("chartdiv-reverse"),
    document.getElementById("chartdiv2-reverse"),
]

function inverse(cb){
    let button_id = cb.id;
    let check_val = cb.checked;

    if (button_id == "reverse1"){
        if (check_val){
            charts[0].classList.add("d-none")
            reverseCharts[0].classList.remove("d-none")
        } else {
            reverseCharts[0].classList.add("d-none")
            charts[0].classList.remove("d-none")
        }
    }

    if (button_id == "reverse2"){
        if (check_val){
            charts[1].classList.add("d-none")
            reverseCharts[1].classList.remove("d-none")
        } else {
            reverseCharts[1].classList.add("d-none")
            charts[1].classList.remove("d-none")
        }
    }

}