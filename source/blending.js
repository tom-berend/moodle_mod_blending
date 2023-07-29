//     // sometimes the host wants to write without a refresh
//    function writeLog(payload64) {
//         let b = Buffer.from(payload64, 'base64')
//         const msg = JSON.parse(b.toString())

//         writeMoodleLog(msg);
//     }



// this hides headers and completion button without modifying the theme.  might not work on older versions, but no big deal
// (alternate suggestion from forums is to hide all <h2> and <h3> tags)
hideActivities();                                       // run first time when page is being loaded
window.addEventListener('load', hideActivities);        // run a second time on load, just in case
function hideActivities() {
    const completeButton = document.getElementsByClassName("activity-header"); // a list of matching elements, *not* the element itself
    if (completeButton)
        completeButton[0].style.display = 'none';
    const secondaryNavigation = document.getElementsByClassName("secondary-navigation"); // a list of matching elements, *not* the element itself
    if (secondaryNavigation)
        secondaryNavigation[0].style.display = 'none';
    const pageheader = document.getElementById("page-header");
    if (pageheader) {
        pageheader.style.display = 'none';
    }

}


// this function called when a tab is clicked
function blendingTabButton(thisTab, nTabs, tabPrefix, active, notactive) {
    let tabName;
    console.log('blendingTabButton', thisTab, nTabs, tabPrefix, active, notactive)

    // clear ALL tabs
    for (var i = 1; i <= nTabs; i++) {
        tabName = tabPrefix + 'tab-' + i.toString();
        console.log('tabName', tabName)
        // console.log('clearing ID', tabName)
        document.getElementById(tabName).style.display = 'none';

        tabheaderName = tabPrefix + 'tab' + i.toString();
        console.log('tabheaderName', tabheaderName)
        document.getElementById(tabheaderName).style.backgroundColor = notactive;
        document.getElementById(tabheaderName).style.color = 'black';
        console.log(tabheaderName, 'set to white')
    }

    // now set the one we want
    tabName = tabPrefix + 'tab-' + thisTab.toString();
    // console.log('setting ID ', tabName)
    console.log('tabName', tabName)
    document.getElementById(tabName).style.display = 'block';
    tabheaderName = tabPrefix + 'tab' + thisTab.toString();
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


StopWatch = {
    formObject: undefined,
    s: undefined,               // use this to point at the 'seconds' object
    watchRunning: false,        // can't start again if watch is running
    timerObj: null,
    timerCount: 0,

    init: function () {
        // our form is called 'stopwatchForm'
        StopWatch.formObject = document.getElementById("stopwatchForm");
        if (StopWatch.formObject === null) return;  // not on this page

        for (var index = 0; index < StopWatch.formObject.length; ++index) {
            if (StopWatch.formObject[index].name == 'sec') {
                StopWatch.s = StopWatch.formObject[index];
            }
        }
    },

    start: function () {		// click on START
        if (StopWatch.watchRunning) {
            return false;
        }
        StopWatch.timerCount = 0;
        let timer = document.getElementById("timer");
        if (timer)  // don't try if not on this page
            document.getElementById("timer").innerHTML = StopWatch.timerCount.toString(); // 0 of course

        StopWatch.init();
        StopWatch.timerObj = setInterval(StopWatch.do_time, 1000);
        StopWatch.watchRunning = true;
        return (false);		// prevent page load
    },

    stop: function () {		// click on STOP
        clearInterval(StopWatch.timerObj);      // stop the timer
        console.log('document score', document.getElementById('score'));
        StopWatch.watchRunning = false;
        return (false);		// prevent page load
    },

    // save: function() {		// click on SAVE
    //     StopWatch.watchRunning=false;
    // 	clearInterval(timer);
    // 	StopWatch.formObject.submit();
    // },

    reset: function () {
        clearInterval(StopWatch.timerObj);
        StopWatch.watchRunning = false;
        StopWatch.timerCount = 0;
        document.getElementById("timer").innerHTML = 0;
        return (false);		// prevent page load
    },

    do_time: function () {    // updates the HTML page
        // StopWatch.init();
        // parseInt() doesn't work here...
        StopWatch.timerCount += 1;
        document.getElementById("timer").innerHTML = StopWatch.timerCount.toString();
        document.getElementById('score').value = StopWatch.timerCount.toString();      // in case the form is submitted while clock is running
        return (false);
    }

};




