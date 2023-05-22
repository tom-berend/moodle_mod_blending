//     // sometimes the host wants to write without a refresh
//    function writeLog(payload64) {
//         let b = Buffer.from(payload64, 'base64')
//         const msg = JSON.parse(b.toString())

//         writeMoodleLog(msg);
//     }




window.addEventListener('load', hideActivities);

function hideActivities() {
    const completeButton = document.getElementsByClassName("activity-header"); // a list of matching elements, *not* the element itself
    if (completeButton)
        completeButton[0].style.display = 'none';
    const secondaryNavigation = document.getElementsByClassName("secondary-navigation"); // a list of matching elements, *not* the element itself
    if (secondaryNavigation)
        secondaryNavigation[0].style.display = 'none';
    const pageheader = document.getElementById("page-header");
    if (pageheader)
        pageheader.style.display = 'none';

    console.log(completeButton)
}


// this function called when a tab is clicked
function blendingTabButton(thisTab, nTabs, tabPrefix, active, notactive) {
    let tabName;

    // clear ALL tabs
    for (var i = 1; i <= nTabs; i++) {
        tabName = tabPrefix + '-' + i.toString();
        // console.log('clearing ID', tabName)
        document.getElementById(tabName).style.display = 'none';

        tabheaderName = tabPrefix + 'header' + i.toString();
        document.getElementById(tabheaderName).style.backgroundColor = notactive;
        document.getElementById(tabheaderName).style.color = 'black';
        console.log(tabheaderName, 'set to white')
    }

    // now set the one we want
    tabName = tabPrefix + '-' + thisTab.toString();
    // console.log('setting ID ', tabName)
    document.getElementById(tabName).style.display = 'block';
    tabheaderName = tabPrefix + 'header' + thisTab.toString();
    document.getElementById(tabheaderName).style.backgroundColor = active;
    document.getElementById(tabheaderName).style.color = 'white';
}



let wSpinnerPrefix = '';
let wSpinnerVowel = '';
let wSpinnerSuffix = '';

function wordSpinner(pvs, letters) {
    console.log('wordspinner', pvs, letters)
    // shift all the existing words down
    document.getElementById('spin3').innerHTML = document.getElementById('spin2').innerHTML;
    document.getElementById('spin2').innerHTML = document.getElementById('spin1').innerHTML;
    document.getElementById('spin1').innerHTML = document.getElementById('spin0').innerHTML;

    if (pvs == 'p') { wSpinnerPrefix = letters; }
    else {
        if (pvs == 'v') { wSpinnerVowel = letters; }
        else { wSpinnerSuffix = letters; }
    }

    console.log('pre', wSpinnerPrefix, 'vow', wSpinnerVowel, 'suf', wSpinnerSuffix)
    document.getElementById('spin0').innerHTML = wSpinnerPrefix + wSpinnerVowel + wSpinnerSuffix;
}


function wordSpinnerPlusE(pvs, letters) {

    // shift all the existing words down
    document.getElementById('spin3').innerHTML = document.getElementById('spin2').innerHTML;
    document.getElementById('spin2').innerHTML = document.getElementById('spin1').innerHTML;
    document.getElementById('spin1').innerHTML = document.getElementById('spin0').innerHTML;

    if (pvs == 'p') { wSpinnerPrefix = letters; }
    else {
        if (pvs == 'v') { wSpinnerVowel = letters; }
        else { wSpinnerSuffix = letters; }
    }

    document.getElementById('spin0').innerHTML = wSpinnerPrefix + wSpinnerVowel + wSpinnerSuffix + 'e';
}


