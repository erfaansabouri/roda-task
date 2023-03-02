<!DOCTYPE html>
<html>
<head>
    <title>Show Location Points on Image</title>
    <link rel="stylesheet" href="{{ asset('css/uikit.min.css') }}" />
    <script src="{{ asset('js/uikit.min.js') }}"></script>
    <script src="{{ asset('js/uikit-icons.min.js') }}"></script>
    <script src="{{ asset('js/heatmap.min.js') }}"></script>
</head>
<style>
    #myHeat {
        background-image: url({{ asset('layouts/mari.png') }});
        height: 500px;
    }
</style>

<body>
<div uk-grid>
    <div class="uk-card-media-left uk-cover-container">
        <canvas id="myCanvas"></canvas>
        <div id="myHeat"></div>
    </div>
    <div>
        <div class="uk-card-body">
            <h3 class="uk-card-title">Instructions</h3>
            <form class="uk-form-horizontal uk-margin-large">
                <div class="uk-margin">
                    <label class="uk-form-label uk-text-bold" for="form-horizontal-text">Step 1</label>
                    <div class="uk-form-controls">
                        <span>Select origin point (0,0).</span> <span id="step-1" class="uk-text-bold uk-text-success"></span>
                    </div>
                </div>
                <hr class="uk-divider-small">
                <div class="uk-margin">
                    <label class="uk-form-label uk-text-bold" for="form-horizontal-text">Step 2</label>
                    <div class="uk-form-controls">
                        <span>Select initial point ({{ $movements->first()->x }},{{ $movements->first()->y }}).</span> <span id="step-2" class="uk-text-bold uk-text-success"></span>
                    </div>
                </div>
                <hr class="uk-divider-small">
                <div class="uk-margin">
                    <label class="uk-form-label uk-text-bold" for="form-horizontal-text">Draw movements pattern</label>
                    <div class="uk-form-controls">
                        <button type="button" class="uk-button uk-button-primary" disabled id="draw-movements-pattern">Draw</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
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
            var step_1 = document.getElementById('step-1');
            step_1.textContent = `=> Done! (${rectX},${rectY})`;
            console.log("Origin done")
            drawPoint(originPoint);
        } else if (initialPoint === null) {
            initialPoint = {x: rectX, y: rectY};
            console.log("Initial Done")
            var step_2 = document.getElementById('step-2');
            step_2.textContent = `=> Done! (${rectX},${rectY})`;

            var draw_movements_pattern_btn = document.getElementById('draw-movements-pattern');
            draw_movements_pattern_btn.disabled = false;
            drawPoint(initialPoint);
            const distance_in_pixel = Math.sqrt(Math.pow(initialPoint.x - originPoint.x, 2) + Math.pow(initialPoint.y - originPoint.y, 2));
            console.log(`Distance in pixel: ${distance_in_pixel}px`);
            console.log(`Distance in meter: ${distance_in_meter}m`);
            var scale = distance_in_meter / distance_in_pixel;
            console.log("Scale: " + scale);
        } else {

        }
    });

    var draw_movements_pattern_btn = document.getElementById('draw-movements-pattern');
    draw_movements_pattern_btn.addEventListener('click', function (event) {

        if(originPoint === null || initialPoint === null) {
            alert('err');
        }else{
            var pixel_points = [];
            var heat_points = [];
            for (var i = 0; i < points.length; i++) {
                pixel_points.push(mapPoint(points[i],{x: 0, y: 0}, originPoint,points[0], initialPoint));
                heat_points.push({x: pixel_points[i].x , y: pixel_points[i].y, value: 1});
            }
            for (var j = 0; j < pixel_points.length - 1; j++) {
                drawLine(pixel_points[j], pixel_points[j+1])
            }
            var heatmapInstance = h337.create({
                container: document.getElementById('myHeat')
            });
            heatmapInstance.setData({
                max: 15,
                data: heat_points
            });
            draw_movements_pattern_btn.classList.add('uk-button-success');
            draw_movements_pattern_btn.classList.remove('uk-button-primary');
            draw_movements_pattern_btn.textContent = 'Done! ' + pixel_points.length + " line drawn."
        }
    });


    // Function to draw a point on the canvas
    function drawPoint(point) {
        ctx.beginPath();
        ctx.arc(point.x, point.y, 1, 0, 2 * Math.PI);
        ctx.fillStyle = 'red';
        ctx.fill();
    }

    function drawLine(pointA, pointB)
    {
        ctx.strokeStyle = 'blue';
        ctx.lineWidth = 1;
        ctx.beginPath();
        ctx.moveTo(pointA.x, pointA.y);
        ctx.lineTo(pointB.x, pointB.y);
        ctx.stroke();
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
