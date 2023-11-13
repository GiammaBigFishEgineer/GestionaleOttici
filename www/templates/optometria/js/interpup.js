const interpFields = {
    row_l: 
    [
        document.getElementById("int_dx_l"),
        document.getElementById("int_sx_l")
    ],
    row_m: 
    [
        document.getElementById("int_dx_m"),
        document.getElementById("int_sx_m"),
    ],
    row_v: 
    [
        document.getElementById("int_dx_v"),
        document.getElementById("int_sx_v"),
    ]

}

const interpupRes = {
    tot_l : document.getElementById("int_tot_l"),
    tot_m : document.getElementById("int_tot_m"),
    tot_v : document.getElementById("int_tot_v"),
}

function updateTot(){
    interpupRes.tot_l.value = Number(interpFields.row_l[0].value) + Number(interpFields.row_l[1].value)
    interpupRes.tot_m.value = Number(interpFields.row_m[0].value) + Number(interpFields.row_m[1].value)
    interpupRes.tot_v.value = Number(interpFields.row_v[0].value) + Number(interpFields.row_v[1].value)

}