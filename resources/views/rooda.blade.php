<!DOCTYPE html>
<html>
<head>
    <title>Show Location Points on Image</title>
    <style>
        #myCanvas {
            position: absolute;
            top: 0;
            left: 0;
        }
    </style>
</head>
<body>
<canvas id="myCanvas"></canvas>
<script>
    var distance_in_meter = "{{ $distance }}";
    var points = [];
    @foreach($movements as $movement)
    points.push({x: "{{ $movement->x }}", y: "{{ $movement->y }}"})
    @endforeach
    const canvas = document.getElementById('myCanvas');
    const ctx = canvas.getContext('2d');
    let originPoint = null;
    let initialPoint = null;

    const img = new Image();
    img.onload = function () {
        canvas.width = img.width;
        canvas.height = img.height;
        ctx.drawImage(img, 0, 0);
    };
    img.src = "{{ asset('layouts/mari.png') }}";

    canvas.addEventListener('click', function (event) {
        const rectX = event.offsetX;
        const rectY = event.offsetY;
        console.log(rectX, rectY)
        if (originPoint === null) {
            originPoint = {x: rectX, y: rectY};
            console.log("Origin done")
            drawPoint(originPoint);
        } else if (initialPoint === null) {
            initialPoint = {x: rectX, y: rectY};
            console.log("Initial Done")
            drawPoint(initialPoint);
            const distance_in_pixel = Math.sqrt(Math.pow(initialPoint.x - originPoint.x, 2) + Math.pow(initialPoint.y - originPoint.y, 2));
            console.log(`Distance in pixel: ${distance_in_pixel}px`);
            console.log(`Distance in meter: ${distance_in_meter}m`);
            var scale = distance_in_meter / distance_in_pixel;
            console.log("Scale: " + scale);
        } else {
            var pixel_points = [];
            for (var i = 0; i < points.length; i++) {
                pixel_points.push(mapPoint(points[i],{x: 0, y: 0}, originPoint,points[0], initialPoint));
            }

            ctx.moveTo(pixel_points[0].x, pixel_points[0].y);
            for (var j = 1; j < pixel_points.length; j++) {
                console.log(j + "-pixel mapped point", pixel_points[j])
                console.log(j + "-meter mapped point", points[j])
                drawPoint(pixel_points[j])
            }
        }
    });

    // Function to draw a point on the canvas
    function drawPoint(point) {
        ctx.beginPath();
        ctx.arc(point.x, point.y, 1, 0, 2 * Math.PI);
        ctx.fillStyle = 'red';
        ctx.fill();
    }

    function mapPoint(pointToBeMapped, originPoint, originPointPrim, initialPoint, initialPointPrim) {
        // Calculate the scale factor and translation
        const deltaX = originPointPrim.x - originPoint.x;
        const deltaY = originPointPrim.y - originPoint.y;
        const deltaXprime = initialPointPrim.x - initialPoint.x;
        const deltaYprime = initialPointPrim.y - initialPoint.y;
        const scaleFactorX = deltaXprime / deltaX;
        const scaleFactorY = deltaYprime / deltaY;
        const translateX = initialPointPrim.x - scaleFactorX * initialPoint.x;
        const translateY = initialPointPrim.y - scaleFactorY * initialPoint.y;

        // Map the point to its new coordinates
        const mappedX = scaleFactorX * pointToBeMapped.x + translateX;
        const mappedY = scaleFactorY * pointToBeMapped.y + translateY;

        // Return the mapped point as an object
        return {
            x: mappedX,
            y: mappedY
        };
    }



</script>
</body>
</html>
