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
function blendingTabButton(buttonEvent, buttonName) {
    let i, tabcontent, tablinks;

    tabcontent = document.getElementsByClassName("Btabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("Btablinks");
    for (i = 0; i < tabcontent.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the link that opened the tab
    document.getElementById(buttonName).style.display = "block";
    buttonEvent.currentTarget.className += " active";
    return true;
}




let wSpinnerPrefix = '';
let wSpinnerVowel = '';
let wSpinnerSuffix = '';

function wordSpinner(pvs, letters) {

    if (pvs == 'p') { wSpinnerPrefix = letters; }
    else {
        if (pvs == 'v') { wSpinnerVowel = letters; }
        else { wSpinnerSuffix = letters; }
    }

    document.getElementById('spin0').innerHTML = wSpinnerPrefix + wSpinnerVowel + wSpinnerSuffix;
    return true;
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

        // flip the opacity to full
        const testElements = document.getElementsByClassName("dimmable");
        testElements.forEach(span => span.style.opacity = 1.0)


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
        StopWatch.watchRunning = false;
        return (false);		// prevent page load
    },


    reset: function () {
        clearInterval(StopWatch.timerObj);
        StopWatch.watchRunning = false;
        StopWatch.timerCount = 0;
        document.getElementById("timer").innerHTML = 0;

        // flip the opacity to faint
        const testElements = document.getElementsByClassName("dimmable");
        testElements.forEach(span => span.style.opacity = 0.1)

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




