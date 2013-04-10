$(document).ready(function() {
    var d = new Date();
    var n = d.getHours();
    var diff = n - serverHours;
    var hours = sendHour + diff;
    if ($('input[name=hours]').val() == '' ) {
        $('input[name=hours]').val(hours);
    }
    $('a#before').click(function() {
        hours = $('input[name=hours]').val();
        if (hours > 0) {
            $('input[name=hours]').val(parseInt(hours)-1);
        }
    })
    $('a#after').click(function() {
        hours = $('input[name=hours]').val();
        if (hours < 24) {
            $('input[name=hours]').val(parseInt(hours)+1);
        }
    })
})
// inner variables
var canvas, ctx;
var clockRadius = 100;
var clockImage;
// draw functions :
function clear() { // clear canvas function
    ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
}
function drawScene() { // main drawScene function
    clear(); // clear canvas
    hours = $('input[name=hours]').val();
    var minutes = 0;
    var hour = hours > 12 ? hours - 12 : hours;
    var minute = 0;
    // save current context
    ctx.save();
    // draw clock image (as background)
    ctx.drawImage(clockImage, 0, 0, 200, 200);
    ctx.translate(canvas.width / 2, canvas.height / 2);
    ctx.beginPath();
    // draw numbers
    ctx.font = '15px Arial';
    ctx.fillStyle = '#000';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    for (var n = 1; n <= 12; n++) {
        var theta = (n - 3) * (Math.PI * 2) / 12;
        var x = clockRadius * 0.7 * Math.cos(theta);
        var y = clockRadius * 0.7 * Math.sin(theta);
        ctx.fillText(n, x, y);
    }
    // draw hour
    ctx.save();
    var theta = (hour - 3) * 2 * Math.PI / 12;
    ctx.rotate(theta);
    ctx.beginPath();
    ctx.moveTo(-15, -5);
    ctx.lineTo(-15, 5);
    ctx.lineTo(clockRadius * 0.5, 1);
    ctx.lineTo(clockRadius * 0.5, -1);
    ctx.fill();
    ctx.restore();
    // draw minute
    ctx.save();
    var theta = (minute - 15) * 2 * Math.PI / 60;
    ctx.rotate(theta);
    ctx.beginPath();
    ctx.moveTo(-15, -4);
    ctx.lineTo(-15, 4);
    ctx.lineTo(clockRadius * 0.8, 1);
    ctx.lineTo(clockRadius * 0.8, -1);
    ctx.fill();
    ctx.restore();

    ctx.restore();
    $('#hours').html(hours)
}
// initialization
$(function() {
    canvas = document.getElementById('canvas');
    ctx = canvas.getContext('2d');
    // var width = canvas.width;
    // var height = canvas.height;
    clockImage = new Image();
    clockImage.src = '/images/cface.png';
    setInterval(drawScene, 1000); // loop drawScene
});