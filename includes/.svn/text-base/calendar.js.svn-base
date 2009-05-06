function y2k(number) { return (number < 1000) ? number + 1900 : number; }
function padout(number) { return (number < 10) ? '0' + number : number; }

var today = new Date();
var day = today.getDate(), month = today.getMonth(), year = y2k(today.getYear()), whichOne = 0;

function restart() {
document.form.elements[whichOne].value = '' + padout(month - 0 + 1) + '/' + padout(day) + '/' + year;
mywindow.close();
}

function newWindow(dateInputName) {
whichOne = dateInputName;
LeftPosition=(screen.width)?(screen.width-300)/2:100;
TopPosition=(screen.height)?(screen.height-180)/2:100;
day = today.getDate(), month = today.getMonth(), year = y2k(today.getYear());
//mywindow=open('../cal.htm','myname','resizable=no,width=300,height=180,top='+TopPosition+',left='+LeftPosition+',');
//mywindow.location.href = '../cal.htm';
mywindow=open('cal.htm','s_datecontrol','resizable=no,width=300,height=200,top='+TopPosition+',left='+LeftPosition+',');
mywindow.location.href = 'cal.htm';
if (mywindow.opener == null) mywindow.opener = self;
}